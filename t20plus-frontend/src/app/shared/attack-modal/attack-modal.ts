import { Component, OnInit, inject, input, output, signal } from '@angular/core';
import { Character, CharacterActiveEffectRow, Power } from '../../api.service';
import { StaticRegistry } from '../hooks/static-registry';
import { Checkbox } from '../inputs/checkbox/checkbox';
import { replaceTormenta0ToO } from '../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';

/**
 * Self-contained attack roll modal — pulled out of character-main since this
 * is expected to keep growing (many small attack-roll edge cases still to
 * come). Owns its own modal chrome (copied from shared/modal/modal.scss,
 * not composed via <app-modal> — this modal's shape is fixed, it doesn't
 * need that component's generic button-row/content-projection inputs) so
 * it's fully atomic and easy to extend on its own.
 */
@Component({
  selector: 'app-attack-modal',
  imports: [Checkbox],
  templateUrl: './attack-modal.html',
  styleUrl: './attack-modal.scss',
})
export class AttackModal implements OnInit {
  private readonly staticRegistry = inject(StaticRegistry);

  character = input.required<Character>();
  cancel = output<void>();

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  // itemWidth/viewportWidth mirror the fixed px sizes in .carousel-item/
  // .carousel-viewport — kept in sync manually since the offset math needs
  // the same numbers the SCSS uses to lay out the strip. viewportWidth must
  // be an exact multiple of itemWidth (5 items visible) — otherwise a
  // partial 6th item leaks into view and throws off centering.
  private readonly carouselItemWidth = 62;
  private readonly carouselViewportWidth = 310; // 5 * 62
  private readonly carouselStartIndex = 9; // value 10 — (9 % 20) + 1

  protected readonly carouselNumbers = signal<number[]>(this.buildCarouselLoops(4));
  protected readonly carouselIndex = signal(this.carouselStartIndex);

  protected carouselOffset(): number {
    return -(this.carouselIndex() * this.carouselItemWidth) + this.carouselViewportWidth / 2 - this.carouselItemWidth / 2;
  }

  // Distance (in columns) from the centered item — drives the font-size
  // falloff (0 = center, 1 = 2nd column, 2 = 3rd/outer column).
  protected carouselDistance(index: number): number {
    return Math.abs(index - this.carouselIndex());
  }

  protected roll(): void {
    const result = Math.floor(Math.random() * 20) + 1;
    const currentValue = (this.carouselIndex() % 20) + 1;
    const stepsToResult = ((result - currentValue) + 20) % 20;
    const spinLoops = 3; // purely visual — how many full loops it spins before landing
    const newIndex = this.carouselIndex() + spinLoops * 20 + stepsToResult;

    // Extend the strip so real items exist all the way to the landing spot.
    const numbers = this.carouselNumbers();
    while (numbers.length <= newIndex + 20) {
      numbers.push(...this.buildCarouselLoops(1));
    }
    this.carouselNumbers.set([...numbers]);

    this.carouselIndex.set(newIndex);
  }

  private buildCarouselLoops(loops: number): number[] {
    const numbers: number[] = [];
    for (let loop = 0; loop < loops; loop++) {
      for (let n = 1; n <= 20; n++) {
        numbers.push(n);
      }
    }
    return numbers;
  }

  // Power checklist — every power the character has whose usability rides
  // an attack roll (active/roll_active — trigger/trigger_active dropped
  // 2026-09-04, no combat engine planned) AND whose effects actually
  // include a mod_hit tag. First step only wires mod_hit; more tags
  // (mod_dmg etc.) get their own checklist group later. Checked state
  // isn't persisted anywhere yet — resolveTag (tag-solver.ts) is what
  // will eventually sum the checked ones into the real roll total.
  private readonly modHitUsabilities = ['active', 'roll_active'];

  protected modHitPowerRows(): { effect: CharacterActiveEffectRow; power: Power }[] {
    const rows: { effect: CharacterActiveEffectRow; power: Power }[] = [];
    for (const effect of this.character().active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || !this.modHitUsabilities.includes(power.usability)) {
        continue;
      }
      if (!(power.effects ?? []).some((e) => e.tag === 'mod_hit')) {
        continue;
      }
      rows.push({ effect, power });
    }
    return rows;
  }

  // Seeded from each row's own power.default_checked — set in ngOnInit,
  // not a field initializer, since required inputs (character) aren't
  // available yet when field initializers run (NG0950).
  protected readonly checkedPowerIds = signal<Set<number>>(new Set());

  ngOnInit(): void {
    const defaultChecked = this.modHitPowerRows()
      .filter((row) => row.power.default_checked)
      .map((row) => row.effect.id);
    this.checkedPowerIds.set(new Set(defaultChecked));
  }

  protected isPowerChecked(effectId: number): boolean {
    return this.checkedPowerIds().has(effectId);
  }

  protected togglePowerCheck(effectId: number): void {
    const next = new Set(this.checkedPowerIds());
    if (next.has(effectId)) {
      next.delete(effectId);
    } else {
      next.add(effectId);
    }
    this.checkedPowerIds.set(next);
  }
}
