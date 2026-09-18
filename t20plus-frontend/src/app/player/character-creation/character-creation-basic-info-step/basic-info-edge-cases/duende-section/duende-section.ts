import { Component, computed, inject, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

// Duende's Natureza — one fixed pick among 3 'specific' powers
// (RaceGrantedPowerSeeder.php ids 16187 Animal, 16188 Vegetal, 16190
// Mineral). Hardcoded here since source: 'specific' is deliberately
// invisible to every generic power-picker dropdown.
const DUENDE_NATURE_POWER_IDS = [16187, 16188, 16190];

@Component({
  selector: 'app-duende-section',
  imports: [SearchableDropdown],
  templateUrl: './duende-section.html',
  styleUrl: './duende-section.scss',
})
export class DuendeSection {
  private staticRegistry = inject(StaticRegistry);

  naturePowerId = model<number | null>(null);

  protected readonly naturePowers = computed(() =>
    DUENDE_NATURE_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );
}
