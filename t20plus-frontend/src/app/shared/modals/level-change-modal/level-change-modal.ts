import { Component, computed, effect, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Power, Prerequisite } from '../../../api.service';
import { resolveGrantedPowerIds } from '../../helpers/resolve-granted-power-ids/resolve-granted-power-ids';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';
import { ClassPickRow } from '../../class-pick-row/class-pick-row';
import { PowerPickRow } from '../../power-pick-row/power-pick-row';
import { REPEATABLE_POWER_IDS } from '../../helpers/power-pick-constants/power-pick-constants';
import { matchesClassPower, resolveAvailablePowers } from '../../helpers/available-power-picks-solver/available-power-picks-solver';
import { calculateMaxCasterCircle } from '../../helpers/calculators/calculate-max-caster-circle/calculate-max-caster-circle';
import { resolveNewSpellSlotsAtLevel } from '../../helpers/resolve-new-spell-slots-at-level/resolve-new-spell-slots-at-level';
import { resolveAvailableSpellOptions } from '../../helpers/resolve-available-spell-options/resolve-available-spell-options';
import { FEITICEIRO_POWER_ID } from '../../arcanista-path-section/arcanista-path-section';
import { grantChildPowers } from '../../helpers/grant-child-powers/grant-child-powers';
import { resolveWaivedPrerequisitePowerIds } from '../../helpers/resolve-waived-prerequisite-power-ids/resolve-waived-prerequisite-power-ids';

const ARCANISTA_CLASS_ID = 3;

