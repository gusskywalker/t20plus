import { Component, computed, inject, model } from '@angular/core';
import { Checkbox } from '../inputs/checkbox/checkbox';
import { PowerPickRow } from '../power-pick-row/power-pick-row';
import { StaticRegistry } from '../hooks/static-registry';

// Bruxo/Feiticeiro/Mago — ClassArcanistaPowerSeeder.php.
export const BRUXO_POWER_ID = 328;
export const FEITICEIRO_POWER_ID = 329;
export const MAGO_POWER_ID = 330;

// Feiticeiro's own once-picked, permanent Linhagem choice —
// ClassArcanistaPowerSeeder.php. Dracônica's own damage type can't be
// tracked as a runtime choice (no per-character custom-effect storage for
// it), so it's baked into 4 separate concrete powers instead of one power
// plus a free pick — Abençoada/Feérica/Rubra stay single entries.
// Hardcoded, same convention as the Caminho ids above.
const LINHAGEM_POWER_IDS = [
  2047, // Abençoada
  2053, // Dracônica (Ácido)
  2054, // Dracônica (Eletricidade)
  2055, // Dracônica (Fogo)
  2056, // Dracônica (Frio)
  2049, // Feérica
  2050, // Rubra
];

// Arcanista's mandatory Caminho pick — shown below whichever class dropdown
// first sets a character's class-relative Arcanista level to 1 (character
// creation step 3's per-level-row dropdowns, or the level-up modal). Three
// app-checkbox rows acting as a radio group: checking one always selects
// exactly that power and clears any other, since selectedPowerId only ever
// holds one id at a time — there's no dedicated radio input component in
// this app, so this is built directly on the existing checkbox. Feiticeiro
// additionally requires a one-time, permanent Linhagem pick — its own
// dropdown shown only while Feiticeiro is checked.
@Component({
  selector: 'app-arcanista-path-section',
  imports: [Checkbox, PowerPickRow],
  templateUrl: './arcanista-path-section.html',
  styleUrl: './arcanista-path-section.scss',
})
export class ArcanistaPathSection {
  private readonly staticRegistry = inject(StaticRegistry);

  selectedPowerId = model<number | null>(null);
  linhagemPowerId = model<number | null>(null);

  protected readonly bruxoId = BRUXO_POWER_ID;
  protected readonly feiticeiroId = FEITICEIRO_POWER_ID;
  protected readonly magoId = MAGO_POWER_ID;

  protected readonly linhagemItems = computed(() => this.staticRegistry.powers.filter((power) => LINHAGEM_POWER_IDS.includes(power.id)));

  protected isSelected(powerId: number): boolean {
    return this.selectedPowerId() === powerId;
  }

  // checked -> that power is now the pick (clearing whichever other one
  // was checked, since isSelected only matches one at a time). unchecked
  // -> only clears if it was the one actually checked, so unchecking a
  // stale/already-false box (shouldn't happen, but harmless) doesn't wipe
  // a different selection.
  protected toggle(powerId: number, checked: boolean): void {
    if (checked) {
      this.selectedPowerId.set(powerId);
    } else if (this.selectedPowerId() === powerId) {
      this.selectedPowerId.set(null);
    }
  }

}
