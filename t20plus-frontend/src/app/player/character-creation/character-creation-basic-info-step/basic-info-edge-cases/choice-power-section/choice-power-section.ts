import { Component, input, output } from '@angular/core';
import { Power } from '../../../../../api.service';
import { Checkbox } from '../../../../../shared/inputs/checkbox/checkbox';

export interface ChoicePowerGroup {
  holder: Power;
  options: Power[];
  pick: number | null;
}

// One group of checkboxes per granted holder power carrying choice_power
// effects — each option is a power, at most one checked per group (same
// fake-radio-via-checkbox convention as ArcanistaPathSection: checking one
// clears the other).
@Component({
  selector: 'app-choice-power-section',
  imports: [Checkbox],
  templateUrl: './choice-power-section.html',
  styleUrl: './choice-power-section.scss',
})
export class ChoicePowerSection {
  groups = input.required<ChoicePowerGroup[]>();
  pickChange = output<{ holderId: number; optionId: number | null }>();

  protected toggle(group: ChoicePowerGroup, option: Power, checked: boolean): void {
    if (checked) {
      this.pickChange.emit({ holderId: group.holder.id, optionId: option.id });
    } else if (group.pick === option.id) {
      this.pickChange.emit({ holderId: group.holder.id, optionId: null });
    }
  }
}