@Component({
  selector: 'app-level-change-modal',
  imports: [SearchableDropdown, ClassPickRow, PowerPickRow],
  templateUrl: './level-change-modal.html',
  styleUrl: './level-change-modal.scss',
})
export class LevelChangeModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  cancel = output<void>();

  // Explicit screen the modal is on, same convention as
  // golpe-pessoal-modal's currentPage. 1: Subir Nível/Reduzir Nível
  // choice. 2: pick the class the new level belongs to (TODO — Selecionar
  // not wired to a save yet).
  protected readonly currentPage = signal(1);

  protected goBack(): void {
    this.currentPage.set(1);
    this.selectedClassId.set(null);
    this.selectedPowerId.set(null);
    this.arcanistaPathPowerId.set(null);
    this.linhagemPowerId.set(null);
    this.chosenSpellIds.set([]);
  }

  protected handleCancel(): void {
    if (this.currentPage() === 2) {
      this.goBack();
      return;
    }
    this.cancel.emit();
  }

  protected subirNivel(): void {
    this.currentPage.set(2);
  }

  // Character's current level + 1 — page 2's dropdown label ("Level 2" for
  // a level-1 character).
  protected nextLevel(): number {
    return this.character().level + 1;
  }

  protected classItems() {
    return this.staticRegistry.classes;
  }

  protected readonly feiticeiroId = FEITICEIRO_POWER_ID;

  protected readonly selectedClassId = signal<number | null>(null);
  protected readonly selectedPowerId = signal<number | null>(null);
  // The Caminho pick, kept separate from selectedPowerId — the two used to
  // share one signal (only one of them is ever visible at once, so it
  // seemed harmless), but that meant picking a normal power immediately
  // tripped ClassPickRow's own "clear a stale Caminho pick" effect, wiping
  // the just-made pick. Same two-field shape character-draft.ts already
  // uses (arcanistaPathPowerId separate from classPowerIds).
  protected readonly arcanistaPathPowerId = signal<number | null>(null);
  // Feiticeiro's own mandatory, permanent Linhagem pick — only meaningful
  // while arcanistaPathPowerId is Feiticeiro's own id, cleared alongside it
  // below and by the constructor's own effect (covers a live checkbox
  // toggle away from Feiticeiro without leaving the class dropdown).
  protected readonly linhagemPowerId = signal<number | null>(null);

  // A power pick is only reset when the class actually changes — picking
  // the same class again keeps whatever was already selected.
  protected setSelectedClassId(value: number | string | null): void {
    this.selectedClassId.set(value as number | null);
    this.selectedPowerId.set(null);
    this.arcanistaPathPowerId.set(null);
    this.linhagemPowerId.set(null);
    this.chosenSpellIds.set([]);
  }

  // This new level's class-relative level — existing character_levels rows
  // for the picked class, plus one for the level being added now. A class
  // never picked before comes out to 1 (a fresh multiclass), same as
  // picking it for the very first time normally would.
  protected newClassLevel(): number {
    const classId = this.selectedClassId();
    if (classId === null) {
      return 0;
    }
    return (this.character().levels ?? []).filter((level) => level.class_id === classId).length + 1;
  }

  // A class's own first level never offers a power pick (baseline features
  // only) — same rule as character-creation-powers-step's levelPowerRows.
  protected offersPowerPick(): boolean {
    return this.newClassLevel() >= 2;
  }

  // Arcanista's own first level is the one exception — its mandatory
  // Caminho pick (Bruxo/Feiticeiro/Mago) shows in place of the normal
  // power dropdown, but still writes into the same selectedPowerId.
  protected isArcanistaFirstLevel(): boolean {
    return this.selectedClassId() === ARCANISTA_CLASS_ID && this.newClassLevel() === 1;
  }

  // Level-up counterpart to character-creation-spells-step's own spell slots —
  // empty for a non-caster class or a level that doesn't grant a new known
  // spell (e.g. Arcanista's own even-numbered growth levels). Depends on
  // arcanistaPathPowerId so a fresh Arcanista's Caminho pick (which is
  // itself what carries starting_spell_count) reacts the moment it's chosen.
  protected readonly newSpellSlots = computed(() => {
    const classId = this.selectedClassId();
    if (classId === null) {
      return [];
    }
    const candidatePowerId = this.isArcanistaFirstLevel() ? this.arcanistaPathPowerId() : this.selectedPowerId();
    return resolveNewSpellSlotsAtLevel(this.character(), classId, this.newClassLevel(), candidatePowerId, this.staticRegistry.powers);
  });

  protected readonly chosenSpellIds = signal<(number | null)[]>([]);

  constructor() {
    // Same resize-preserving-existing-picks rule as step 10's own effect —
    // keeps chosenSpellIds sized to newSpellSlots() as the class/power pick
    // changes which (if any) slots apply.
    effect(() => {
      const slotCount = this.newSpellSlots().length;
      const current = this.chosenSpellIds();
      if (current.length === slotCount) {
        return;
      }
      this.chosenSpellIds.set(Array.from({ length: slotCount }, (_, i) => current[i] ?? null));
    });

    // Clear a stale Linhagem pick the instant the Caminho checkbox moves
    // away from Feiticeiro (goBack/setSelectedClassId already cover leaving
    // the class dropdown entirely, but not toggling Bruxo/Mago in while
    // staying on the same class) — same reasoning as classes-step's own
    // equivalent effect.
    effect(() => {
      if (this.arcanistaPathPowerId() !== FEITICEIRO_POWER_ID && this.linhagemPowerId() !== null) {
        this.linhagemPowerId.set(null);
      }
    });
  }

  protected spellSlotLabel(cap: number): string {
    return `Magia (${cap}º Círculo)`;
  }

  // Every arcana/universal spell at or below this slot's cap, minus the
  // character's own already-known spells (spell_ids across every level
  // row — can't learn the same spell twice) and whatever's picked in a
  // DIFFERENT slot in this same modal, except this slot's own current
  // pick — same carve-out convention as step 10's optionsForSlot.
  protected spellOptionsForSlot(index: number): { id: number; name: string }[] {
    const slot = this.newSpellSlots()[index];
    const cap = slot?.cap ?? 0;
    const ownPick = this.chosenSpellIds()[index] ?? null;
    const alreadyKnown = new Set((this.character().levels ?? []).flatMap((level) => level.spell_ids ?? []));
    const chosenElsewhere = new Set(this.chosenSpellIds().filter((id, i) => id !== null && i !== index));
    // The character's own already-saved powers PLUS whatever this same
    // level-up is about to grant but hasn't posted yet (e.g. picking
    // Linhagem Abençoada and a 1st-circle divina spell in the same
    // level-up) — character-creation-spells-step gets this for free from
    // the draft's own live grantedPowerIds(); a real Character has no such
    // in-progress view, so it's unioned in by hand here.
    const inProgressPowerIds = [this.arcanistaPathPowerId(), this.linhagemPowerId(), this.selectedPowerId()].filter((id): id is number => id !== null);
    const granted = new Set([...(this.character().active_effects ?? []).map((effect) => effect.power_id), ...inProgressPowerIds]);
    const options = resolveAvailableSpellOptions({
      spells: this.staticRegistry.spells,
      classId: slot?.classId ?? -1,
      cap,
      granted,
      powers: this.staticRegistry.powers,
    });
    return options.filter((spell) => !alreadyKnown.has(spell.id) && (!chosenElsewhere.has(spell.id) || spell.id === ownPick));
  }

  protected chosenSpellIdAt(index: number): number | null {
    return this.chosenSpellIds()[index] ?? null;
  }

  protected setChosenSpellIdAt(index: number, value: number | string | null): void {
    const current = [...this.chosenSpellIds()];
    current[index] = (value as number | null) ?? null;
    this.chosenSpellIds.set(current);
  }

  // Every offered slot must have a pick before Selecionar is enabled — same
  // rule as step 10's canContinue.
  protected readonly spellPicksComplete = computed(() => {
    const chosen = this.chosenSpellIds();
    return this.newSpellSlots().every((_, i) => chosen[i] !== null && chosen[i] !== undefined);
  });

  private checkPrerequisites(power: Power): boolean {
    const character = this.character();
    const granted = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
    if (resolveWaivedPrerequisitePowerIds(granted, this.staticRegistry.powers).has(power.id)) {
      return true;
    }
    return (power.prerequisites ?? []).every((prerequisite: Prerequisite) => {
      switch (prerequisite.type) {
        case 'attribute': {
          // Base score only, never calculateStatBonus — a temporary/active
          // power buff (mod_str etc.) must not let a character qualify for
          // a prerequisite they haven't actually permanently earned.
          const baseValues: Record<string, number> = {
            str: character.base_str,
            dex: character.base_dex,
            con: character.base_con,
            int: character.base_int,
            knw: character.base_knw,
            car: character.base_car,
          };
          return prerequisite.attribute !== undefined && (baseValues[prerequisite.attribute] ?? 0) >= (prerequisite.min ?? 0);
        }
        case 'character_level':
          return this.nextLevel() >= (prerequisite.min ?? 0);
        case 'power':
          return (
            (prerequisite.power_id !== undefined && granted.has(prerequisite.power_id)) ||
            (prerequisite.power_ids_any !== undefined && prerequisite.power_ids_any.some((id) => granted.has(id)))
          );
        case 'available_spell_circle': {
          // Simulates this pending level-up's own class row the same way
          // 'character_level' above uses nextLevel() instead of the
          // character's current level — a class power gated by this
          // prerequisite should already see the class level it's about to
          // gain, not just what's already on character.levels.
          const pendingClassId = this.selectedClassId();
          const classLevelForClassId = (classId: number) => {
            const current = (character.levels ?? []).filter((level) => level.class_id === classId).length;
            return classId === pendingClassId ? current + 1 : current;
          };
          return calculateMaxCasterCircle(granted, classLevelForClassId, this.staticRegistry.powers) >= (prerequisite.min ?? 0);
        }
        default:
          return true;
      }
    });
  }

  // Same shape as character-creation-powers-step's availablePowerItems, simulating
  // one hypothetical new character_levels row (this.selectedClassId(),
  // newClassLevel(), nextLevel()) instead of a real draft row.
  protected availablePowerItems(): Power[] {
    const classId = this.selectedClassId();
    if (classId === null || !this.offersPowerPick()) {
      return [];
    }
    const character = this.character();
    const granted = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
    const openOtherSourceIds = new Set(
      (character.active_effects ?? []).filter((effect) => effect.other_sources_state === 'open').map((effect) => effect.power_id),
    );
    const classLevel = this.newClassLevel();

    return resolveAvailablePowers({
      powers: this.staticRegistry.powers,
      granted,
      ownPickId: this.selectedPowerId(),
      repeatableIds: REPEATABLE_POWER_IDS,
      openOtherSourceIds,
      matchesSource: (power) => matchesClassPower(power, classId, classLevel, character.race_id ?? null),
      checkPrerequisites: (power) => this.checkPrerequisites(power),
    });
  }

  protected selecionarNivel(): void {
    const classId = this.selectedClassId();
    if (classId === null) {
      return;
    }
    const powerId = this.isArcanistaFirstLevel() ? this.arcanistaPathPowerId() : this.offersPowerPick() ? this.selectedPowerId() : null;
    const spellIds = this.chosenSpellIds().filter((id): id is number => id !== null);
    const payload = { class_id: classId, power_id: powerId, ...(spellIds.length > 0 ? { spell_ids: spellIds } : {}) };

    this.apiService.createCharacterLevel(this.character().id, payload).subscribe((character) => {
      // Aumentar Atributo (mod_base_str/etc) is applied server-side now
      // (GrantsPowers trait, same grant path Adicionar Poder uses) — the
      // returned character already carries the incremented base_* column,
      // just cache it along with everything else.
      this.useCharacter.patchCharacterCache(this.id(), {
        level: character.level,
        levels: character.levels,
        active_effects: character.active_effects,
        golpes_pessoais: character.golpes_pessoais,
        base_str: character.base_str,
        base_dex: character.base_dex,
        base_con: character.base_con,
        base_int: character.base_int,
        base_knw: character.base_knw,
        base_car: character.base_car,
      });

      // The picked power can itself grant others (tag: 'power', op:
      // 'grant' — source: 'power_granted' on the granted side, e.g.
      // Espreitar's two children) — add those the same way any normal
      // power gets added, one at a time (not parallel) so each call's own
      // active_effects snapshot already includes the ones added just
      // before it, instead of racing and dropping one from the cache.
      const grantedChildIds =
        powerId === null ? [] : [...resolveGrantedPowerIds([powerId], this.staticRegistry.powers)].filter((id) => id !== powerId);
      // Feiticeiro's own mandatory Linhagem pick rides along the same way —
      // it's not a child grant of the Caminho power itself (no `tag:
      // 'power'` effect on 328/329/330), just a second power this exact
      // level-up also grants, so it's appended into the same sequential
      // grant chain instead of a separate one.
      const linhagemPowerId = powerId === FEITICEIRO_POWER_ID ? this.linhagemPowerId() : null;
      const extraGrantIds = linhagemPowerId === null ? grantedChildIds : [...grantedChildIds, linhagemPowerId];
      grantChildPowers(this.apiService, this.useCharacter, character.id, this.id(), extraGrantIds, () => this.cancel.emit());
    });
  }

  // Same deliberate second-click cooldown as character-main.ts's
  // onRemovePowerClick, own independent state since this is its own
  // component now.
  protected readonly reduzirNivelConfirming = signal(false);
  protected readonly reduzirNivelReady = signal(false);
  private reduzirNivelTimeoutId: ReturnType<typeof setTimeout> | null = null;

  protected reduzirNivel(): void {
    if (!this.reduzirNivelConfirming()) {
      this.reduzirNivelConfirming.set(true);
      this.reduzirNivelTimeoutId = setTimeout(() => this.reduzirNivelReady.set(true), 1000);
      return;
    }
    if (!this.reduzirNivelReady()) {
      return;
    }
    this.apiService.destroyHighestCharacterLevel(this.character().id).subscribe((character) => {
      this.useCharacter.patchCharacterCache(this.id(), {
        level: character.level,
        levels: character.levels,
        active_effects: character.active_effects,
        golpes_pessoais: character.golpes_pessoais,
        base_str: character.base_str,
        base_dex: character.base_dex,
        base_con: character.base_con,
        base_int: character.base_int,
        base_knw: character.base_knw,
        base_car: character.base_car,
      });
      this.cancel.emit();
    });
  }
}
