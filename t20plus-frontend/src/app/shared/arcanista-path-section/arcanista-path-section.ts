import { Component, model } from '@angular/core';
import { Checkbox } from '../inputs/checkbox/checkbox';

// Bruxo/Feiticeiro/Mago — ClassArcanistaPowerSeeder.php.
const BRUXO_POWER_ID = 328;
const FEITICEIRO_POWER_ID = 329;
const MAGO_POWER_ID = 330;

// Arcanista's mandatory Caminho pick — shown below whichever class dropdown
// first sets a character's class-relative Arcanista level to 1 (character
// creation step 3's per-level-row dropdowns, or the level-up modal). Three
// app-checkbox rows acting as a radio group: checking one always selects
// exactly that power and clears any other, since selectedPowerId only ever
// holds one id at a time — there's no dedicated radio input component in
// this app, so this is built directly on the existing checkbox.
@Component({
  selector: 'app-arcanista-path-section',
  imports: [Checkbox],
  templateUrl: './arcanista-path-section.html',
  styleUrl: './arcanista-path-section.scss',
})
export class ArcanistaPathSection {
  selectedPowerId = model<number | null>(null);

  protected readonly bruxoId = BRUXO_POWER_ID;
  protected readonly feiticeiroId = FEITICEIRO_POWER_ID;
  protected readonly magoId = MAGO_POWER_ID;

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
