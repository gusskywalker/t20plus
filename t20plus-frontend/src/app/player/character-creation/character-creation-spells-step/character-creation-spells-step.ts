import { Component, ViewChild, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { CharacterCreationSaving } from '../character-creation-saving/character-creation-saving';
import { resolveCasterSpellSlots } from '../resolve-caster-spell-slots';
import { SpellSlot } from '../../../shared/helpers/calculators/calculate-spell-slot-circle-caps/calculate-spell-slot-circle-caps';
import { spellSlotLabel } from '../../../shared/helpers/spell-slot-label/spell-slot-label';
import { resolveSlotSpellOptions } from '../../../shared/helpers/resolve-available-spell-options/resolve-available-spell-options';
import { TatuagemMisticaSection } from './spells-step-edge-cases/tatuagem-mistica-section/tatuagem-mistica-section';
import { CancaoDosMaresSection } from './spells-step-edge-cases/cancao-dos-mares-section/cancao-dos-mares-section';
import { MagiaDasFadasSection } from './spells-step-edge-cases/magia-das-fadas-section/magia-das-fadas-section';
import { TATUAGEM_MISTICA_POWER_ID, CANCAO_DOS_MARES_POWER_ID, MAGIA_DAS_FADAS_POWER_ID } from '../../../shared/helpers/power-pick-constants/power-pick-constants';
import { limitedSpellChoiceLabel, limitedSpellPool, resolveLimitedSpellChoicePowers } from '../../../shared/helpers/resolve-limited-spell-choice-powers/resolve-limited-spell-choice-powers';

@Component({
  selector: 'app-character-creation-spells-step',
  imports: [CardHeader, SearchableDropdown, CharacterCreationSaving, TatuagemMisticaSection, CancaoDosMaresSection, MagiaDasFadasSection],
  templateUrl: './character-creation-spells-step.html',
  styleUrl: './character-creation-spells-step.scss',
})
export class CharacterCreationSpellsStep {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  @ViewChild(CharacterCreationSaving) private saving!: CharacterCreationSaving;

  protected readonly slots = computed(() => resolveCasterSpellSlots(this.draft, this.staticRegistry.powers));

  // Same "power id present, regardless of which source granted it" check
  // used everywhere else (e.g. Osteon's Trocar Raça Base can hand either of
  // these to a non-Qareen/non-Sereia character) — never a race check.
  protected readonly hasTatuagemMistica = computed(() => this.draft.grantedPowerIds().has(TATUAGEM_MISTICA_POWER_ID));
  protected readonly hasCancaoDosMares = computed(() => this.draft.grantedPowerIds().has(CANCAO_DOS_MARES_POWER_ID));
  protected readonly hasMagiaDasFadas = computed(() => this.draft.grantedPowerIds().has(MAGIA_DAS_FADAS_POWER_ID));

  // One dropdown per chosen-spell slot of every granted power carrying a
  // limit_spell_choices effect (e.g. Sapiência), labeled with that power's
  // name — the data-driven counterpart of the three hardcoded sections
  // above. A slot's own pick stays in its list, and a spell picked in a
  // sibling slot of the same power is hidden.
  private readonly tatuagemMisticaPicks = computed(() => [this.draft.tatuagemMisticaSpellId()].filter((id): id is number => id !== null));
  private readonly cancaoDosMaresPicks = computed(() => this.draft.cancaoDosMaresSpellIds().filter((id): id is number => id !== null));
  private readonly magiaDasFadasPicks = computed(() => this.draft.magiaDasFadasSpellIds().filter((id): id is number => id !== null));
  private readonly limitedSpellChoicePicksByPower = computed(() =>
    Object.entries(this.draft.limitedSpellChoiceIds()).map(([powerId, ids]) => ({ powerId: Number(powerId), ids: ids.filter((id): id is number => id !== null) })),
  );

  protected readonly excludedForTatuagemMistica = computed<ReadonlySet<number>>(
    () => new Set([...this.cancaoDosMaresPicks(), ...this.magiaDasFadasPicks(), ...this.limitedSpellChoicePicksByPower().flatMap((entry) => entry.ids)]),
  );
  protected readonly excludedForCancaoDosMares = computed<ReadonlySet<number>>(
    () => new Set([...this.tatuagemMisticaPicks(), ...this.magiaDasFadasPicks(), ...this.limitedSpellChoicePicksByPower().flatMap((entry) => entry.ids)]),
  );
  protected readonly excludedForMagiaDasFadas = computed<ReadonlySet<number>>(
    () => new Set([...this.tatuagemMisticaPicks(), ...this.cancaoDosMaresPicks(), ...this.limitedSpellChoicePicksByPower().flatMap((entry) => entry.ids)]),
  );

  protected readonly limitedSpellChoiceRows = computed(() => {
    const choices = this.draft.limitedSpellChoiceIds();
    const otherSourcePicks = new Set([...this.tatuagemMisticaPicks(), ...this.cancaoDosMaresPicks(), ...this.magiaDasFadasPicks()]);
    const picksByPower = this.limitedSpellChoicePicksByPower();
    return resolveLimitedSpellChoicePowers(this.draft.grantedPowerIds(), this.staticRegistry.powers).map((entry) => {
      const picks = Array.from({ length: entry.slotCount }, (_, i) => choices[entry.power.id]?.[i] ?? null);
      const otherPowersPicks = new Set(picksByPower.filter((other) => other.powerId !== entry.power.id).flatMap((other) => other.ids));
      const pool = limitedSpellPool(this.staticRegistry.spells, entry.circle, entry.school, entry.maxCircle).filter(
        (spell) => !otherSourcePicks.has(spell.id) && !otherPowersPicks.has(spell.id),
      );
      return {
        power: entry.power,
        label: limitedSpellChoiceLabel(entry),
        slots: picks.map((pick, index) => ({
          index,
          pick,
          items: pool.filter((spell) => spell.id === pick || !picks.some((other, otherIndex) => otherIndex !== index && other === spell.id)),
        })),
      };
    });
  });

  protected setLimitedSpellChoice(powerId: number, index: number, value: number | string | null): void {
    const current = this.draft.limitedSpellChoiceIds();
    const slotCount = this.limitedSpellChoiceRows().find((row) => row.power.id === powerId)?.slots.length ?? index + 1;
    const picks = Array.from({ length: slotCount }, (_, i) => current[powerId]?.[i] ?? null);
    picks[index] = (value as number | null) ?? null;
    this.draft.limitedSpellChoiceIds.set({ ...current, [powerId]: picks });
  }

  protected get draftTatuagemMisticaSpellId() {
    return this.draft.tatuagemMisticaSpellId;
  }

  protected get draftCancaoDosMaresSpellIds() {
    return this.draft.cancaoDosMaresSpellIds;
  }

  protected get draftMagiaDasFadasSpellIds() {
    return this.draft.magiaDasFadasSpellIds;
  }

  constructor() {
    // Keep chosenSpellIds sized to match slots — a class/level/Caminho
    // change earlier in the wizard can grow or shrink the slot count, and
    // a stale longer/shorter array would misalign index-to-slot. Existing
    // picks at still-valid indices are preserved.
    effect(() => {
      const slotCount = this.slots().length;
      const current = this.draft.chosenSpellIds();
      if (current.length === slotCount) {
        return;
      }
      const next = Array.from({ length: slotCount }, (_, i) => current[i] ?? null);
      this.draft.chosenSpellIds.set(next);
    });

    // Clear a stale pick once its granting power is gone (e.g. the player
    // goes back and changes race, or swaps Trocar Raça Base's own choice) —
    // same reasoning as basic-info-step's own isQareen-driven clearing.
    effect(() => {
      if (!this.hasTatuagemMistica()) {
        this.draft.tatuagemMisticaSpellId.set(null);
      }
    });
    effect(() => {
      if (!this.hasCancaoDosMares()) {
        this.draft.cancaoDosMaresSpellIds.set([null, null]);
      }
    });
    effect(() => {
      if (!this.hasMagiaDasFadas()) {
        this.draft.magiaDasFadasSpellIds.set([null, null]);
      }
    });
    effect(() => {
      const grantingIds = new Set(this.limitedSpellChoiceRows().map((row) => row.power.id));
      const choices = this.draft.limitedSpellChoiceIds();
      const staleKeys = Object.keys(choices).filter((key) => !grantingIds.has(Number(key)));
      if (staleKeys.length === 0) {
        return;
      }
      const next = { ...choices };
      staleKeys.forEach((key) => delete next[Number(key)]);
      this.draft.limitedSpellChoiceIds.set(next);
    });
  }

  protected slotLabel(slot: SpellSlot): string {
    return spellSlotLabel(slot, this.staticRegistry.classes.find((characterClass) => characterClass.id === slot.classId)?.name ?? '');
  }

  // Every arcana spell at or below this slot's cap, minus whatever's
  // already picked in a DIFFERENT slot (a character shouldn't know the
  // same spell twice) — except this slot's own current pick, which has to
  // stay in its own list or the dropdown would show a blank label for a
  // value it can't find, same carve-out convention as every other
  // "already granted elsewhere" dropdown in this wizard.
  protected optionsForSlot(index: number): { id: number; name: string }[] {
    const slot = this.slots()[index];
    const ownPick = this.draft.chosenSpellIds()[index] ?? null;
    const chosenElsewhere = new Set(this.draft.chosenSpellIds().filter((id, i) => id !== null && i !== index));
    const options = resolveSlotSpellOptions({
      spells: this.staticRegistry.spells,
      slot,
      granted: this.draft.grantedPowerIds(),
      powers: this.staticRegistry.powers,
    });
    return options.filter((spell) => !chosenElsewhere.has(spell.id) || spell.id === ownPick);
  }

  protected chosenSpellIdAt(index: number): number | null {
    return this.draft.chosenSpellIds()[index] ?? null;
  }

  protected setChosenSpellIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.chosenSpellIds()];
    current[index] = (value as number | null) ?? null;
    this.draft.chosenSpellIds.set(current);
  }

  protected readonly canContinue = computed(() => {
    const chosen = this.draft.chosenSpellIds();
    const slotsSatisfied = this.slots().every((_, i) => chosen[i] !== null && chosen[i] !== undefined);
    const tatuagemMisticaSatisfied = !this.hasTatuagemMistica() || this.draft.tatuagemMisticaSpellId() !== null;
    const cancaoDosMaresIds = this.draft.cancaoDosMaresSpellIds();
    const cancaoDosMaresSatisfied = !this.hasCancaoDosMares() || (cancaoDosMaresIds[0] !== null && cancaoDosMaresIds[1] !== null);
    const magiaDasFadasIds = this.draft.magiaDasFadasSpellIds();
    const magiaDasFadasSatisfied = !this.hasMagiaDasFadas() || (magiaDasFadasIds[0] !== null && magiaDasFadasIds[1] !== null);
    const limitedSpellChoicesSatisfied = this.limitedSpellChoiceRows().every((row) => row.slots.every((slot) => slot.pick !== null));
    return slotsSatisfied && tatuagemMisticaSatisfied && cancaoDosMaresSatisfied && magiaDasFadasSatisfied && limitedSpellChoicesSatisfied;
  });

  back(): void {
    this.router.navigate(['/character-creation-powers-step']);
  }

  continue(): void {
    this.saving.save();
  }
}
