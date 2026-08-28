import { Component, afterNextRender, signal } from '@angular/core';

@Component({
  selector: 'app-dice-outline',
  imports: [],
  templateUrl: './dice-outline.html',
  styleUrl: './dice-outline.scss',
  host: {
    '[class.no-transition]': 'justMounted()',
  },
})
export class DiceOutline {
  /**
   * Suppresses the fill transition for one frame on mount. Without this,
   * navigating to a page where the dice badge re-appears under a
   * stationary cursor makes it visibly animate from the base color to the
   * hover color, since CSS :hover has no concept of "cursor just arrived"
   * vs "cursor was already here" — it only sees "is the cursor over this
   * box right now."
   */
  protected readonly justMounted = signal(true);

  constructor() {
    afterNextRender(() => {
      requestAnimationFrame(() => this.justMounted.set(false));
    });
  }
}
