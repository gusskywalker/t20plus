import { Component, ViewChild, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Power, Prerequisite } from '../../../api.service';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { CharacterCreationSaving } from '../character-creation-saving/character-creation-saving';
import { matchesClassPower, matchesGeneralPower, resolveAvailablePowers } from '../../../shared/helpers/available-power-picks-solver/available-power-picks-solver';
import { resolveCasterSpellSlots } from '../resolve-caster-spell-slots';
import { calculateMaxCasterCircle } from '../../../shared/helpers/calculators/calculate-max-caster-circle/calculate-max-caster-circle';

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
  selector: 'app-character-creation-step-9',
  imports: [CardHeader, SearchableDropdown, CharacterCreationSaving],
  templateUrl: './character-creation-step-9.html',
  styleUrl: './character-creation-step-9.scss',
})
export class CharacterCreationStep9 {
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
  protected readonly hasCasterClass = computed(() => resolveCasterSpellSlots(this.draft, this.staticRegistry.powers).length > 0);

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
  // (see availablePowerItems' typeMatches). 'attribute' resolves against
  // the draft's finalBaseStr/etc (raw point-buy + Aumentar Atributo's own
  // permanent mod_base_str, see character-draft.ts) — never
  // calculateStatBonus, which also sums live buffs (mod_str etc.) that
  // must not let a character qualify for a prerequisite they haven't
  // actually permanently earned.
  private checkPrerequisites(power: Power, characterLevel: number): boolean {
    const granted = this.draft.grantedPowerIds();
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
        default:
          // class/race are gated by typeMatches at the call site before this
          // ever runs; skill_trained/god/power_type fall through to true
          // here, unchecked.
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

  // Golpe Pessoal is the one power the rulebook explicitly lets you pick
  // more than once ("outras vezes para golpes diferentes") — every other
  // power is a one-time fact, hence the granted-exclusion below. Hardcoded
  // exception, same convention as powerPickHints/ataqueEspecialPowerIds.
  private readonly repeatablePowerIds = new Set([115]); // Golpe Pessoal

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
  protected availablePowerItems(row: LevelPowerRow): Power[] {
    return resolveAvailablePowers({
      powers: this.staticRegistry.powers,
      granted: this.draft.grantedPowerIds(),
      ownPickId: this.draft.classPowerIds()[row.index] ?? null,
      repeatableIds: this.repeatablePowerIds,
      matchesSource: (power) => matchesClassPower(power, row.classId, row.classLevel, this.draftRaceId()),
      checkPrerequisites: (power) => this.checkPrerequisites(power, row.characterLevel),
    });
  }

  protected classPowerIdAt(index: number): number | null {
    return this.draft.classPowerIds()[index] ?? null;
  }

  protected setClassPowerIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.classPowerIds()];
    current[index] = (value as number | null) ?? null;
    this.draft.classPowerIds.set(current);
  }

  // Hardcoded per-power hint shown under a level row's dropdown once that
  // power is picked — same hardcode-the-exception convention as
  // ataqueEspecialPowerIds/Golpe Pessoal's own menu ids, just for a UI
  // nudge instead of a mechanic. Golpe Pessoal itself needs no build UI
  // here (that lives on the character sheet, see golpe-pessoal-modal
  // plans) — this just tells the player where to go.
  private readonly powerPickHints: Record<number, string> = {
    115: 'Customize na página do personagem', // Golpe Pessoal
    // Aumentar Atributo (Inteligência)'s 4 patamar tiers — bumping Int
    // grows step 6's bonus skill-pick count (effectiveInt), which the
    // player might not otherwise notice from this screen alone.
    58: 'Selecione mais uma perícia!',
    59: 'Selecione mais uma perícia!',
    60: 'Selecione mais uma perícia!',
    61: 'Selecione mais uma perícia!',
  };

  protected powerPickHint(index: number): string | null {
    const powerId = this.classPowerIdAt(index);
    return powerId !== null ? (this.powerPickHints[powerId] ?? null) : null;
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
    const classPowerIds = this.draft.classPowerIds();
    const levelPowersSatisfied = this.levelPowerRows().every((row) => classPowerIds[row.index] !== null);
    return generalComplicationSatisfied && adultoSatisfied && ambicaoHerdadaSatisfied && levelPowersSatisfied;
  });

  back(): void {
    this.router.navigate(['/character-creation-step-8']);
  }

  continue(): void {
    if (this.hasCasterClass()) {
      this.router.navigate(['/character-creation-step-10']);
      return;
    }
    this.saving.save();
  }
}
