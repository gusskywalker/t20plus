import { Component, ViewChild, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { PowerPickRow } from '../../../shared/power-pick-row/power-pick-row';
import { Power, Prerequisite } from '../../../api.service';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { CharacterCreationSaving } from '../character-creation-saving/character-creation-saving';
import { matchesClassPower, matchesGeneralPower, resolveAvailablePowers } from '../../../shared/helpers/available-power-picks-solver/available-power-picks-solver';
import { resolveCasterSpellSlots } from '../resolve-caster-spell-slots';
import { calculateMaxCasterCircle } from '../../../shared/helpers/calculators/calculate-max-caster-circle/calculate-max-caster-circle';
import { REPEATABLE_POWER_IDS, TATUAGEM_MISTICA_POWER_ID, CANCAO_DOS_MARES_POWER_ID, MAGIA_DAS_FADAS_POWER_ID } from '../../../shared/helpers/power-pick-constants/power-pick-constants';
import { resolveLimitedSpellChoicePowers } from '../../../shared/helpers/resolve-limited-spell-choice-powers/resolve-limited-spell-choice-powers';
import { resolveWaivedPrerequisitePowerIds } from '../../../shared/helpers/resolve-waived-prerequisite-power-ids/resolve-waived-prerequisite-power-ids';
import { getActiveEffects } from '../../../shared/helpers/get-active-effects/get-active-effects';
import { resolveTrainedSkillIds } from '../../../shared/helpers/resolve-trained-skill-ids/resolve-trained-skill-ids';
import { isTradicaoPerdidaPower, resolveTradicaoPerdidaClassOptions } from '../../../shared/helpers/calculators/calculate-max-pm/calculate-max-pm-edge-cases/tradicao-perdida';

interface LevelPowerRow {
  /** Index into orderedClassIds/classPowerIds — same index means same level. */
  index: number;
  /** The character's overall level at this row (1-based). */
  characterLevel: number;
  classId: number;
  className: string;
  /** How many levels of this specific class the character has had up to and including this row (class-relative, not character-relative — matters for multiclassing). */
  classLevel: number;
}

@Component({
  selector: 'app-character-creation-powers-step',
  imports: [CardHeader, SearchableDropdown, PowerPickRow, CharacterCreationSaving],
  templateUrl: './character-creation-powers-step.html',
  styleUrl: './character-creation-powers-step.scss',
})
export class CharacterCreationPowersStep {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  @ViewChild(CharacterCreationSaving) private saving!: CharacterCreationSaving;

  // Whether ANY of the character's classes actually grants spell slots —
  // not just whether Arcanista is the level-1 class, since a caster class
  // can be reached via multiclassing at any level. Mirrors
  // resolveCasterSpellSlots' own generic "any granted power carrying
  // starting_spell_count" detection, so this stays in sync automatically
  // once a second caster class exists.
  // A real caster class isn't the only reason to visit spells-step — Qareen's
  // Tatuagem Mística/Sereia-Tritão's Canção dos Mares (spells-step-edge-
  // cases) grant their own spell pick regardless of class, so a Qareen
  // Guerreiro still needs to land there instead of saving straight from here.
  protected readonly needsSpellsStep = computed(
    () =>
      resolveCasterSpellSlots(this.draft, this.staticRegistry.powers).length > 0 ||
      this.draft.grantedPowerIds().has(TATUAGEM_MISTICA_POWER_ID) ||
      this.draft.grantedPowerIds().has(CANCAO_DOS_MARES_POWER_ID) ||
      this.draft.grantedPowerIds().has(MAGIA_DAS_FADAS_POWER_ID) ||
      resolveLimitedSpellChoicePowers(this.draft.grantedPowerIds(), this.staticRegistry.powers).length > 0,
  );

  constructor() {
    // Reset classPowerIds whenever orderedClassIds actually changes (a
    // level added/removed, or which class occupies a level) — a prior
    // level's class pick could otherwise silently point at the wrong
    // level, same reasoning/pattern as classSkillChoicesSourceKey in step
    // 6. Keyed on the whole ordered list, not just its length, since
    // inserting/removing a level anywhere shifts every later index's
    // meaning.
    effect(() => {
      const orderedClassIds = this.draft.orderedClassIds();
      const key = orderedClassIds.join(',');
      if (this.draft.classPowerIdsSourceKey() === key) {
        return;
      }
      this.draft.classPowerIdsSourceKey.set(key);
      this.draft.classPowerIds.set(orderedClassIds.map(() => null));
    });

    // Clear the bonus Poder Geral whenever Nenhuma is (re-)picked back on
    // step 7 — the power only means anything alongside a real complication,
    // and its own section here is hidden once that's true anyway (see the
    // @if in the template), so a stale value should be cleared too.
    effect(() => {
      if (this.draft.generalComplicationId() === null) {
        this.draft.generalComplicationPowerId.set(null);
      }
    });

    // Drops a bonus power pick once its granting power is no longer on the
    // draft (e.g. the race changed back on step 1).
    effect(() => {
      const grantingIds = new Set(this.bonusPowerChoiceRows().map((row) => row.power.id));
      const choices = this.draft.bonusPowerChoiceIds();
      const staleKeys = Object.keys(choices).filter((key) => !grantingIds.has(Number(key)));
      if (staleKeys.length === 0) {
        return;
      }
      const next = { ...choices };
      staleKeys.forEach((key) => delete next[Number(key)]);
      this.draft.bonusPowerChoiceIds.set(next);
    });

    // Drops a Tradição Perdida class pick once its power is no longer on the
    // draft.
    effect(() => {
      const granted = this.draft.grantedPowerIds();
      const picks = this.draft.tradicaoPerdidaClassIds();
      const staleKeys = Object.keys(picks).filter((key) => !granted.has(Number(key)));
      if (staleKeys.length === 0) {
        return;
      }
      const next = { ...picks };
      staleKeys.forEach((key) => delete next[Number(key)]);
      this.draft.tradicaoPerdidaClassIds.set(next);
    });

    // Clear the picked bonus power if it stops being a valid option — e.g.
    // the player goes back and picks it from the origin/god screens
    // instead, after already having it selected here.
    effect(() => {
      const powerId = this.draft.generalComplicationPowerId();
      if (powerId === null) {
        return;
      }
      const origin = this.staticRegistry.origins.find((o) => o.id === this.draft.originId());
      const originChoices = this.draft.originChoices();
      const grantedByOrigin = (origin?.grants ?? []).some((group, gi) =>
        (originChoices[gi] ?? []).some((optionIndex) => group.options[optionIndex]?.power_id === powerId),
      );
      const grantedByGod = this.draft.godPowerIds().includes(powerId);
      if (grantedByOrigin || grantedByGod) {
        this.draft.generalComplicationPowerId.set(null);
      }
    });
  }

  // Shared by every power-picking dropdown on this screen (the two general-
  // power sections below, and each level-up row's own dropdown) — checks
  // every prerequisite entry EXCEPT 'class'/'race', which are the type-
  // membership gate each dropdown already applies before ever calling this
  // (see resolveAvailablePowers' matchesSource). 'attribute' resolves against
  // the draft's finalBaseStr/etc (raw point-buy + Aumentar Atributo's own
  // permanent mod_base_str, see character-draft.ts) — never
  // calculateStatBonus, which also sums live buffs (mod_str etc.) that
  // must not let a character qualify for a prerequisite they haven't
  // actually permanently earned.
  private readonly waivedPrerequisitePowerIds = computed(() => resolveWaivedPrerequisitePowerIds(this.draft.grantedPowerIds(), this.staticRegistry.powers));
  private readonly draftActiveEffects = computed(() => getActiveEffects(this.draft));
  private readonly draftTrainedSkillIds = computed(() => resolveTrainedSkillIds(this.draft.baseTrainedSkillIds(), this.draftActiveEffects()));

  private checkPrerequisites(power: Power, characterLevel: number): boolean {
    const granted = this.draft.grantedPowerIds();
    if (this.waivedPrerequisitePowerIds().has(power.id)) {
      return true;
    }
    const trainedSkillIds = this.draftTrainedSkillIds();
    const trainedWithoutThisPower = () =>
      resolveTrainedSkillIds(
        this.draft.baseTrainedSkillIds(),
        this.draftActiveEffects().filter((effect) => effect.source_power_id !== power.id),
      );
    return (power.prerequisites ?? []).every((prerequisite: Prerequisite) => {
      switch (prerequisite.type) {
        case 'attribute': {
          // finalBaseStr/etc (raw point-buy + Aumentar Atributo's own
          // permanent mod_base_str) — a mid-wizard Aumentar Atributo pick
          // correctly counts here, since it's baked in via that computed
          // signal, not resolved as a live buff.
          const baseValues: Record<string, number> = {
            str: this.draft.base_str,
            dex: this.draft.base_dex,
            con: this.draft.base_con,
            int: this.draft.base_int,
            knw: this.draft.base_knw,
            car: this.draft.base_car,
          };
          return prerequisite.attribute !== undefined && (baseValues[prerequisite.attribute] ?? 0) >= (prerequisite.min ?? 0);
        }
        case 'character_level':
          return characterLevel >= (prerequisite.min ?? 0);
        case 'power':
          return (
            (prerequisite.power_id !== undefined && granted.has(prerequisite.power_id)) ||
            (prerequisite.power_ids_any !== undefined && prerequisite.power_ids_any.some((id) => granted.has(id)))
          );
        case 'available_spell_circle':
          return calculateMaxCasterCircle(granted, (classId) => this.draft.orderedClassIds().filter((id) => id === classId).length, this.staticRegistry.powers) >= (prerequisite.min ?? 0);
        case 'skill_trained':
          return prerequisite.skill_id !== undefined && trainedSkillIds.has(prerequisite.skill_id);
        case 'skill_not_trained':
          return prerequisite.skill_id !== undefined && !trainedWithoutThisPower().has(prerequisite.skill_id);
        default:
          // class/race are gated by typeMatches at the call site before this
          // ever runs; god/power_type fall through to true here, unchecked.
          return true;
      }
    });
  }

  // Bonus Poder Geral list (Complicação) — moved here from step 7 so every
  // power pick lives on one screen. Every 'general' (or eligible
  // race_optional) power minus whatever's already on the draft from any
  // source (draft.grantedPowerIds), minus anything whose prerequisites the
  // draft doesn't meet, except this dropdown's own current pick, which has
  // to stay in its own list or the dropdown would show a blank label for a
  // value it can't find.
  protected readonly generalPowerItems = computed(() =>
    resolveAvailablePowers({
      powers: this.staticRegistry.powers,
      granted: this.draft.grantedPowerIds(),
      ownPickId: this.draft.generalComplicationPowerId(),
      matchesSource: (p) => matchesGeneralPower(p, this.draftRaceId()),
      checkPrerequisites: (p) => this.checkPrerequisites(p, this.draft.totalLevel()),
    }),
  );

  // Same idea as generalPowerItems, but for Adulto's own mandatory pick —
  // a separate dropdown/draft field, so it needs its own "own pick" carve-
  // out (a general-complication power and Adulto's power could both be in
  // play on the same draft at once).
  protected readonly adultoPowerItems = computed(() =>
    resolveAvailablePowers({
      powers: this.staticRegistry.powers,
      granted: this.draft.grantedPowerIds(),
      ownPickId: this.draft.adultoPowerId(),
      matchesSource: (p) => matchesGeneralPower(p, this.draftRaceId()),
      checkPrerequisites: (p) => this.checkPrerequisites(p, this.draft.totalLevel()),
    }),
  );

  // Meio-Elfo's Ambição Herdada — "um poder geral ou poder único de origem
  // a sua escolha," so unlike every other dropdown here, both 'general'
  // (or eligible race_optional) and 'origin_granted' sources are offered.
  protected readonly ambicaoHerdadaPowerItems = computed(() =>
    resolveAvailablePowers({
      powers: this.staticRegistry.powers,
      granted: this.draft.grantedPowerIds(),
      ownPickId: this.draft.ambicaoHerdadaPowerId(),
      matchesSource: (p) => matchesGeneralPower(p, this.draftRaceId(), ['origin_granted']),
      checkPrerequisites: (p) => this.checkPrerequisites(p, this.draft.totalLevel()),
    }),
  );

  // Memória Póstuma's own "1 Poder Geral" branch — same shape as Adulto's
  // own dropdown, just a separate field since it's triggered by a
  // different draft choice (memoriaPostumaChoice).
  protected readonly memoriaPostumaPowerItems = computed(() =>
    resolveAvailablePowers({
      powers: this.staticRegistry.powers,
      granted: this.draft.grantedPowerIds(),
      ownPickId: this.draft.memoriaPostumaPowerId(),
      matchesSource: (p) => matchesGeneralPower(p, this.draftRaceId()),
      checkPrerequisites: (p) => this.checkPrerequisites(p, this.draft.totalLevel()),
    }),
  );

  // One dropdown per granted power carrying a choice_bonus_to_general_powers
  // or choice_bonus_to_tormenta_power effect (e.g. Plurivalente, Versátil's
  // Perícia e Poder Geral, Deformidade's Perícia e Poder da Tormenta), labeled
  // with its pool and source — same shape as generalPowerItems/
  // adultoPowerItems, just data-driven off the tag instead of a hardcoded
  // race/bracket check. The tag decides the pool: general (plus eligible
  // race_optional) or tormenta. Labeled "<pool> (<source>)" — the source is
  // the holder that offered this option (Versátil), or the power itself.
  protected readonly bonusPowerChoiceRows = computed(() => {
    const granted = this.draft.grantedPowerIds();
    const choices = this.draft.bonusPowerChoiceIds();
    return this.staticRegistry.powers
      .filter((power) => granted.has(power.id))
      .flatMap((power) => {
        const effects = power.effects ?? [];
        const isTormenta = effects.some((effect) => effect.tag === 'choice_bonus_to_tormenta_power' && effect.op === 'grant');
        const isGeneral = effects.some((effect) => effect.tag === 'choice_bonus_to_general_powers' && effect.op === 'grant');
        if (!isTormenta && !isGeneral) {
          return [];
        }
        const pickId = choices[power.id] ?? null;
        const holder = this.staticRegistry.powers.find(
          (candidate) => granted.has(candidate.id) && (candidate.effects ?? []).some((effect) => effect.tag === 'choice_power' && effect.power_id === power.id),
        );
        return [
          {
            power,
            pickId,
            label: `${isTormenta ? 'Poder da Tormenta' : 'Poder Geral'} (${holder?.name ?? power.name})`,
            items: resolveAvailablePowers({
              powers: this.staticRegistry.powers,
              granted,
              ownPickId: pickId,
              matchesSource: (p) => (isTormenta ? p.source === 'tormenta' : matchesGeneralPower(p, this.draftRaceId())),
              checkPrerequisites: (p) => this.checkPrerequisites(p, this.draft.totalLevel()),
            }),
          },
        ];
      });
  });

  protected setBonusPowerChoiceId(grantingPowerId: number, value: number | string | null): void {
    this.draft.bonusPowerChoiceIds.set({ ...this.draft.bonusPowerChoiceIds(), [grantingPowerId]: (value as number | null) ?? null });
  }

  protected get draftGeneralComplicationId() {
    return this.draft.generalComplicationId;
  }

  protected get draftGeneralComplicationPowerId() {
    return this.draft.generalComplicationPowerId;
  }

  protected get draftAgeBracket() {
    return this.draft.ageBracket;
  }

  protected get draftAdultoPowerId() {
    return this.draft.adultoPowerId;
  }

  protected get draftRaceId() {
    return this.draft.raceId;
  }

  protected get draftAmbicaoHerdadaPowerId() {
    return this.draft.ambicaoHerdadaPowerId;
  }

  protected get draftMemoriaPostumaChoice() {
    return this.draft.memoriaPostumaChoice;
  }

  protected get draftMemoriaPostumaPowerId() {
    return this.draft.memoriaPostumaPowerId;
  }

  // Every level that offers a class-power choice: class-relative level 2
  // onwards only — a class's own first level never offers one, it only
  // bakes in whatever's class_granted (e.g. Ataque Especial's tiers are
  // all automatic, never picked here). class-relative, not character-
  // relative, level is what gates this — the class's own 2nd level still
  // grants a pick even when reached via multiclassing partway through the
  // character's career.
  protected readonly levelPowerRows = computed<LevelPowerRow[]>(() => {
    const orderedClassIds = this.draft.orderedClassIds();
    const classLevelCounts = new Map<number, number>();
    const rows: LevelPowerRow[] = [];

    orderedClassIds.forEach((classId, index) => {
      if (classId === null) {
        return;
      }
      const classLevel = (classLevelCounts.get(classId) ?? 0) + 1;
      classLevelCounts.set(classId, classLevel);
      if (classLevel < 2) {
        return;
      }
      const className = this.staticRegistry.classes.find((c) => c.id === classId)?.name ?? '';
      rows.push({ index, characterLevel: index + 1, classId, className, classLevel });
    });

    return rows;
  });

  protected levelLabel(row: LevelPowerRow): string {
    return `Nível ${row.characterLevel} - ${row.className} ${row.classLevel}`;
  }

  // Every power choosable at THIS row's level-up: 'class' powers whose
  // prerequisites name this row's class (not 'class_granted', which is
  // auto-only and never shown here), and 'general'/'tormenta'/'group' powers
  // (no type-restriction) — minus whatever's already on the draft from any
  // source (draft.grantedPowerIds — origin/god/complication/age-bracket/
  // race/starting-class proficiencies/other level-up picks alike), except this
  // row's own current pick (has to stay in its own list or the dropdown
  // would show a blank label for a value it can't find) and except any
  // repeatablePowerIds entry, which stays pickable everywhere regardless
  // of already being granted elsewhere. A caster's own path power
  // (Bruxo/Feiticeiro/Mago) is excluded too — see available-power-picks-
  // solver.ts's isHiddenFromDropdowns.
  //
  // Matching the source is only the first gate — every OTHER prerequisite
  // entry on the power (character_level, power chains) still has to be
  // satisfied at this specific row too, using that row's own
  // characterLevel (not draft.totalLevel() — an earlier row's level is
  // lower than the character's eventual total, e.g. Nível 2's dropdown
  // must only offer patamar-Iniciante tiers, not every tier up to
  // whatever level the character ends up at).
  protected readonly levelRowItems = computed(() => {
    const granted = this.draft.grantedPowerIds();
    const classPowerIds = this.draft.classPowerIds();
    const itemsByRowIndex = new Map<number, Power[]>();
    for (const row of this.levelPowerRows()) {
      itemsByRowIndex.set(
        row.index,
        resolveAvailablePowers({
          powers: this.staticRegistry.powers,
          granted,
          ownPickId: classPowerIds[row.index] ?? null,
          repeatableIds: REPEATABLE_POWER_IDS,
          matchesSource: (power) => matchesClassPower(power, row.classId, row.classLevel, this.draftRaceId()),
          checkPrerequisites: (power) => this.checkPrerequisites(power, row.characterLevel),
        }),
      );
    }
    return itemsByRowIndex;
  });

  protected readonly tradicaoPerdidaRows = computed(() => {
    const picks = this.draft.tradicaoPerdidaClassIds();
    const ownedClassIds = this.draft.orderedClassIds().filter((id): id is number => id !== null);
    const items = resolveTradicaoPerdidaClassOptions(this.staticRegistry.classes, this.staticRegistry.powers, ownedClassIds);
    return this.staticRegistry.powers
      .filter((power) => this.draft.grantedPowerIds().has(power.id) && isTradicaoPerdidaPower(power))
      .map((power) => ({ power, pick: picks[power.id] ?? null, items }));
  });

  protected setTradicaoPerdidaClassId(powerId: number, value: number | string | null): void {
    this.draft.tradicaoPerdidaClassIds.set({ ...this.draft.tradicaoPerdidaClassIds(), [powerId]: (value as number | null) ?? null });
  }

  protected classPowerIdAt(index: number): number | null {
    return this.draft.classPowerIds()[index] ?? null;
  }

  protected setClassPowerIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.classPowerIds()];
    current[index] = (value as number | null) ?? null;
    this.draft.classPowerIds.set(current);
  }

  // Same two gates the dropdowns above enforce — only required when the
  // thing granting them is actually in play (a real complication picked,
  // or Adulto as the age bracket) — plus every
  // level-up row's own class-power dropdown, which has no such "only when
  // in play" carve-out: every row in levelPowerRows offers a real choice,
  // so every one of them needs a pick before saving.
  protected readonly canContinue = computed(() => {
    const generalComplicationSatisfied = this.draft.generalComplicationId() === null || this.draft.generalComplicationPowerId() !== null;
    const adultoSatisfied = this.draft.ageBracket() !== 'adulto' || this.draft.adultoPowerId() !== null;
    const ambicaoHerdadaSatisfied = this.draft.raceId() !== 22 || this.draft.ambicaoHerdadaPowerId() !== null;
    const memoriaPostumaSatisfied = this.draft.memoriaPostumaChoice() !== 'general_power' || this.draft.memoriaPostumaPowerId() !== null;
    const bonusPowerChoicesSatisfied = this.bonusPowerChoiceRows().every((row) => row.pickId !== null);
    const classPowerIds = this.draft.classPowerIds();
    const levelPowersSatisfied = this.levelPowerRows().every((row) => classPowerIds[row.index] !== null);
    const tradicaoPerdidaSatisfied = this.tradicaoPerdidaRows().every((row) => row.pick !== null);
    return generalComplicationSatisfied && adultoSatisfied && ambicaoHerdadaSatisfied && memoriaPostumaSatisfied && bonusPowerChoicesSatisfied && levelPowersSatisfied && tradicaoPerdidaSatisfied;
  });

  back(): void {
    this.router.navigate(['/character-creation-items-step']);
  }

  continue(): void {
    if (this.needsSpellsStep()) {
      this.router.navigate(['/character-creation-spells-step']);
      return;
    }
    this.saving.save();
  }
}
