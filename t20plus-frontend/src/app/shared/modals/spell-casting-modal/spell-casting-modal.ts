import { Component, computed, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Effect, Spell, SpellEnhancement } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { resolveSpellCasterInfo } from '../../helpers/resolve-spell-caster-info/resolve-spell-caster-info';
import { calculateSpellCd } from '../../helpers/calculators/calculate-spell-cd/calculate-spell-cd';
import { calculateMaxSpellCircle } from '../../helpers/calculators/calculate-max-spell-circle/calculate-max-spell-circle';
import { spendPm } from '../../helpers/spend-pm/spend-pm';
import { rollDice } from '../../helpers/roll-dice/roll-dice';
import { Checkbox } from '../../inputs/checkbox/checkbox';
import { SONO_SPELL_ID, resolveSonoConditionIds } from './spell-edge-cases/sono';
import { ARMA_DE_JADE_SPELL_ID, applyArmaDeJadeUpgrade } from './spell-edge-cases/arma-de-jade';

// Base PM cost by círculo (spells-basics.md's own table) — before any
// enhancement picks. Only used here; move to a shared helper if a second
// consumer ever needs it.
const BASE_PM_COST_BY_CIRCLE: Record<number, number> = { 1: 1, 2: 3, 3: 6, 4: 10, 5: 15 };

interface SpellCastResult {
  // null means no damage was rolled at all (a condition-only spell, or a
  // resisted cast with no fail-multiply) — the Total line is hidden
  // entirely rather than showing a misleading "Total 0".
  total: number | null;
  breakdown: string[];
}

interface EnhancementRow {
  key: string;
  enhancementIndex: number;
  rowIndex: number;
  label: string;
  checked: boolean;
  disabled: boolean;
}

/**
 * Self-contained spell-casting modal, opened from a spell card in the
 * character sheet's Magias section. Page 1 (the only page built so far) is
 * purely descriptive — same shape as item-details-modal's own page 1
 * (icon, name, an inner-card properties section, an inner-card
 * enhancements section, then the description). Casting itself (PM cost,
 * key attribute, enhancement picks) isn't wired up yet. Owns its own modal
 * chrome (copied from shared/modal/modal.scss, same as attack-modal)
 * instead of composing <app-modal>.
 */
