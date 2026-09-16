import { Component, input, model } from '@angular/core';
import { Checkbox } from '../../../../../shared/inputs/checkbox/checkbox';

export type ChoosingMechanicChoice = 'skills' | 'skill_and_power' | null;

// Shared by Humano's Versátil (power id 16020) and Lefou's Deformidade
// (power id 16031) — two mutually exclusive alternatives, same fake-radio-
// via-checkbox convention as ArcanistaPathSection's own Bruxo/Feiticeiro/
// Mago toggle: checking one clears the other. The actual consequences (the
// free skill picks, the bonus power) live in character-creation-skills-
// step/character-creation-powers-step, which read this same choice off the
// draft. `powerLabel` is the only thing that differs between the two races
// (Poder Geral vs Poder da Tormenta).
@Component({
  selector: 'app-choosing-mechanic-section',
  imports: [Checkbox],
  templateUrl: './choosing-mechanic-section.html',
  styleUrl: './choosing-mechanic-section.scss',
})
export class ChoosingMechanicSection {
  choice = model<ChoosingMechanicChoice>(null);
  powerLabel = input<string>('Poder Geral');

  protected isSelected(value: ChoosingMechanicChoice): boolean {
    return this.choice() === value;
  }

  protected toggle(value: ChoosingMechanicChoice, checked: boolean): void {
    if (checked) {
      this.choice.set(value);
    } else if (this.choice() === value) {
      this.choice.set(null);
    }
  }
}
