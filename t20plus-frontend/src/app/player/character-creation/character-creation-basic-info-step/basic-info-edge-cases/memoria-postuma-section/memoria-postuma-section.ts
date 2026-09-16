import { Component, computed, inject, model } from '@angular/core';
import { Checkbox } from '../../../../../shared/inputs/checkbox/checkbox';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

export type MemoriaPostumaChoice = 'skill' | 'general_power' | 'change_base_race' | null;

// Races an osteon can never "become" via Trocar Raça Base — itself
// (Osteon, 42 — nothing to change to) and Humano (15, the power's own
// "que não humano" exclusion).
const CHANGE_BASE_RACE_EXCLUDED_RACE_IDS = [42, 15];

// Osteon's Memória Póstuma (power id 16041) — three mutually exclusive
// alternatives, same fake-radio-via-checkbox convention as
// ChoosingMechanicSection/ArcanistaPathSection: checking one clears the
// other two. The free skill pick and the bonus general power live in
// character-creation-skills-step/character-creation-powers-step, which
// read this same choice off the draft — Trocar Raça Base's own dropdown
// lives right here instead, same as QareenAncestrySection's own pick.
@Component({
  selector: 'app-memoria-postuma-section',
  imports: [Checkbox, SearchableDropdown],
  templateUrl: './memoria-postuma-section.html',
  styleUrl: './memoria-postuma-section.scss',
})
export class MemoriaPostumaSection {
  private staticRegistry = inject(StaticRegistry);

  choice = model<MemoriaPostumaChoice>(null);
  raceAbilityPowerId = model<number | null>(null);

  protected isSelected(value: MemoriaPostumaChoice): boolean {
    return this.choice() === value;
  }

  protected toggle(value: MemoriaPostumaChoice, checked: boolean): void {
    if (checked) {
      this.choice.set(value);
    } else if (this.choice() === value) {
      this.choice.set(null);
    }
  }

  // Every race_granted power except osteon's own and humano's — the
  // player self-reports whether their chosen race is actually humanoide,
  // same self-report philosophy as everywhere else this app can't verify
  // a rule against tracked state.
  protected readonly raceAbilityPowers = computed(() =>
    this.staticRegistry.powers.filter(
      (power) =>
        power.source === 'race_granted' &&
        !(power.prerequisites ?? []).some(
          (prerequisite) => prerequisite.type === 'race' && (prerequisite.race_ids ?? []).some((id) => CHANGE_BASE_RACE_EXCLUDED_RACE_IDS.includes(id)),
        ),
    ),
  );
}
