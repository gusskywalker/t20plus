import { Component, computed, inject, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';
import { GOLEM_MASHIN_CHASSI_POWER_ID } from '../../../../../shared/helpers/power-pick-constants/power-pick-constants';

// Golem's Chassi — one fixed pick among its 'specific' material powers, same
// DUENDE_SIZE_POWER_IDS reasoning (source: 'specific' is deliberately
// invisible to every generic power-picker dropdown). Filled in as each
// material power gets seeded (RaceGrantedPowerSeeder.php ids 16216 Barro,
// 16217 Bronze, 16218 Carne, 16219 Dourado's own vessel, 16223 Espelhos'
// own vessel, 16226 Ferro, 16227 Gelo Eterno, 16228 Pedra, 16229 Sucata,
// 16230 Mashin — each vessel's own children are power_granted, never
// picked directly).
const GOLEM_CHASSI_POWER_IDS: number[] = [16216, 16217, 16218, 16219, 16223, 16226, 16227, 16228, 16229, GOLEM_MASHIN_CHASSI_POWER_ID];

// Golem's Fonte de Energia — one fixed pick among its 'specific' power
// options, same reasoning as GOLEM_CHASSI_POWER_IDS. Filled in as each
// option gets seeded (RaceGrantedPowerSeeder.php ids 16235 Alquímica,
// 16236-16239 Elemental's own 4 sub-choices, 16240 Sagrada, 16241 Vapor).
const GOLEM_FONTE_ENERGIA_POWER_IDS: number[] = [16235, 16236, 16237, 16238, 16239, 16240, 16241];

@Component({
  selector: 'app-golem-section',
  imports: [SearchableDropdown],
  templateUrl: './golem-section.html',
  styleUrl: './golem-section.scss',
})
export class GolemSection {
  private staticRegistry = inject(StaticRegistry);

  chassiPowerId = model<number | null>(null);
  fonteEnergiaPowerId = model<number | null>(null);

  protected readonly chassiPowers = computed(() =>
    GOLEM_CHASSI_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );

  protected readonly fonteEnergiaPowers = computed(() =>
    GOLEM_FONTE_ENERGIA_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );
}
