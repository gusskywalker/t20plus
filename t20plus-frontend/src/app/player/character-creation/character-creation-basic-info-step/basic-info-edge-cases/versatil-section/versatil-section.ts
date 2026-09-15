import { Component, model } from '@angular/core';
import { Checkbox } from '../../../../../shared/inputs/checkbox/checkbox';

export type VersatilChoice = 'skills' | 'skill_and_power' | null;

// Humano's Versátil (power id 16020) — two mutually exclusive alternatives,
// same fake-radio-via-checkbox convention as ArcanistaPathSection's own
// Bruxo/Feiticeiro/Mago toggle: checking one clears the other. The actual
// consequences (the free skill picks, the bonus general power) live in
// character-creation-skills-step/character-creation-powers-step, which
// read this same choice off the draft.
@Component({
  selector: 'app-versatil-section',
  imports: [Checkbox],
  templateUrl: './versatil-section.html',
  styleUrl: './versatil-section.scss',
})
export class VersatilSection {
  choice = model<VersatilChoice>(null);

  protected isSelected(value: VersatilChoice): boolean {
    return this.choice() === value;
  }

  protected toggle(value: VersatilChoice, checked: boolean): void {
    if (checked) {
      this.choice.set(value);
    } else if (this.choice() === value) {
      this.choice.set(null);
    }
  }
}
