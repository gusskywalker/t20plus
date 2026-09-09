import { Component, WritableSignal, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterActiveEffectRow, Power } from '../../../api.service';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { Checkbox } from '../../inputs/checkbox/checkbox';
import { calculateSkillBonusBreakdown } from '../../helpers/calculators/calculate-skill-bonus/calculate-skill-bonus';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { resolveTag } from '../../helpers/tag-solver/tag-solver';
import { spendPm } from '../../helpers/spend-pm/spend-pm';

/**
 * Skill check roll — same carousel/checklist/breakdown shape as attack-
 * modal's own hit roll (step 3/4), just without a weapon in play and
 * without the Passou/Falhou split: a skill check has no "did it connect"
 * follow-up, so the bottom button is just Cancelar until rolled, then
 * Fechar.
 */
@Component({
  selector: 'app-skill-roll-modal',
  imports: [Checkbox],
  templateUrl: './skill-roll-modal.html',
  styleUrl: './skill-roll-modal.scss',
})
export class SkillRollModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason attack-modal needs its own: any
  // future patchCharacterCache call's key must match character-main.ts's
  // own string id, not the numeric Character.id.
  id = input.required<string>();
  // Which skill this roll is for — set from whichever skill-row was
  // clicked.
  skillId = input.required<number>();
  cancel = output<void>();

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  protected skill() {
    return this.staticRegistry.skills.find((s) => s.id === this.skillId());
  }

  // Every power the character has whose usability is roll_active (a fresh
  // per-roll self-report) AND whose effects include a skill entry for
  // THIS skill — same shape as attack-modal's attackPowerRows, just
  // matched on skill_id instead of a fixed mod_hit/mod_dmg tag list.
  // 'active' powers already fold into the skill's own displayed bonus via
  // calculateSkillBonus's getActiveEffects call, so they don't belong here.
  protected skillPowerRows(): { effect: CharacterActiveEffectRow; power: Power }[] {
    const skillId = this.skillId();
    const rows: { effect: CharacterActiveEffectRow; power: Power }[] = [];
    for (const effect of this.character().active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || power.usability !== 'roll_active') {
        continue;
      }
      if (!(power.effects ?? []).some((e) => e.tag === 'skill' && e.skill_id === skillId)) {
        continue;
      }
      rows.push({ effect, power });
    }
    return rows;
  }

  protected powerChecklistLabel(power: Power): string {
    return power.pm_cost > 0 ? `[${power.pm_cost}PM] ${power.name}` : power.name;
  }

  private readonly checkedPowerIds = signal<Set<number>>(new Set());

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

  // Live, not a snapshot — reacts immediately as the player checks/
  // unchecks powers, same as attack-modal's hasAdvantage.
  protected hasAdvantage(): boolean {
    const skillId = this.skillId();
    const checkedEffects = this.skillPowerRows()
      .filter((row) => this.isPowerChecked(row.effect.id))
      .flatMap((row) => row.power.effects ?? []);
    return checkedEffects.some((effect) => effect.tag === 'advantage' && effect.scope === 'skill' && effect.skill_id === skillId);
  }

  // itemWidth/viewportWidth/startIndex/transitionMs mirror attack-modal's
  // own fixed numbers exactly — the carousel SCSS is copy-pasted from
  // there too, so these have to stay in sync with it the same way.
  private readonly carouselItemWidth = 62;
  private readonly carouselViewportWidth = 310; // 5 * 62
  private readonly carouselStartIndex = 9; // value 10 — (9 % 20) + 1
  private readonly carouselTransitionMs = 1400;

  protected readonly carouselNumbers = signal<number[]>(this.buildCarouselLoops(4));
  protected readonly carouselIndex = signal(this.carouselStartIndex);

  protected carouselOffset(): number {
    return -(this.carouselIndex() * this.carouselItemWidth) + this.carouselViewportWidth / 2 - this.carouselItemWidth / 2;
  }

  protected carouselDistance(index: number): number {
    return Math.abs(index - this.carouselIndex());
  }

  // Second carousel — only shown/rolled when a checked power grants
  // advantage on this skill roll (see hasAdvantage above). Duplicated
  // state rather than a shared/generalized carousel, same convention as
  // attack-modal (duplicate over nest/share).
  protected readonly carousel2Numbers = signal<number[]>(this.buildCarouselLoops(4));
  protected readonly carousel2Index = signal(this.carouselStartIndex);

  protected carousel2Offset(): number {
    return -(this.carousel2Index() * this.carouselItemWidth) + this.carouselViewportWidth / 2 - this.carouselItemWidth / 2;
  }

  protected carousel2Distance(index: number): number {
    return Math.abs(index - this.carousel2Index());
  }

  protected readonly roll1Value = signal<number | null>(null);
  protected readonly roll2Value = signal<number | null>(null);

  protected isCarousel1Loser(): boolean {
    const roll1 = this.roll1Value();
    const roll2 = this.roll2Value();
    return roll1 !== null && roll2 !== null && roll1 < roll2;
  }

  protected isCarousel2Loser(): boolean {
    const roll1 = this.roll1Value();
    const roll2 = this.roll2Value();
    return roll1 !== null && roll2 !== null && roll2 < roll1;
  }

  protected readonly rollResult = signal<number | null>(null);
  protected readonly rollBreakdown = signal<string[] | null>(null);

  private buildCarouselLoops(loops: number): number[] {
    const numbers: number[] = [];
    for (let loop = 0; loop < loops; loop++) {
      for (let n = 1; n <= 20; n++) {
        numbers.push(n);
      }
    }
    return numbers;
  }

  // Shared by both carousels — spins whichever (numbers, index) pair is
  // passed in to land on `result`. Same extraction reasoning as attack-
  // modal's own spinCarouselTo.
  private spinCarouselTo(numbers: WritableSignal<number[]>, index: WritableSignal<number>, result: number): void {
    const currentValue = (index() % 20) + 1;
    const stepsToResult = ((result - currentValue) + 20) % 20;
    const spinLoops = 3;
    const newIndex = index() + spinLoops * 20 + stepsToResult;

    const strip = numbers();
    while (strip.length <= newIndex + 20) {
      strip.push(...this.buildCarouselLoops(1));
    }
    numbers.set([...strip]);

    index.set(newIndex);
  }

  private signedValue(value: number): string {
    return value >= 0 ? `+${value}` : `${value}`;
  }

  protected roll(): void {
    const skill = this.skill();
    if (!skill) {
      return;
    }

    const checkedRows = this.skillPowerRows().filter((row) => this.isPowerChecked(row.effect.id));
    spendPm(
      this.apiService,
      this.useCharacter,
      this.id(),
      this.character(),
      checkedRows.reduce((sum, row) => sum + (row.power.pm_cost ?? 0), 0),
    );

    this.rollResult.set(null);
    this.rollBreakdown.set(null);

    const advantage = this.hasAdvantage();

    const roll1 = Math.floor(Math.random() * 20) + 1;
    this.spinCarouselTo(this.carouselNumbers, this.carouselIndex, roll1);

    let result = roll1;
    let roll2: number | null = null;
    if (advantage) {
      roll2 = Math.floor(Math.random() * 20) + 1;
      this.spinCarouselTo(this.carousel2Numbers, this.carousel2Index, roll2);
      result = Math.max(roll1, roll2);
    }

    const checkedEffects = checkedRows.flatMap((row) => row.power.effects ?? []);
    const skillParts = calculateSkillBonusBreakdown(this.character(), skill, this.staticRegistry.armors, this.staticRegistry.shields, this.staticRegistry.powers);
    const skillBonus = skillParts.reduce((sum, part) => sum + part.value, 0);
    const powerBonus = resolveTag(checkedEffects, 'skill', (e) => e.skill_id === skill.id);
    const total = result + skillBonus + powerBonus;

    const breakdown = [
      `d20 ${this.signedValue(result)}`,
      ...skillParts.map((part) => `${part.label} ${this.signedValue(part.value)}`),
      ...checkedRows
        .filter((row) => (row.power.effects ?? []).some((e) => e.tag === 'skill' && e.skill_id === skill.id))
        .map((row) => `${row.power.name} ${this.signedValue(resolveTag(row.power.effects ?? [], 'skill', (e) => e.skill_id === skill.id))}`),
    ];

    setTimeout(() => {
      this.rollResult.set(total);
      this.rollBreakdown.set(breakdown);
      this.roll1Value.set(roll1);
      this.roll2Value.set(roll2);
    }, this.carouselTransitionMs);
  }
}
