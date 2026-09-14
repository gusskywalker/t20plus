import { Component, computed, input, model } from '@angular/core';
import { Power } from '../../api.service';
import { SearchableDropdown } from '../inputs/searchable-dropdown/searchable-dropdown';
import { POWER_PICK_HINTS } from '../helpers/power-pick-constants/power-pick-constants';

/**
 * One "pick a power" dropdown + its hardcoded hint, if any — shared by
 * character-creation-powers-step (looped, one per level row) and
 * level-change-modal (a single instance, for the pending level's power
 * pick). Same split as class-pick-row: each caller gathers its own
 * available-items list differently (draft vs a real Character), only the
 * rendering lives here — including the hint lookup itself, so neither
 * caller needs its own powerPickHint() anymore.
 */
@Component({
  selector: 'app-power-pick-row',
  imports: [SearchableDropdown],
  templateUrl: './power-pick-row.html',
  styleUrl: './power-pick-row.scss',
})
export class PowerPickRow {
  label = input.required<string>();
  items = input.required<Power[]>();
  openUpwards = input(false);

  powerId = model<number | null>(null);

  // SearchableDropdown's own value/valueChange is typed number | string |
  // null generically — a power id is always numeric in practice, but
  // templates can't `as`-cast, so the narrowing happens here.
  protected setPowerId(value: number | string | null): void {
    this.powerId.set(value as number | null);
  }

  protected readonly hint = computed(() => {
    const id = this.powerId();
    return id !== null ? (POWER_PICK_HINTS[id] ?? null) : null;
  });
}
