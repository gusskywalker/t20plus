import { Component, WritableSignal, inject, input, output, signal } from '@angular/core';
import { attributeCode } from '../../helpers/attribute-code/attribute-code';
import { ApiService, Character, CharacterActiveEffectRow, Effect, Power } from '../../../api.service';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { Checkbox } from '../../inputs/checkbox/checkbox';
import { calculateSkillBonusBreakdown } from '../../helpers/calculators/calculate-skill-bonus/calculate-skill-bonus';
import { getItemGrantedPowers } from '../../helpers/get-item-granted-effects/get-item-granted-effects';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { resolveTag } from '../../helpers/tag-solver/tag-solver';
import { resolveEffectSentinels } from '../../helpers/resolve-effect-sentinels/resolve-effect-sentinels';
import { spendPm } from '../../helpers/spend-pm/spend-pm';
import { COMBAT_SKILL_IDS, effectSkillIdMatches } from '../../constants/combat-skill-ids';
import { resolvePowerPmCost } from '../../helpers/resolve-power-pm-cost/resolve-power-pm-cost';
import { resolveSkillKeyAttribute } from '../../helpers/resolve-skill-key-attribute/resolve-skill-key-attribute';
import { calculateStatBonus } from '../../helpers/calculators/calculate-stat-bonus/calculate-stat-bonus';
import { isTriggerSatisfied } from '../../helpers/is-trigger-satisfied/is-trigger-satisfied';
import { resolveCurrentSize } from '../../helpers/resolve-current-size/resolve-current-size';
import { resolveReplacedPowerIds } from '../../helpers/resolve-replaced-power-ids/resolve-replaced-power-ids';
import { CHARACTER_SIZE_MODIFIERS, LUTA_SKILL_ID } from '../../constants/character-size-modifiers';
import { getActiveEffects } from '../../helpers/get-active-effects/get-active-effects';
import { resolveTrainedSkillIds } from '../../helpers/resolve-trained-skill-ids/resolve-trained-skill-ids';

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

  // skill = this exact skill; all_skills = every skill; all_skills_no_combat
  // = every skill except Luta/Pontaria (Engenhosidade's own "não pode usar
  // em testes de ataque" clause — those two ARE the attack-roll skills).
  private matchesThisSkill(effect: Effect, skillId: number): boolean {
    return (
      (effect.tag === 'skill' && effect.skill_id === skillId) ||
      this.isSkillAdvantageFor(effect, skillId) ||
      this.isSkillDisadvantageFor(effect, skillId) ||
      effect.tag === 'all_skills' ||
      (effect.tag === 'all_skills_no_combat' && !COMBAT_SKILL_IDS.includes(skillId)) ||
      this.isSkillAttributeSwapFor(effect, skillId)
    );
  }

  private isSkillAttributeSwapFor(effect: Effect, skillId: number): boolean {
    return effect.tag === 'skill_attribute' && effectSkillIdMatches(effect.skill_id, skillId);
  }

  // advantage scope 'skill' targets one exact skill_id, or every skill under
  // an `attribute` (minus `exclude_skill_ids`).
  private isSkillAdvantageFor(effect: Effect, skillId: number): boolean {
    if (effect.tag !== 'advantage' || effect.scope !== 'skill') {
      return false;
    }
    if (effect.skill_id !== undefined) {
      return effect.skill_id === skillId;
    }
    const skill = this.skill();
    return (
      effect.attribute !== undefined &&
      skill !== undefined &&
      resolveSkillKeyAttribute(this.character(), skill, this.staticRegistry.powers) === effect.attribute &&
      !(effect.exclude_skill_ids ?? []).includes(skillId)
    );
  }

  // disadvantage scope 'skill' targets one exact skill_id.
  private isSkillDisadvantageFor(effect: Effect, skillId: number): boolean {
    return effect.tag === 'disadvantage' && effect.scope === 'skill' && effect.skill_id === skillId;
  }

  // applies_when.skill_trained/skill_not_trained (e.g. Herança de Skerry's
  // two mutually-exclusive checkboxes) gates a roll_active row on whether
  // the CURRENTLY-rolled skill is already trained — the skill_id itself
  // stays on the power's own skill/advantage effect, matchesThisSkill
  // already scopes to it, so this only ever needs a plain boolean check.
  private matchesTrainedState(power: Power, skillId: number): boolean {
    const appliesWhen = power.applies_when;
    if (!appliesWhen || (appliesWhen.skill_trained === undefined && appliesWhen.skill_not_trained === undefined)) {
      return true;
    }
    const trained = resolveTrainedSkillIds(this.character().trained_skill_ids ?? [], getActiveEffects(this.character())).has(skillId);
    if (appliesWhen.skill_trained && !trained) {
      return false;
    }
    if (appliesWhen.skill_not_trained && trained) {
      return false;
    }
    return true;
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
    const replacedPowerIds = resolveReplacedPowerIds(new Set((this.character().active_effects ?? []).map((effect) => effect.power_id)), this.staticRegistry.powers);
    for (const effect of this.character().active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || replacedPowerIds.has(power.id) || power.usability !== 'roll_active') {
        continue;
      }
      if (!(power.effects ?? []).some((e) => this.matchesThisSkill(e, skillId))) {
        continue;
      }
      if (!this.matchesTrainedState(power, skillId)) {
        continue;
      }
      rows.push({ effect, power });
    }
    rows.push(...this.generalItemGrantedPowerRows());
    rows.push(...this.activeSpellRollRows());
    if (skillId === LUTA_SKILL_ID) {
      const sizeRow = this.sizeManeuverRow();
      if (sizeRow) {
        rows.push(sizeRow);
      }
    }
    return rows;
  }

  // A roll_active effect of a spell buff currently on the character (e.g. a
  // skill bonus that only counts for one kind of roll) — one synthetic row per
  // active spell effect row, named after the spell, carrying only its
  // roll_active effects that match this skill.
  private activeSpellRollRows(): { effect: CharacterActiveEffectRow; power: Power }[] {
    const skillId = this.skillId();
    const character = this.character();
    return (character.active_spell_effects ?? []).flatMap((spellRow) => {
      const rollEffects = spellRow.effects.filter((effect) => effect.usability === 'roll_active' && this.matchesThisSkill(effect, skillId));
      if (rollEffects.length === 0) {
        return [];
      }
      const syntheticId = -1000000 - spellRow.id;
      return [
        {
          effect: { id: syntheticId, character_id: character.id, power_id: syntheticId, is_active: false, is_favorite: false },
          power: {
            id: syntheticId,
            name: this.staticRegistry.spells.find((spell) => spell.id === spellRow.spell_id)?.name ?? 'Magia',
            description: '',
            source: 'specific',
            usability: 'roll_active',
            default_checked: false,
            action_cost: 'none',
            duration: null,
            pm_cost: 0,
            prerequisites: null,
            effects: rollEffects,
            applies_when: null,
            icon_file_name: null,
          },
        },
      ];
    });
  }

  // Size's Manobras modifier as a self-reported roll_active checkbox on Luta
  // rolls (any Luta roll here is a maneuver — attacks go through the attack
  // modal, which never sees this). Synthetic row, same shape as the item-
  // granted ones; hidden for Médio (0). To be replaced by a real Manobra
  // checkbox once maneuvers are modeled.
  private sizeManeuverRow(): { effect: CharacterActiveEffectRow; power: Power } | null {
    const modifier = CHARACTER_SIZE_MODIFIERS[resolveCurrentSize(this.character())]?.manobras ?? 0;
    if (modifier === 0) {
      return null;
    }
    const syntheticId = -900001;
    return {
      effect: { id: syntheticId, character_id: this.character().id, power_id: syntheticId, is_active: false, is_favorite: false },
      power: {
        id: syntheticId,
        name: 'Tamanho',
        description: '',
        source: 'specific',
        usability: 'roll_active',
        default_checked: false,
        action_cost: 'none',
        duration: null,
        pm_cost: 0,
        prerequisites: null,
        effects: [{ tag: 'skill', op: 'add', skill_id: LUTA_SKILL_ID, value: modifier }],
        applies_when: null,
        icon_file_name: null,
      },
    };
  }

  // Same idea as attack-modal's weaponGrantedPowerRows, sourced from every
  // owned non-consumable general_item instead of a selected weapon —
  // general_items have no worn concept, so ownership alone is enough (a
  // consumable one only grants its power through the separate one-shot
  // "Usar" flow, not this self-report checklist). Synthetic negative ids,
  // same convention as attack-modal's own item-granted rows.
  private generalItemGrantedPowerRows(): { effect: CharacterActiveEffectRow; power: Power }[] {
    const skillId = this.skillId();
    const character = this.character();
    const rows: { effect: CharacterActiveEffectRow; power: Power }[] = [];
    for (const item of character.inventory ?? []) {
      if (item.item_type !== 'general_item') {
        continue;
      }
      const generalItem = this.staticRegistry.generalItems.find((g) => g.id === item.item_id);
      if (!generalItem || generalItem.consumable) {
        continue;
      }
      const grantedPowers = getItemGrantedPowers(item, this.staticRegistry.itemImprovements, this.staticRegistry.itemEnchantments, this.staticRegistry.powers, null, generalItem.effects)
        .filter((power) => power.usability === 'roll_active')
        .filter((power) => (power.effects ?? []).some((e) => this.matchesThisSkill(e, skillId)));
      for (const power of grantedPowers) {
        rows.push({
          effect: { id: -power.id, character_id: character.id, power_id: power.id, is_active: false, is_favorite: false },
          power,
        });
      }
    }
    return rows;
  }

  protected powerChecklistLabel(power: Power, effect: CharacterActiveEffectRow): string {
    const cost = resolvePowerPmCost(power, effect);
    return cost > 0 ? `[${cost}PM] ${power.name}` : power.name;
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
      .flatMap((row) => (row.power.effects ?? []).filter((effect) => isTriggerSatisfied(effect, row.effect.other_sources_state)));
    return checkedEffects.some((effect) => this.isSkillAdvantageFor(effect, skillId));
  }

  protected hasDisadvantage(): boolean {
    const skillId = this.skillId();
    const checkedEffects = this.skillPowerRows()
      .filter((row) => this.isPowerChecked(row.effect.id))
      .flatMap((row) => (row.power.effects ?? []).filter((effect) => isTriggerSatisfied(effect, row.effect.other_sources_state)));
    return checkedEffects.some((effect) => this.isSkillDisadvantageFor(effect, skillId));
  }

  // Advantage and disadvantage together cancel out — a single die.
  protected rollsTwoDice(): boolean {
    return this.hasAdvantage() !== this.hasDisadvantage();
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

  // Whether the two-dice roll just made kept the best or the worst die.
  protected readonly rollKeepsWorst = signal(false);

  protected isCarousel1Loser(): boolean {
    const roll1 = this.roll1Value();
    const roll2 = this.roll2Value();
    if (roll1 === null || roll2 === null) {
      return false;
    }
    return this.rollKeepsWorst() ? roll1 > roll2 : roll1 < roll2;
  }

  protected isCarousel2Loser(): boolean {
    const roll1 = this.roll1Value();
    const roll2 = this.roll2Value();
    if (roll1 === null || roll2 === null) {
      return false;
    }
    return this.rollKeepsWorst() ? roll2 > roll1 : roll2 < roll1;
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
      checkedRows.reduce((sum, row) => sum + resolvePowerPmCost(row.power, row.effect), 0),
    );

    this.rollResult.set(null);
    this.rollBreakdown.set(null);

    const twoDice = this.rollsTwoDice();
    const keepsWorst = this.hasDisadvantage() && !this.hasAdvantage();

    const roll1 = Math.floor(Math.random() * 20) + 1;
    this.spinCarouselTo(this.carouselNumbers, this.carouselIndex, roll1);

    let result = roll1;
    let roll2: number | null = null;
    if (twoDice) {
      roll2 = Math.floor(Math.random() * 20) + 1;
      this.spinCarouselTo(this.carousel2Numbers, this.carousel2Index, roll2);
      result = keepsWorst ? Math.min(roll1, roll2) : Math.max(roll1, roll2);
    }
    this.rollKeepsWorst.set(keepsWorst);

    // A checked roll_active {tag:'skill', op:'trains'} (e.g. Herança de
    // Skerry's Treinar) never reaches getActiveEffects — that row is never
    // toggled — so it's resolved here from the checkbox state instead and
    // forced into the breakdown for just this roll.
    const forceTrained = checkedRows.some((row) => (row.power.effects ?? []).some((effect) => effect.tag === 'skill' && effect.op === 'trains' && effect.skill_id === skill.id));

    const skillParts = calculateSkillBonusBreakdown(
      this.character(),
      skill,
      this.staticRegistry.armors,
      this.staticRegistry.shields,
            this.staticRegistry.powers,
      this.staticRegistry.spells,
      forceTrained,
    );
    const skillBonus = skillParts.reduce((sum, part) => sum + part.value, 0);

    // Resolved per checked power (not flattened) — all_skills carries a
    // sentinel value (e.g. Engenhosidade's own Inteligência), which needs
    // resolving per-power before summing, same as calculate-skill-bonus.ts
    // does for the passive case.
    const checkedPowerBonuses = checkedRows.map((row) => {
      const resolved = resolveEffectSentinels(
        (row.power.effects ?? []).filter((effect) => isTriggerSatisfied(effect, row.effect.other_sources_state)),
        this.character(),
        this.staticRegistry.powers,
      );
      // A skill_attribute swap changes the skill's key attribute for this
      // roll: the bonus becomes the new attribute's minus the current one's.
      const attributeSwap = (row.power.effects ?? []).find((effect) => this.isSkillAttributeSwapFor(effect, skill.id));
      const attributeSwapValue = attributeSwap
        ? calculateStatBonus(this.character(), attributeCode(attributeSwap.value ?? '')) -
          calculateStatBonus(this.character(), resolveSkillKeyAttribute(this.character(), skill, this.staticRegistry.powers))
        : 0;
      const value =
        resolveTag(resolved, 'skill', (e) => e.skill_id === skill.id) +
        resolveTag(resolved, 'all_skills') +
        resolveTag(resolved, 'all_skills_no_combat') +
        attributeSwapValue;
      return { name: row.power.name, value };
    });
    const powerBonus = checkedPowerBonuses.reduce((sum, p) => sum + p.value, 0);
    const total = result + skillBonus + powerBonus;

    const breakdown = [
      `d20 ${this.signedValue(result)}`,
      ...skillParts.map((part) => `${part.label} ${this.signedValue(part.value)}`),
      ...checkedPowerBonuses.filter((p) => p.value !== 0).map((p) => `${p.name} ${this.signedValue(p.value)}`),
    ];

    setTimeout(() => {
      this.rollResult.set(total);
      this.rollBreakdown.set(breakdown);
      this.roll1Value.set(roll1);
      this.roll2Value.set(roll2);
    }, this.carouselTransitionMs);
  }
}
