import { Component, model } from '@angular/core';
import { Checkbox } from '../../../../../shared/inputs/checkbox/checkbox';

export type MemoriaPostumaChoice = 'skill' | 'general_power' | 'change_base_race' | null;

// Osteon's Memória Póstuma (power id 16041) — three mutually exclusive
// alternatives, same fake-radio-via-checkbox convention as
// ChoosingMechanicSection/ArcanistaPathSection: checking one clears the
// other two. The actual consequences (the free skill pick, the bonus
// power, the Raça Base + ability dropdowns) live in
// character-creation-skills-step/character-creation-powers-step/this same
// basic-info-step, which read this same choice off the draft.
@Component({
  selector: 'app-memoria-postuma-section',
  imports: [Checkbox],
  templateUrl: './memoria-postuma-section.html',
  styleUrl: './memoria-postuma-section.scss',
})
export class MemoriaPostumaSection {
  choice = model<MemoriaPostumaChoice>(null);

  protected isSelected(value: MemoriaPostumaChoice): boolean {
    return this.choice() === value;
  }

  protected toggle(value: MemoriaPostumaChoice, checked: boolean): void {
    if (checked) {
      this.choice.set(value);
    } else if (this.choice() === value) {
      this.choice.set(null);
    }
  }
}
