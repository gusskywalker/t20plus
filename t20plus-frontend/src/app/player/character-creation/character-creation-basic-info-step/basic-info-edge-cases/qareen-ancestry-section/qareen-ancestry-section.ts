import { Component, computed, inject, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

// Qareen's Resistência Elemental — one fixed pick among 6 'specific'
// powers (RaceGrantedPowerSeeder.php ids 16049-16054), each a flat
// damage_reduction to one element tied to an ancestral element. Hardcoded
// here since source: 'specific' is deliberately invisible to every
// generic power-picker dropdown (resolveAvailablePowers/matchesGeneralPower).
const QAREEN_ANCESTRY_POWER_IDS = [16049, 16050, 16051, 16052, 16053, 16054];

@Component({
  selector: 'app-qareen-ancestry-section',
  imports: [SearchableDropdown],
  templateUrl: './qareen-ancestry-section.html',
  styleUrl: './qareen-ancestry-section.scss',
})
export class QareenAncestrySection {
  private staticRegistry = inject(StaticRegistry);

  powerId = model<number | null>(null);

  protected readonly ancestryPowers = computed(() =>
    QAREEN_ANCESTRY_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );
}
