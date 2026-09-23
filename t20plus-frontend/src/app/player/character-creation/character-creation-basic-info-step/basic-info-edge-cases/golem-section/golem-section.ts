import { Component, computed, effect, inject, model } from '@angular/core';
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

// Golem's Tamanho — one fixed pick among 3 'specific' powers (ids 16243
// Pequeno, 16244 Médio, 16245 Grande), same reasoning as
// GOLEM_CHASSI_POWER_IDS.
const GOLEM_SIZE_POWER_IDS: number[] = [16243, 16244, 16245];
const GOLEM_SIZE_MEDIO_POWER_ID = 16244;

// Chassi de Carne is immune to água/fogo damage — its own Fonte de Energia
// can't be an elemental Água/Fogo or Vapor source, since those would
// contradict that immunity.
const GOLEM_CHASSI_CARNE_POWER_ID = 16218;
const GOLEM_CHASSI_CARNE_BLOCKED_FONTE_POWER_IDS = [16236, 16238, 16241];

// Chassi de Gelo Eterno is immune to frio and vulnerable to fogo — its own
// Fonte de Energia can't be an elemental Fogo or Vapor source, same
// reasoning as Chassi de Carne above.
const GOLEM_CHASSI_GELO_ETERNO_POWER_ID = 16227;
const GOLEM_CHASSI_GELO_ETERNO_BLOCKED_FONTE_POWER_IDS = [16238, 16241];

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
  sizePowerId = model<number | null>(null);

  protected readonly chassiPowers = computed(() =>
    GOLEM_CHASSI_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );

  // Chassi de Carne/Gelo Eterno each block a subset of Fonte de Energia
  // options — everyone else gets the full list.
  private readonly blockedFontePowerIds = computed<number[]>(() => {
    const chassiId = this.chassiPowerId();
    if (chassiId === GOLEM_CHASSI_CARNE_POWER_ID) {
      return GOLEM_CHASSI_CARNE_BLOCKED_FONTE_POWER_IDS;
    }
    if (chassiId === GOLEM_CHASSI_GELO_ETERNO_POWER_ID) {
      return GOLEM_CHASSI_GELO_ETERNO_BLOCKED_FONTE_POWER_IDS;
    }
    return [];
  });

  protected readonly fonteEnergiaPowers = computed(() => {
    const blocked = this.blockedFontePowerIds();
    return GOLEM_FONTE_ENERGIA_POWER_IDS.filter((id) => !blocked.includes(id))
      .map((id) => this.staticRegistry.powers.find((power) => power.id === id))
      .filter((power) => power !== undefined);
  });

  // Mashin can't choose Tamanho — Médio is the only option while its
  // Chassi is picked.
  protected readonly sizePowers = computed(() => {
    const ids = this.chassiPowerId() === GOLEM_MASHIN_CHASSI_POWER_ID ? [GOLEM_SIZE_MEDIO_POWER_ID] : GOLEM_SIZE_POWER_IDS;
    return ids.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined);
  });

  constructor() {
    // Drops a Fonte de Energia pick that becomes blocked the moment Chassi
    // de Carne/Gelo Eterno is (re-)picked.
    effect(() => {
      const validIds = new Set(this.fonteEnergiaPowers().map((power) => power.id));
      const current = this.fonteEnergiaPowerId();
      if (current !== null && !validIds.has(current)) {
        this.fonteEnergiaPowerId.set(null);
      }
    });

    // Forces Médio the moment Chassi Mashin is picked.
    effect(() => {
      if (this.chassiPowerId() === GOLEM_MASHIN_CHASSI_POWER_ID && this.sizePowerId() !== GOLEM_SIZE_MEDIO_POWER_ID) {
        this.sizePowerId.set(GOLEM_SIZE_MEDIO_POWER_ID);
      }
    });
  }
}
