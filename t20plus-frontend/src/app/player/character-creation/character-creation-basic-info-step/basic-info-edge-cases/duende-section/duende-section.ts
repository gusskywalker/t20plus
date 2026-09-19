import { Component, computed, inject, model } from '@angular/core';
import { Checkbox } from '../../../../../shared/inputs/checkbox/checkbox';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';
import { Power } from '../../../../../api.service';

// Duende's Natureza — one fixed pick among 3 'specific' powers
// (RaceGrantedPowerSeeder.php ids 16187 Animal, 16188 Vegetal, 16190
// Mineral). Hardcoded here since source: 'specific' is deliberately
// invisible to every generic power-picker dropdown.
const DUENDE_NATURE_POWER_IDS = [16187, 16188, 16190];

// Duende's Tamanho — one fixed pick among 4 'specific' powers (ids 16191
// Minúsculo, 16192 Pequeno, 16193 Médio, 16194 Grande), same reasoning.
const DUENDE_SIZE_POWER_IDS = [16191, 16192, 16193, 16194];

// Duende's Tabu — one fixed pick among 4 'specific' powers (ids 16197
// Diplomacia, 16198 Iniciativa, 16199 Luta, 16200 Percepção), same reasoning.
const DUENDE_TABOO_POWER_IDS = [16197, 16198, 16199, 16200];

const DUENDE_RACE_ID = 60;

@Component({
  selector: 'app-duende-section',
  imports: [Checkbox, SearchableDropdown],
  templateUrl: './duende-section.html',
  styleUrl: './duende-section.scss',
})
export class DuendeSection {
  private staticRegistry = inject(StaticRegistry);

  naturePowerId = model<number | null>(null);
  sizePowerId = model<number | null>(null);
  giftPowerIds = model<(number | null)[]>([null, null, null]);
  tabooPowerId = model<number | null>(null);
  randomlyCreated = model<boolean>(false);

  protected readonly naturePowers = computed(() =>
    DUENDE_NATURE_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );

  protected readonly sizePowers = computed(() =>
    DUENDE_SIZE_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );

  protected readonly tabooPowers = computed(() =>
    DUENDE_TABOO_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  );

  // Every Presente is a race_optional power with a Duende prerequisite.
  private readonly giftPowers = computed(() =>
    this.staticRegistry.powers
      .filter(
        (power) =>
          power.source === 'race_optional' &&
          (power.prerequisites ?? []).some((prerequisite) => prerequisite.type === 'race' && (prerequisite.race_ids ?? []).includes(DUENDE_RACE_ID)),
      )
      .sort((a, b) => a.name.localeCompare(b.name, 'pt-BR')),
  );

  // A gift picked in one slot never shows in the other two, but each slot
  // keeps its own current pick in its list.
  private giftItemsExcludingOthers(slot: number): Power[] {
    const picked = this.giftPowerIds().filter((id, index) => index !== slot && id !== null);
    return this.giftPowers().filter((power) => !picked.includes(power.id));
  }

  protected readonly firstGiftItems = computed(() => this.giftItemsExcludingOthers(0));
  protected readonly secondGiftItems = computed(() => this.giftItemsExcludingOthers(1));
  protected readonly thirdGiftItems = computed(() => this.giftItemsExcludingOthers(2));

  protected setGift(slot: number, value: number | string | null): void {
    const next = [...this.giftPowerIds()];
    next[slot] = (value as number | null) ?? null;
    this.giftPowerIds.set(next);
  }
}