@Component({
  selector: 'app-spell-casting-modal',
  imports: [Checkbox],
  templateUrl: './spell-casting-modal.html',
  styleUrl: './spell-casting-modal.scss',
})
export class SpellCastingModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason attack-modal/golpe-pessoal-modal
  // need their own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  spell = input.required<Spell>();
  cancel = output<void>();

  // 1: descriptive page. 2: enhancement picks + Lançar Magia. 3: rolling
  // (no buttons — nothing to do but wait). 4: result. "Cancelar" on page 1
  // closes the modal; on page 2 it becomes "Voltar" and just returns to
  // page 1 instead, same convention as item-details-modal's own multi-page
  // back button. Both button rows are hidden on page 3, and page 4's
  // bottom row becomes "Fechar" (closes the modal).
  protected readonly currentPage = signal<1 | 2 | 3 | 4>(1);

  protected handleCancel(): void {
    if (this.currentPage() === 2) {
      this.currentPage.set(1);
      return;
    }
    this.cancel.emit();
  }

  // Top row's button — "Lançar Magia" on both pages, same position, but
  // page 1's press just moves to page 2 (the enhancement-picking view)
  // while page 2's press is the real cast action.
  protected handleLancarMagia(): void {
    if (this.currentPage() === 1) {
      this.currentPage.set(2);
      return;
    }
    this.castSpell();
  }

  // Flips once Lançar Magia is actually pressed on page 2 — swaps the
  // button row to Passou/Falhou. Nothing beyond that is wired up yet (no
  // character_active_spell_effects row, no resist handling).
  protected readonly hasCast = signal(false);

  private castSpell(): void {
    spendPm(this.apiService, this.useCharacter, this.id(), this.character(), this.pmCost());
    this.hasCast.set(true);
  }

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  protected circleLabel(circle: number): string {
    return `${circle}º Círculo`;
  }

  protected typeLabel(type: string): string {
    const labels: Record<string, string> = {
      arcana: 'Arcana',
      divina: 'Divina',
      universal: 'Universal',
    };
    return labels[type] ?? type;
  }

  protected schoolLabel(school: string): string {
    const labels: Record<string, string> = {
      abjuracao: 'Abjuração',
      adivinhacao: 'Adivinhação',
      convocacao: 'Convocação',
      encantamento: 'Encantamento',
      evocacao: 'Evocação',
      ilusao: 'Ilusão',
      necromancia: 'Necromancia',
      transmutacao: 'Transmutação',
    };
    return labels[school] ?? school;
  }

  protected actionCostLabel(actionCost: string): string {
    const labels: Record<string, string> = {
      standard: 'Ação Padrão',
      movement: 'Ação de Movimento',
      complete: 'Ação Completa',
      extra: 'Ação Extra',
      free: 'Ação Livre',
      none: 'Nenhuma',
      reaction: 'Reação',
    };
    return labels[actionCost] ?? actionCost;
  }

  protected resistanceLabel(resistance: string): string {
    const labels: Record<string, string> = {
      fortitude: 'Fortitude',
      reflexos: 'Reflexos',
      vontade: 'Vontade',
    };
    return labels[resistance] ?? resistance;
  }

  // 'caster' is the one standardized literal Spell.affects can carry (see
  // its own comment) — a future ally-targeting feature matches against it
  // directly, so it can't just be free Portuguese text like every other
  // affects value, which passes through unchanged here.
  protected affectsLabel(affects: string): string {
    return affects === 'caster' ? 'Você' : affects;
  }

  // Which class taught this spell, that class's current level (the PM
  // limit), and its key attribute (the CD attribute) — see
  // resolve-spell-caster-info.ts. Null would mean the spell somehow isn't
  // actually known, which shouldn't happen from how this modal is opened.
  private readonly casterInfo = computed(() => resolveSpellCasterInfo(this.character(), this.spell().id, this.staticRegistry.powers));

  protected readonly cd = computed(() => {
    const info = this.casterInfo();
    return info ? calculateSpellCd(this.character(), info.keyAttribute, this.staticRegistry.powers) : null;
  });

  // The class-level rule caps how much you're ALLOWED to spend, but you
  // still can't spend PM you don't have — whichever is lower actually
  // applies.
  protected readonly pmLimit = computed(() => Math.min(this.casterInfo()?.classLevel ?? 0, this.character().current_pm ?? 0));

  // The highest círculo currently accessible through whichever class
  // taught THIS spell (e.g. an Arcanista 19/Bardo 1 casting a Bardo spell
  // is capped at Bardo 1's own círculo access, not Arcanista's) — caps any
  // enhancement marked max_stacks_by_max_circle.
  protected readonly casterMaxCircle = computed(() => {
    const info = this.casterInfo();
    return info ? calculateMaxSpellCircle(info.classId, info.classLevel) : 0;
  });

  // General powers (usability: 'spell_enhancement', e.g. Magia Acelerada)
  // the character already has, that apply to THIS spell specifically —
  // gated by applies_when.spell_action_costs against the spell's own
  // action_cost, same "runtime context" role applies_when already plays
  // for attack-modal's weapon_* fields. The power's own prerequisites (its
  // build-time gate) already ran once at pick time — never rechecked here.
  private readonly matchingSpellEnhancementPowers = computed(() => {
    const spell = this.spell();
    const granted = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
    return this.staticRegistry.powers.filter(
      (power) => granted.has(power.id) && power.usability === 'spell_enhancement' && (power.applies_when?.spell_action_costs ?? []).includes(spell.action_cost),
    );
  });

  // The spell's own enhancements plus any matching general power translated
  // into the same shape — everything downstream (PM cost, the checkbox
  // list, unique_change_group/requires_enhancement_index, casting
  // resolution) reads from this one combined list instead of spell().
  // enhancements directly, so a general power's pick slots into the exact
  // same index space and gets the exact same handling for free.
  protected readonly castEnhancements = computed<SpellEnhancement[]>(() => [
    ...(this.spell().enhancements ?? []),
    ...this.matchingSpellEnhancementPowers().map((power) => ({
      description: power.description,
      pm_cost: power.pm_cost,
      repeatable: false,
      is_truque: false,
      effects: power.effects ?? [],
    })),
  ]);

  // How many times each enhancement (by its index in castEnhancements()) is
  // currently checked — 0 or 1 for a normal/unique_change entry, 0..N for a
  // repeatable one (each unit stacks its own PM cost/effect).
  private readonly enhancementCounts = signal<Record<number, number>>({});

  protected readonly pmCost = computed(() => {
    const base = BASE_PM_COST_BY_CIRCLE[this.spell().circle] ?? 0;
    const counts = this.enhancementCounts();
    const enhancementsTotal = this.castEnhancements().reduce((sum, enhancement, i) => sum + (counts[i] ?? 0) * enhancement.pm_cost, 0);
    return base + enhancementsTotal;
  });

  // Which enhancement index is currently checked for each unique_change_group
  // value — checking one disables every other checkbox sharing that same
  // group (see enhancementRows below), matching "muda" enhancements never
  // stacking within the same characteristic. Entries with different group
  // values are independent of each other.
  private readonly checkedUniqueChangeGroups = computed(() => {
    const counts = this.enhancementCounts();
    const groups = new Map<string, number>();
    this.castEnhancements().forEach((enhancement, i) => {
      if (enhancement.unique_change_group && (counts[i] ?? 0) > 0) {
        groups.set(enhancement.unique_change_group, i);
      }
    });
    return groups;
  });

  // Flattened list of every checkbox row to render — a repeatable
  // enhancement expands into (checked count + 1) rows (all checked plus
  // one empty "next" one to grow the stack); everything else is exactly
  // one row. A row is disabled (but never hidden) when checking it would
  // exceed the PM limit, conflict with an already-checked entry sharing
  // its own unique_change_group, exceed a max_stacks_by_max_circle cap, or
  // (for the unchecked "next" row only) its own requires_enhancement_index
  // prerequisite isn't checked yet — already-checked rows stay clickable
  // so they can always be unchecked.
  protected readonly enhancementRows = computed<EnhancementRow[]>(() => {
    const enhancements = this.castEnhancements();
    const counts = this.enhancementCounts();
    const cost = this.pmCost();
    const limit = this.pmLimit();
    const uniqueChangeGroups = this.checkedUniqueChangeGroups();
    const maxCircle = this.casterMaxCircle();
    const rows: EnhancementRow[] = [];

    enhancements.forEach((enhancement, enhancementIndex) => {
      const count = counts[enhancementIndex] ?? 0;
      const label = `[${enhancement.pm_cost}PM] ${enhancement.description}`;
      const rowCount = enhancement.repeatable ? count + 1 : 1;

      for (let rowIndex = 0; rowIndex < rowCount; rowIndex++) {
        const checked = rowIndex < count;
        const wouldExceedLimit = !checked && cost + enhancement.pm_cost > limit;
        const group = enhancement.unique_change_group;
        const conflictsUniqueChange = !checked && !!group && uniqueChangeGroups.has(group) && uniqueChangeGroups.get(group) !== enhancementIndex;
        const missingRequirement = !checked && enhancement.requires_enhancement_index !== undefined && (counts[enhancement.requires_enhancement_index] ?? 0) === 0;
        const exceedsCircleStacks = !checked && !!enhancement.max_stacks_by_max_circle && count >= maxCircle;
        const wrongGod = !checked && enhancement.requires_god_id !== undefined && this.character().god_id !== enhancement.requires_god_id;
        rows.push({
          key: `${enhancementIndex}-${rowIndex}`,
          enhancementIndex,
          rowIndex,
          label,
          checked,
          // Once actually cast, every pick is locked in — even an already-
          // checked row (normally always clickable to uncheck) stops being
          // interactive.
          disabled: this.hasCast() || wouldExceedLimit || conflictsUniqueChange || missingRequirement || exceedsCircleStacks || wrongGod,
        });
      }
    });

    return rows;
  });

  // Clicking a repeatable enhancement's row sets the stack to that row's
  // position (checking row 2 means "I want 3 of these," unchecking row 1
  // means "back down to 1") — a normal/unique_change row just toggles 0/1.
  // Unchecking one also force-unchecks any OTHER enhancement whose
  // requires_enhancement_index points at it — e.g. Arma Espiritual's
  // "aumenta o bônus" can't stay checked once the +1 Defesa pick it builds
  // on is gone.
  protected toggleEnhancementRow(row: EnhancementRow, checked: boolean): void {
    const enhancement = this.castEnhancements()[row.enhancementIndex];
    if (!enhancement) {
      return;
    }
    const counts = { ...this.enhancementCounts() };
    counts[row.enhancementIndex] = enhancement.repeatable ? (checked ? row.rowIndex + 1 : row.rowIndex) : checked ? 1 : 0;

    this.castEnhancements().forEach((other, i) => {
      if (other.requires_enhancement_index !== undefined && (counts[other.requires_enhancement_index] ?? 0) === 0) {
        counts[i] = 0;
      }
    });

    this.enhancementCounts.set(counts);
  }

  // "Rolando." / "Rolando.." / "Rolando..." — same cycling-dots convention
  // as attack-modal's own rollingText, held for damageRollMs before the
  // result actually shows.
  private readonly damageRollMs = 2000;
  protected readonly rollingDots = signal(1);
  protected rollingText(): string {
    return 'Rolando' + '.'.repeat(this.rollingDots());
  }

  protected readonly castResult = signal<SpellCastResult | null>(null);

  // Self-reported resist outcome — Passou means the target failed its
  // resistance (the spell's on_spell_success effects apply in full);
  // Falhou means the target resisted (on_spell_fail applies instead,
  // per-spell — a multiply, a still-inflicted condition, or neither).
  protected markPassed(): void {
    this.resolveCast(false);
  }

  protected markFailed(): void {
    this.resolveCast(true);
  }

  private resolveCast(resisted: boolean): void {
    this.currentPage.set(3);
    this.castResult.set(null);
    this.rollingDots.set(1);

    const dotsInterval = setInterval(() => {
      this.rollingDots.set((this.rollingDots() % 3) + 1);
    }, 500);

    const spell = this.spell();
    const effects = spell.effects ?? [];
    const enhancements = this.castEnhancements();
    const counts = this.enhancementCounts();
    const trigger = resisted ? 'on_spell_fail' : 'on_spell_success';
    const breakdown: string[] = [];
    let total: number | null = null;

    // Every checked enhancement's index, duplicated once per repeated
    // instance — a plain record of what was picked for this cast, same
    // shape as golpes_pessoais.power_ids. Only meaningful for a 'buff'
    // cast that actually persists a row below, but gathered regardless.
    const chosenEnhancementIndices: number[] = [];
    enhancements.forEach((enhancement, i) => {
      for (let n = 0; n < (counts[i] ?? 0); n++) {
        chosenEnhancementIndices.push(i);
      }
    });

    // spell.usability governs which of these runs — an explicit, authored
    // dispatch key (see its own comment on the Spell interface), not
    // derived from what shape of effects happen to be present. A 'damage'
    // spell that also inflicts a condition on success (Adaga Mental) still
    // rolls its dice AND gets its condition line below; a pure 'debuff'
    // (Sono, Leque Cromático) never rolls damage at all, even resisted.
    if (spell.usability === 'damage' || spell.usability === 'debuff') {
      if (spell.usability === 'damage') {
        // A resisted cast only still deals damage if the spell explicitly
        // says so (trigger: on_spell_fail, tag: mod_spell_dmg, op:
        // multiply) — no such entry means fully negated, no damage rolled
        // at all (not just zeroed after rolling). base_spell_dmg is the
        // spell's own inherent damage (always active, no trigger);
        // mod_spell_dmg is anything that modifies that total — a checked
        // enhancement's own added dice, or this fail-only multiplier.
        const failMultiply = resisted ? effects.find((effect) => effect.trigger === 'on_spell_fail' && effect.tag === 'mod_spell_dmg' && effect.op === 'multiply') : undefined;
        if (!resisted || failMultiply) {
          // Every damage die (the spell's own base_spell_dmg plus every
          // checked mod_spell_dmg add, once per repeated instance) rolls as
          // ONE combined die under a single "Dados da Magia" line, not one
          // line per source. A checked enhancement carrying tag:
          // base_spell_dmg, op: 'set' (e.g. Açoite Flamejante's "muda o
          // dano para 4d6") REPLACES the spell's own base value outright
          // instead of stacking with it — at most one such override can
          // ever be checked, since they always share a unique_change_group
          // with each other.
          const dmgNotations: string[] = [];
          const baseOverride = enhancements
            .flatMap((enhancement, i) => ((counts[i] ?? 0) > 0 ? (enhancement.effects ?? []) : []))
            .find((effect) => effect.tag === 'base_spell_dmg' && effect.op === 'set');
          const baseDamage = baseOverride ?? effects.find((effect) => effect.tag === 'base_spell_dmg');
          if (baseDamage) {
            dmgNotations.push(String(baseDamage.value));
          }
          enhancements.forEach((enhancement, enhancementIndex) => {
            const count = counts[enhancementIndex] ?? 0;
            if (count === 0) {
              return;
            }
            (enhancement.effects ?? [])
              .filter((effect) => effect.tag === 'mod_spell_dmg' && effect.op === 'add')
              .forEach((effect) => {
                for (let n = 0; n < count; n++) {
                  dmgNotations.push(String(effect.value));
                }
              });
          });

          if (dmgNotations.length > 0) {
            const combinedNotation = this.combineDiceNotations(dmgNotations);
            const rolled = rollDice(combinedNotation);
            total = rolled;
            breakdown.push(`Dados da Magia (${combinedNotation}) ${this.signedValue(rolled)}`);
          }

          if (failMultiply && total !== null) {
            total = Math.floor(total * Number(failMultiply.value ?? 1));
          }
        }
      }

      // Only an actual matching inflict entry produces a line — no
      // placeholder for "nothing was inflicted." Checked enhancement-level
      // inflicts (e.g. Leque Cromático's "vulnerável" pick) count alongside
      // the spell's own. Shared by both 'damage' (Adaga Mental) and pure
      // 'debuff' spells.
      const inflictEntries = [
        ...effects.filter((effect) => effect.trigger === trigger && effect.tag === 'condition' && effect.op === 'inflict'),
        ...enhancements.flatMap((enhancement, i) =>
          (counts[i] ?? 0) > 0 ? (enhancement.effects ?? []).filter((effect) => effect.trigger === trigger && effect.tag === 'condition' && effect.op === 'inflict') : [],
        ),
      ];
      inflictEntries.forEach((entry) => this.pushConditionLine(breakdown, entry.condition_id));

      // Sono's success side branches on combat state — not expressible
      // through the generic trigger/tag system, so it's resolved by its
      // own dedicated file (spell-edge-cases/sono.ts), same "hardcode the
      // exception, call a dedicated resolver" convention as attack-modal's
      // own attack-power-resolvers/. Its Falhou side stays fully generic
      // (already covered by inflictEntries above via trigger: on_spell_fail).
      if (spell.id === SONO_SPELL_ID && !resisted) {
        const combatFlagIndex = enhancements.findIndex((enhancement) => (enhancement.effects ?? []).some((effect) => effect.tag === 'context_flag' && effect.op === 'target_in_combat'));
        const targetInCombat = combatFlagIndex !== -1 && (counts[combatFlagIndex] ?? 0) > 0;
        resolveSonoConditionIds(targetInCombat).forEach((conditionId) => this.pushConditionLine(breakdown, conditionId));
      }
    }

    if (spell.usability === 'buff') {
      // Anything the spell/its checked enhancements grant, other than the
      // tags 'damage'/'debuff' already handle above — mod_def, skill, ...
      // — we don't translate into a number, so it's persisted as-is into
      // character_active_spell_effects for getActiveEffects (Defesa/skill/
      // etc. calculators) to pick up, not just shown on this one screen.
      const knownTags = new Set(['base_spell_dmg', 'mod_spell_dmg', 'condition']);
      const isBuffEffect = (effect: Effect) => (!effect.trigger || effect.trigger === trigger) && !knownTags.has(effect.tag);
      // Repeated once per stacked instance (spells-basics.md's "Aprimoramentos
      // Cumulativos" — e.g. Armadura Arcana's own "+1 Defesa" repeatable pick
      // checked 3 times grants +3, not +1), same per-count repeat the damage
      // dice loop above already does.
      let buffEffects: Effect[] = [
        ...effects.filter(isBuffEffect),
        ...enhancements.flatMap((enhancement, i) => {
          const enhancementBuffEffects = (enhancement.effects ?? []).filter(isBuffEffect);
          return Array.from({ length: counts[i] ?? 0 }, () => enhancementBuffEffects).flat();
        }),
      ];

      // Arma de Jade's Lin-Wu-only pick rewrites another enhancement's own
      // value rather than granting an effect of its own — not expressible
      // generically, so it gets its own dedicated resolver, same
      // convention as Sono's combat-state branch.
      if (spell.id === ARMA_DE_JADE_SPELL_ID) {
        buffEffects = applyArmaDeJadeUpgrade(buffEffects, counts);
      }

      // sum_group entries (see Effect's own comment) — the spell's own base
      // +5 and its repeatable "+1 Defesa" enhancement share one sum_group
      // so they collapse into a single combined effect here. Any
      // stack_group the seeded data carries (e.g. Armadura Arcana's own
      // 'armor_bonus', authored directly on its effects — not every
      // spell's mod_def implies this, only ones whose own text says so)
      // rides along on whichever entry in the group already has it.
      buffEffects = this.mergeSumGroups(buffEffects);

      if (buffEffects.length > 0) {
        breakdown.push('Estatísticas Melhoradas!');
        this.apiService.addCharacterActiveSpellEffect(this.character().id, spell.id, buffEffects, chosenEnhancementIndices).subscribe((active_spell_effects) => {
          this.useCharacter.patchCharacterCache(this.id(), { active_spell_effects });
        });
      }
    }

    // 'utility' never resolves anything mechanical, and 'damage'/'debuff'/
    // 'buff' all fall back here too if they genuinely produced nothing
    // (fully negated on resist, or a buff with no coded effects yet).
    if (breakdown.length === 0) {
      breakdown.push('Sem Efeitos Mecânicos!');
    }

    setTimeout(() => {
      clearInterval(dotsInterval);
      this.castResult.set({ total, breakdown });
      this.currentPage.set(4);
    }, this.damageRollMs);
  }

  // Collapses entries sharing a sum_group (see Effect's own comment) into
  // one combined effect (shape of the first entry in the group, value
  // replaced by the sum) — ungrouped entries pass through untouched.
  // sum_group itself is authoring-time-only and doesn't survive the merge;
  // any stack_group the group's first entry carries does (e.g. Armadura
  // Arcana's base effect is listed before its enhancement's, so its own
  // 'armor_bonus' stack_group is what the combined entry keeps).
  private mergeSumGroups(effects: Effect[]): Effect[] {
    const grouped = new Map<string, Effect[]>();
    const ungrouped: Effect[] = [];
    effects.forEach((effect) => {
      if (!effect.sum_group) {
        ungrouped.push(effect);
        return;
      }
      const group = grouped.get(effect.sum_group) ?? [];
      group.push(effect);
      grouped.set(effect.sum_group, group);
    });
    const merged = Array.from(grouped.values()).map((group) => {
      const { sum_group, ...rest } = group[0];
      return { ...rest, value: group.reduce((sum, effect) => sum + Number(effect.value ?? 0), 0) };
    });
    return [...ungrouped, ...merged];
  }

  private pushConditionLine(breakdown: string[], conditionId: number | undefined): void {
    const condition = this.staticRegistry.conditions.find((c) => c.id === conditionId);
    if (condition) {
      breakdown.push(`Causou ${condition.name}`);
    }
  }

  // Sums same-sided dice notations into one ("2d6" + "1d6" + "1d6" ->
  // "4d6") — every damage die a spell can roll shares the same size so
  // far, so this doesn't need to handle a mismatched-sides case yet.
  private combineDiceNotations(notations: string[]): string {
    let count = 0;
    let sides = 0;
    notations.forEach((notation) => {
      const match = notation.match(/^(\d+)d(\d+)$/);
      if (!match) {
        return;
      }
      count += Number(match[1]);
      sides = Number(match[2]);
    });
    return `${count}d${sides}`;
  }

  private signedValue(value: number): string {
    return value >= 0 ? `+${value}` : `${value}`;
  }
}
