import { Component, computed, inject, signal } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

// Golem's Chassi — one fixed pick among its 'specific' material powers, same
// DUENDE_SIZE_POWER_IDS reasoning (source: 'specific' is deliberately
// invisible to every generic power-picker dropdown). Filled in as each
// material power gets seeded (RaceGrantedPowerSeeder.php id 16216 Barro).
const GOLEM_CHASSI_POWER_IDS: number[] = [16216];

@Component({
  selector: 'app-golem-section',
  imports: [SearchableDropdown],
  templateUrl: './golem-section.html',
  styleUrl: './golem-section.scss',
})
export class GolemSection {
  private staticRegistry = inject(StaticRegistry);

  // Not wired into character-draft.ts yet — no chassi powers exist to pick,
  // so there's nothing to persist. Local-only until that lands.
  protected readonly chassiPowerId = signal<number | null>(null);

  protected readonly chassiPowers = computed(() =>
    GOLEM_CHASSI_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );
}
