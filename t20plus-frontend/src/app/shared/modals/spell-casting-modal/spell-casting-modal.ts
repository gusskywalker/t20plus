import { Component, computed, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Effect, Spell, SpellEnhancement } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { resolveSpellCasterInfo, resolveOtherSourceGrantingPower } from '../../helpers/resolve-spell-caster-info/resolve-spell-caster-info';
import { calculateSpellCd } from '../../helpers/calculators/calculate-spell-cd/calculate-spell-cd';
import { calculateMaxSpellCircle } from '../../helpers/calculators/calculate-max-spell-circle/calculate-max-spell-circle';
import { spendPm } from '../../helpers/spend-pm/spend-pm';
import { restorePm } from '../../helpers/restore-pm/restore-pm';
import { rollDice } from '../../helpers/roll-dice/roll-dice';
import { Checkbox } from '../../inputs/checkbox/checkbox';
import { SONO_SPELL_ID, resolveSonoConditionIds } from './spell-edge-cases/sono';
import { ARMA_DE_JADE_SPELL_ID, applyArmaDeJadeUpgrade } from './spell-edge-cases/arma-de-jade';
import { HERANCA_APRIMORADA_ABENCOADA_POWER_ID, herancaAprimoradaAbencoadaPmDiscount } from './spell-edge-cases/heranca-aprimorada-abencoada';
import { RAIO_ARCANO_SPELL_IDS, RAIO_DIVIDIDO_POWER_ID, raioArcanoDiceNotation, raioArcanoMinPmCost } from './spell-edge-cases/raio-arcano';
import { MAGIA_AMPLIADA_POWER_ID, isMagiaAmpliadaEligible } from './spell-enhancement-resolvers/magia-ampliada';
import { resolveEffectiveSpellUsability } from '../../helpers/resolve-effective-spell-usability/resolve-effective-spell-usability';
import { resolveEffectSentinels } from '../../helpers/resolve-effect-sentinels/resolve-effect-sentinels';
import { matchesSpellAppliesWhen } from '../../helpers/matches-spell-applies-when/matches-spell-applies-when';
import { resolveTag } from '../../helpers/tag-solver/tag-solver';
import { DAMAGE_TYPE_LABELS, SPELL_SCHOOL_LABELS, SPELL_TYPE_LABELS, ACTION_COST_LABELS, RESISTANCE_LABELS } from '../../constants/translation-constants';

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

  // Whole party for the ally-buff picker (page 3, 'buff' spells only) —
  // fetched eagerly regardless of usability, same as any other field
  // initializer query; cheap and cached by campaign id.
  private readonly campaignCharactersQuery = this.useCharacter.campaignCharactersQuery(() => this.character().campaign_id);

  // The caster is only in the pickable list if the spell's own affects
  // says so — ['allies'] alone means everyone BUT the caster (Bênção:
  // "aliados" excludes self); ['caster', 'allies'] together means the
  // whole party including the caster. campaignCharactersQuery is disabled
  // (and its data() empty) whenever the character has no campaign_id, so a
  // caster-outside-a-campaign is never in that fetched list at all — added
  // back explicitly rather than assuming the query already covers it.
  protected readonly campaignCharacters = computed(() => {
    const buffAffects = this.spell().buff_affects ?? [];
    const characters = this.campaignCharactersQuery.data() ?? [];
    if (buffAffects.includes('caster')) {
      const self = this.character();
      return characters.some((c) => c.id === self.id) ? characters : [self, ...characters];
    }
    return characters.filter((c) => c.id !== this.character().id);
  });

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
  // button row to Passou/Falhou. Never set for 'buff'/'utility' spells
  // (see castSpell below), which resolve straight through instead — the
  // page-2 button row only ever renders on pages 1/2, so jumping straight
  // to page 4 (or, for 'buff', to page 3's ally picker) makes it disappear
  // without any extra template check needed.
  protected readonly hasCast = signal(false);

  private castSpell(): void {
    spendPm(this.apiService, this.useCharacter, this.id(), this.character(), this.pmCost());

    // No target to resist in the first place for either of these — skip
    // the Passou/Falhou choice entirely instead of asking a question that
    // doesn't apply.
    const usability = this.effectiveUsability();

    // 'buff' only needs the ally picker (page 3) when it actually has
    // allies to choose from — buff_affects: ['caster'] alone means it
    // always just self-applies, same as before this field existed.
    if (usability === 'buff' && (this.spell().buff_affects ?? []).includes('allies')) {
      this.currentPage.set(3);
      return;
    }

    // 'utility', and a 'buff' with no allies to pick from, have nothing
    // left to ask — resolveCast(false) picks the on_spell_success side of
    // the buff branch's own trigger filter, harmless today since no
    // seeded buff effect sets a trigger at all.
    if (usability === 'utility' || usability === 'buff') {
      this.resolveCast(false);
      return;
    }

    this.hasCast.set(true);
  }

  protected confirmCharacterSelection(): void {
    this.resolveCast(false, Array.from(this.selectedCharacterIds()));
  }

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  protected circleLabel(circle: number): string {
    return `${circle}º Círculo`;
  }

  protected typeLabel(type: string): string {
    return SPELL_TYPE_LABELS[type] ?? type;
  }

  protected schoolLabel(school: string): string {
    return SPELL_SCHOOL_LABELS[school] ?? school;
  }

  protected actionCostLabel(actionCost: string): string {
    return ACTION_COST_LABELS[actionCost] ?? actionCost;
  }

  protected resistanceLabel(resistance: string): string {
    return RESISTANCE_LABELS[resistance] ?? resistance;
  }

  // Which class taught this spell, that class's current level (the PM
  // limit), and its key attribute (the CD attribute) — see
  // resolve-spell-caster-info.ts. Null would mean the spell somehow isn't
  // actually known, which shouldn't happen from how this modal is opened.
  private readonly casterInfo = computed(() => resolveSpellCasterInfo(this.character(), this.spell().id, this.staticRegistry.powers, this.spell().effects));

  protected readonly cd = computed(() => {
    const info = this.casterInfo();
    if (!info) return null;
    const baseCd = calculateSpellCd(this.character(), info.keyAttribute, this.staticRegistry.powers, this.spell().school, this.spell().resistance, this.isDoubleKnown());
    return baseCd + this.checkedEnhancementCdBonus();
  });

  // Is this spell known BOTH for real (spell_ids) AND granted via some
  // other source (other_source_spell_ids) at once — e.g. Pakk granting a
  // spell you also picked normally. Feeds applies_when.spell_double_known
  // generically (O Próprio Sangue's +2 CD) as well as the unconditional
  // -1 PM discount below, which every double-known spell gets regardless
  // of any power.
  private readonly isDoubleKnown = computed(() => {
    const spellId = this.spell().id;
    const levels = this.character().levels ?? [];
    const knownForReal = levels.some((level) => (level.spell_ids ?? []).includes(spellId));
    const grantedViaOtherSource = levels.some((level) => (level.other_source_spell_ids ?? []).includes(spellId));
    return knownForReal && grantedViaOtherSource;
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
    return this.staticRegistry.powers.filter((power) => {
      if (!granted.has(power.id) || power.usability !== 'spell_enhancement') {
        return false;
      }
      // Magia Ampliada's eligibility is a genuine OR across two different
      // Spell fields — applies_when's own fields are always AND'd
      // together generically, so it gets its own small resolver instead
      // (see spell-enhancement-resolvers/magia-ampliada.ts).
      if (power.id === MAGIA_AMPLIADA_POWER_ID) {
        return isMagiaAmpliadaEligible(spell);
      }
      // Raio Dividido only ever applies to Raio Arcano's own 6 variants —
      // not a spell PROPERTY the way school/damage_type/range are (those
      // get reused across many powers with different value sets), just the
      // exact same fixed id list raio-arcano.ts already owns for Raio
      // Poderoso. Reusing that one constant instead of retyping it into
      // applies_when keeps it a single source of truth.
      if (power.id === RAIO_DIVIDIDO_POWER_ID) {
        return RAIO_ARCANO_SPELL_IDS.includes(spell.id);
      }
      // Absent applies_when (or an absent field within it) means always
      // relevant for that check — same "null applies_when = always
      // relevant" convention matches-power-reqs.ts already established
      // for weapon_* — not "matches nothing" (e.g. Magia Discreta has no
      // restriction of its own at all, always eligible).
      return matchesSpellAppliesWhen(power.applies_when, {
        school: spell.school,
        damageType: spell.damage_type,
        actionCost: spell.action_cost,
        hasAffectedArea: spell.info_affected_area !== null,
        range: spell.range,
      });
    });
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
      name: power.name,
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

  // A checked enhancement's own mod_cd effect (e.g. Familiar (Dragão)) —
  // castEnhancements()/enhancementCounts already drive pmCost's checked-cost
  // total the same way; cd needs the same "only counts while checked" read.
  private readonly checkedEnhancementCdBonus = computed(() => {
    const counts = this.enhancementCounts();
    return this.castEnhancements().reduce((sum, enhancement, i) => {
      if ((counts[i] ?? 0) === 0) return sum;
      const bonus = (enhancement.effects ?? [])
        .filter((effect) => effect.tag === 'mod_cd' && effect.op === 'add')
        .reduce((effectSum, effect) => effectSum + Number(effect.value ?? 0), 0);
      return sum + bonus * (counts[i] ?? 0);
    }, 0);
  });

  // mod_spell_pm_cost powers (e.g. Familiar (Diabrete)'s two vessel
  // children) each carry a single applies_when field — the standard
  // AND-everything-present filter (matchesSpellAppliesWhen), same shape as
  // calculateSpellCd's own mod_cd bonus. A rule needing "school X OR damage
  // type Y" is modeled as two separate granted powers rather than one power
  // with OR logic.
  private readonly modSpellPmCostBonus = computed(() => {
    const spell = this.spell();
    const grantedPowerIds = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
    return this.staticRegistry.powers
      .filter((power) => grantedPowerIds.has(power.id) && power.usability === 'passive' && matchesSpellAppliesWhen(power.applies_when, { school: spell.school, damageType: spell.damage_type }))
      .flatMap((power) => power.effects ?? [])
      .filter((effect) => effect.tag === 'mod_spell_pm_cost' && effect.op === 'add')
      .reduce((sum, effect) => sum + Number(effect.value ?? 0), 0);
  });

  // grant_or_reduce_spell_pm_cost_by_1 powers (e.g. Pakk) never both grant AND
  // discount the same cast — the spell only shows up in the Magias list at
  // all (spellGroups in character-main.ts) because it's in spell_ids OR
  // other_source_spell_ids; the -1 only kicks in when it's genuinely known
  // (spell_ids) ON TOP OF the granted copy (other_source_spell_ids).
  private readonly addOrReduceSpellPmCostBonus = computed(() => (this.isDoubleKnown() ? -1 : 0));

  // Herança Aprimorada (Abençoada) — see spell-edge-cases/heranca-
  // aprimorada-abencoada.ts for why this can't be a generic applies_when.
  private readonly herancaAprimoradaAbencoadaBonus = computed(() => {
    const grantedPowerIds = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
    if (!grantedPowerIds.has(HERANCA_APRIMORADA_ABENCOADA_POWER_ID)) {
      return 0;
    }
    return herancaAprimoradaAbencoadaPmDiscount(this.spell(), this.character(), this.staticRegistry.powers);
  });

  // Unfloored total — a -1PM discount (e.g. Tatuagem Mística) can push this
  // to 0 or below even though the actual amount paid never goes under the
  // floor (see pmCost below). enhancementRows' own limit check reads THIS,
  // not pmCost, so a discount that's otherwise fully absorbed by the floor
  // still frees up room for a new enhancement pick instead of being wasted.
  private readonly rawPmCost = computed(() => {
    const base = BASE_PM_COST_BY_CIRCLE[this.spell().circle] ?? 0;
    const counts = this.enhancementCounts();
    const enhancementsTotal = this.castEnhancements().reduce((sum, enhancement, i) => sum + (counts[i] ?? 0) * enhancement.pm_cost, 0);
    return base + enhancementsTotal + this.modSpellPmCostBonus() + this.addOrReduceSpellPmCostBonus() + this.herancaAprimoradaAbencoadaBonus();
  });

  protected readonly pmCost = computed(() => Math.max(raioArcanoMinPmCost(this.spell().id), this.rawPmCost()));

  // The spell's own usability, unless a checked enhancement overrides it
  // for this cast (see resolve-effective-spell-usability.ts) — read by the
  // template (page 3's branch between the ally-buff picker and the Rolando
  // screen) and by castSpell/resolveCast below.
  protected readonly effectiveUsability = computed(() => resolveEffectiveSpellUsability(this.spell(), this.castEnhancements(), this.enhancementCounts()));

  // Page 3 is the ally picker only when there's actually someone besides
  // the caster to pick from — buff_affects: ['caster'] alone never reaches
  // page 3 at all (see castSpell), but this stays the single source of
  // truth the template checks, instead of re-deriving the same condition
  // twice.
  protected readonly showsCharacterPicker = computed(() => this.effectiveUsability() === 'buff' && (this.spell().buff_affects ?? []).includes('allies'));

  // Which campaign characters are currently checked on the ally-buff
  // picker (page 3, 'buff' spells only) — cleared each time the modal
  // opens fresh since it's a plain signal, not persisted anywhere yet
  // (applying the buff to each selection is a later step — see
  // confirmCharacterSelection below).
  protected readonly selectedCharacterIds = signal<ReadonlySet<number>>(new Set());

  protected isCharacterSelected(characterId: number): boolean {
    return this.selectedCharacterIds().has(characterId);
  }

  // Once buff_base_max_targets is reached, every UNselected card disables
  // itself (never hides — same "disable, don't hide" convention
  // enhancementRows/unique_change_group already use) until one is
  // deselected to free up a slot. Null buff_base_max_targets means
  // unlimited — never disabled.
  protected isCharacterCardDisabled(characterId: number): boolean {
    const maxTargets = this.spell().buff_base_max_targets;
    if (maxTargets === null || this.isCharacterSelected(characterId)) {
      return false;
    }
    return this.selectedCharacterIds().size >= maxTargets;
  }

  protected toggleCharacterSelection(characterId: number): void {
    if (this.isCharacterCardDisabled(characterId)) {
      return;
    }
    const next = new Set(this.selectedCharacterIds());
    if (next.has(characterId)) {
      next.delete(characterId);
    } else {
      next.add(characterId);
    }
    this.selectedCharacterIds.set(next);
  }

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

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
    const cost = this.rawPmCost();
    const limit = this.pmLimit();
    const uniqueChangeGroups = this.checkedUniqueChangeGroups();
    const maxCircle = this.casterMaxCircle();
    // A checked truque locks out every other enhancement outright (see
    // toggleEnhancementRow's own comment) — an already-checked row stays
    // clickable so it can still be unchecked.
    const checkedTruqueIndex = enhancements.findIndex((enhancement, i) => enhancement.is_truque && (counts[i] ?? 0) > 0);
    const rows: EnhancementRow[] = [];

    enhancements.forEach((enhancement, enhancementIndex) => {
      const count = counts[enhancementIndex] ?? 0;
      if (count === 0 && enhancement.min_circle !== undefined && enhancement.min_circle > maxCircle) return;
      const label = `[${enhancement.pm_cost}PM] ${enhancement.name ?? enhancement.description}`;
      const rowCount = enhancement.repeatable ? count + 1 : 1;

      for (let rowIndex = 0; rowIndex < rowCount; rowIndex++) {
        const checked = rowIndex < count;
        const wouldExceedLimit = !checked && cost + enhancement.pm_cost > limit;
        const group = enhancement.unique_change_group;
        const conflictsUniqueChange = !checked && !!group && uniqueChangeGroups.has(group) && uniqueChangeGroups.get(group) !== enhancementIndex;
        const missingRequirement = !checked && enhancement.requires_enhancement_index !== undefined && (counts[enhancement.requires_enhancement_index] ?? 0) === 0;
        const exceedsCircleStacks = !checked && !!enhancement.max_stacks_by_max_circle && count >= maxCircle;
        const wrongGod = !checked && enhancement.requires_god_id !== undefined && this.character().god_id !== enhancement.requires_god_id;
        const conflictsTruque = !checked && checkedTruqueIndex !== -1 && checkedTruqueIndex !== enhancementIndex;
        rows.push({
          key: `${enhancementIndex}-${rowIndex}`,
          enhancementIndex,
          rowIndex,
          label,
          checked,
          // Once actually cast, every pick is locked in — even an already-
          // checked row (normally always clickable to uncheck) stops being
          // interactive.
          disabled: this.hasCast() || wouldExceedLimit || conflictsUniqueChange || missingRequirement || exceedsCircleStacks || wrongGod || conflictsTruque,
        });
      }
    });

    // Purely informational — the double-known -1 PM discount
    // (addOrReduceSpellPmCostBonus) already applies automatically, this
    // just surfaces WHICH power caused it (never togglable: an already-
    // baked-in fact, not a pick). enhancementIndex -1 never matches a real
    // castEnhancements() index, so toggleEnhancementRow is a no-op on it
    // regardless — moot anyway since a disabled checkbox never emits.
    if (this.isDoubleKnown()) {
      const grantingPower = resolveOtherSourceGrantingPower(this.character(), this.spell().id, this.staticRegistry.powers);
      if (grantingPower) {
        rows.push({
          key: 'double-known-discount',
          enhancementIndex: -1,
          rowIndex: 0,
          label: `[-1PM] ${grantingPower.name}`,
          checked: true,
          disabled: true,
        });
      }
    }

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

    // Truques "não podem ser usados em conjunto com outros aprimoramentos"
    // (spells-basics.md) — checking one clears every other pick outright.
    if (enhancement.is_truque && checked) {
      this.castEnhancements().forEach((_, i) => {
        if (i !== row.enhancementIndex) {
          counts[i] = 0;
        }
      });
    }

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

  // Page 4's reload-nudge card — only relevant for a buff whose actual
  // targets (see resolveCast's targetCharacterIds) include someone other
  // than the caster. That other character's own sheet has no live
  // subscription to this cast, so their client won't see the new buff
  // until they refresh — this card is the only heads-up they get.
  protected readonly notifiesOtherTargets = signal(false);

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

  // targetCharacterIds defaults to just the caster — the only case where a
  // buff cast reaches resolveCast() with more/other targets is the ally
  // picker's Confirmar button (confirmCharacterSelection above), which
  // passes the actual selection explicitly.
  private resolveCast(resisted: boolean, targetCharacterIds: number[] = [this.character().id]): void {
    const spell = this.spell();
    const effects = spell.effects ?? [];
    const enhancements = this.castEnhancements();
    const counts = this.enhancementCounts();

    // A checked change_usability enhancement (e.g. Bênção's "muda o alvo
    // para 1 cadáver" truque) overrides the spell's own static usability
    // for THIS cast — see resolve-effective-spell-usability.ts.
    const usability = this.effectiveUsability();

    // 'buff'/'utility' never have a target to resist in the first place —
    // no rolling suspense, straight to the result. 'damage'/'debuff' keep
    // the dramatic pause even though the numbers are already known, same
    // as the comment on damageRollMs always explained.
    const instant = usability === 'buff' || usability === 'utility';

    let dotsInterval: ReturnType<typeof setInterval> | undefined;
    if (!instant) {
      this.currentPage.set(3);
      this.castResult.set(null);
      this.rollingDots.set(1);
      dotsInterval = setInterval(() => {
        this.rollingDots.set((this.rollingDots() % 3) + 1);
      }, 500);
    }

    const trigger = resisted ? 'on_spell_fail' : 'on_spell_success';
    const breakdown: string[] = [];
    let total: number | null = null;

    // Sifão de Mana — "pelo menos um inimigo falha" is the same resisted
    // self-report every other spell already uses. Capped by the PM
    // actually spent THIS cast (pmCost()), not current/max PM.
    // spell_circle is a cast-context sentinel (like spell_die/weapon_die),
    // resolved here rather than through the generic resolveEffectSentinels
    // (character-fact sentinels only) — fully tag-driven, so any future
    // power with this exact trigger/tag/op/value shape works for free.
    if (!resisted) {
      const grantedPowerIds = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
      const hasRestorePmOnSuccess = this.staticRegistry.powers.some(
        (power) =>
          grantedPowerIds.has(power.id) &&
          power.usability === 'passive' &&
          (power.effects ?? []).some(
            (effect) => effect.trigger === 'on_spell_success' && effect.tag === 'restore_pm' && effect.op === 'add' && effect.value === 'spell_circle',
          ),
      );
      if (hasRestorePmOnSuccess) {
        restorePm(this.apiService, this.useCharacter, this.id(), this.character(), Math.min(spell.circle, this.pmCost()), this.staticRegistry.powers);
      }
    }

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

    // Purely informational tags (op: 'grant', no number to resolve) on any
    // checked enhancement — a fixed Portuguese line, same "you just have
    // it" role op 'grant' plays elsewhere, unconditional on usability or
    // resist outcome (e.g. Gênese Elemental's summoned minions happen
    // regardless of Passou/Falhou). Add a new tag here whenever a future
    // enhancement just needs to say something happened, nothing more.
    // fluff_target_count is the one exception with an actual value to
    // resolve (e.g. Raio Dividido's "Atingiu X alvos", X = key_attribute)
    // — same sentinel-swap-then-resolve dance passiveSpellDmgEffects uses
    // below, just producing a line instead of a die.
    const informationalTagLines: Record<string, string> = {
      fluff_summon_minions: 'Criou Capangas Elementais',
      fluff_split_area: 'Área dividida em duas',
    };
    const fluffKeyAttribute = this.casterInfo()?.keyAttribute;
    enhancements.forEach((enhancement, i) => {
      if ((counts[i] ?? 0) === 0) {
        return;
      }
      (enhancement.effects ?? [])
        .filter((effect) => effect.op === 'grant')
        .forEach((effect) => {
          if (informationalTagLines[effect.tag]) {
            breakdown.push(informationalTagLines[effect.tag]);
            return;
          }
          if (effect.tag === 'fluff_target_count') {
            const swapped = effect.value === 'key_attribute' && fluffKeyAttribute ? { ...effect, value: fluffKeyAttribute } : effect;
            const [resolved] = resolveEffectSentinels([swapped], this.character(), this.staticRegistry.powers);
            breakdown.push(`Atingiu ${resolved.value} alvos`);
          }
          if (effect.tag === 'fluff_change_target') {
            breakdown.push(`Atingiu ${effect.value}`);
          }
        });
    });

    // usability (the effective one, not necessarily spell.usability itself
    // — see resolveEffectiveSpellUsability above) governs which of these
    // runs — an explicit, authored dispatch key (see Spell.usability's own
    // comment), not derived from what shape of effects happen to be
    // present. A 'damage' spell that also inflicts a condition on success
    // (Adaga Mental) still rolls its dice AND gets its condition line
    // below; a pure 'debuff' (Sono, Leque Cromático) never rolls damage at
    // all, even resisted.
    if (usability === 'damage' || usability === 'debuff') {
      if (usability === 'damage') {
        // A resisted cast only still deals damage if the spell explicitly
        // says so (trigger: on_spell_fail, tag: mod_spell_dmg, op:
        // multiply) — no such entry means fully negated, no damage rolled
        // at all (not just zeroed after rolling). base_spell_dmg is the
        // spell's own inherent damage (always active, no trigger);
        // mod_spell_dmg is anything that modifies that total — a checked
        // enhancement's own added dice, or this fail-only multiplier.
        const failMultiply = resisted ? effects.find((effect) => effect.trigger === 'on_spell_fail' && effect.tag === 'mod_spell_dmg' && effect.op === 'multiply') : undefined;
        if (!resisted || failMultiply) {
          // Only the spell's own dice — its base_spell_dmg plus whatever
          // its OWN enhancements (spell().enhancements) add — combine into
          // ONE roll under "Dados da Magia," same as always. A checked
          // enhancement carrying tag: base_spell_dmg, op: 'set' (e.g.
          // Açoite Flamejante's "muda o dano para 4d6") REPLACES the base
          // value outright instead of stacking — at most one such override
          // can ever be checked, since they always share a
          // unique_change_group with each other. Anything from a genuinely
          // different source — a general power translated into an
          // enhancement row (matchingSpellEnhancementPowers, e.g.
          // Arcanista de Linha de Frente) or an always-on passive (Arcano
          // de Batalha, Herança Aprimorada/Superior) — is NOT the spell's
          // own dice, so it never joins this roll; it gets its own named
          // line below instead, same "Power Name ±value" treatment
          // attack-modal gives every one of its own bonus sources.
          const dmgNotations: string[] = [];
          // Kept separate from dmgNotations itself — spell_die (Arcanista
          // de Linha de Frente) needs to know the BASE die's own size
          // specifically, not the full combined notation, and needs it to
          // already reflect Raio Arcano's own dynamic circle/Poderoso
          // resolution.
          let baseNotation: string | null = null;
          if (RAIO_ARCANO_SPELL_IDS.includes(spell.id)) {
            baseNotation = raioArcanoDiceNotation(this.character(), this.staticRegistry.powers);
          } else {
            const baseOverride = enhancements
              .flatMap((enhancement, i) => ((counts[i] ?? 0) > 0 ? (enhancement.effects ?? []) : []))
              .find((effect) => effect.tag === 'base_spell_dmg' && effect.op === 'set');
            const baseDamage = baseOverride ?? effects.find((effect) => effect.tag === 'base_spell_dmg');
            baseNotation = baseDamage ? String(baseDamage.value) : null;
          }
          if (baseNotation) {
            dmgNotations.push(baseNotation);
          }
          const baseDieSize = Number(baseNotation?.match(/d(\d+)$/)?.[1] ?? 0);

          // Everything at or past this index in castEnhancements() came
          // from matchingSpellEnhancementPowers(), not spell().enhancements
          // itself — the boundary between "the spell's own dice" (lumped
          // above) and "a different power's own dice" (its own line below).
          const nativeEnhancementCount = (spell.enhancements ?? []).length;
          const matchingPowers = this.matchingSpellEnhancementPowers();
          const powerDiceLines: { text: string; total: number; diceCount: number }[] = [];

          let enhancementFlatBonus = 0;

          enhancements.forEach((enhancement, enhancementIndex) => {
            const count = counts[enhancementIndex] ?? 0;
            if (count === 0) {
              return;
            }
            // mod_spell_dmg_flat (e.g. Despedaçar's own "+1d8+2") — a plain
            // number added straight into Dano da Magia's total, never a
            // dice-notation term, so it never touches dmgNotations/rollDice
            // at all. Scoped to the spell's own native enhancements only,
            // same as dmgNotations itself just below.
            if (enhancementIndex < nativeEnhancementCount) {
              enhancementFlatBonus += resolveTag(enhancement.effects ?? [], 'mod_spell_dmg_flat') * count;
            }
            const diceEffects = (enhancement.effects ?? []).filter((effect) => effect.tag === 'mod_spell_dmg' && (effect.op === 'add' || effect.op === 'extra_die'));
            if (diceEffects.length === 0) {
              return;
            }
            const notations = diceEffects.flatMap((effect) => {
              // spell_die (Arcanista de Linha de Frente) — "um dado extra
              // do mesmo tipo": ONE more die matching the base die's own
              // SIZE, not a duplicate of the full (possibly multi-die,
              // e.g. Raio Arcano's own Xd8) base notation — same role
              // weapon_die plays for attack-modal, but sized rather than
              // duplicated since a spell's own base die count varies.
              const notation = effect.op === 'extra_die' && effect.value === 'spell_die' && baseDieSize > 0 ? `1d${baseDieSize}` : String(effect.value);
              return Array.from({ length: count }, () => notation);
            });

            if (enhancementIndex < nativeEnhancementCount) {
              notations.forEach((notation) => dmgNotations.push(notation));
              return;
            }

            const power = matchingPowers[enhancementIndex - nativeEnhancementCount];
            const total = notations.reduce((sum, notation) => sum + rollDice(notation), 0);
            const diceCount = notations.reduce((sum, notation) => sum + Number(notation.match(/^(\d+)d/)?.[1] ?? 0), 0);
            powerDiceLines.push({ text: `${power?.name ?? enhancement.description} (${notations.join('+')}) ${this.signedValue(total)}`, total, diceCount });
          });

          // Passive powers (e.g. Arcano de Batalha) that always add to
          // every damage spell's own dice — unlike the checked enhancements
          // above, these aren't gated by a checkbox, they're just always
          // on. key_attribute resolves through whichever class actually
          // taught THIS spell (casterInfo), not just any caster class the
          // character happens to have, same reasoning calculateSpellCd
          // already follows — swapped for the real attribute code (e.g.
          // 'int') BEFORE resolveEffectSentinels, so the actual number
          // comes from the exact same generic attribute-code resolution
          // attack-modal.ts already uses for mod_dmg add knw, instead of a
          // second hand-rolled calculateStatBonus call here. Then summed via
          // resolveTag (tag-solver.ts), same as attack-modal's own
          // checkedPowerRows mod_dmg breakdown lines.
          const grantedPowerIds = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
          const keyAttribute = this.casterInfo()?.keyAttribute;
          // A resolved sentinel (e.g. key_attribute -> a plain number like
          // "3") is a FLAT bonus, never dice notation — pushing it into
          // dmgNotations used to silently vanish, since combineDiceNotations
          // only accumulates entries matching /^\d+d\d+$/ and skips
          // anything else with no warning. Summed separately (one line per
          // granting power, e.g. "Arcano de Batalha +5", not one lumped
          // total — a character could have more than one such power) and
          // added to the rolled total instead. Filtered on the effect's
          // presence, not on the resolved value being nonzero — same
          // "always its own line, even at +0" treatment attack-modal gives
          // a permanent attribute-based bonus (see dmgAttributeBonus), since
          // key_attribute can legitimately resolve to 0.
          const passiveSpellDmgPowers = this.staticRegistry.powers
            .filter((power) => grantedPowerIds.has(power.id) && power.usability === 'passive' && (power.effects ?? []).some((effect) => effect.tag === 'mod_spell_dmg' && effect.op === 'add'))
            .map((power) => {
              const rawEffects = (power.effects ?? [])
                .filter((effect) => effect.tag === 'mod_spell_dmg' && effect.op === 'add')
                .map((effect) => (effect.value === 'key_attribute' && keyAttribute ? { ...effect, value: keyAttribute } : effect));
              const bonus = resolveTag(resolveEffectSentinels(rawEffects, this.character(), this.staticRegistry.powers), 'mod_spell_dmg');
              return { power, bonus };
            });

          if (dmgNotations.length > 0) {
            const combinedNotation = this.combineDiceNotations(dmgNotations);
            const rolled = rollDice(combinedNotation);
            const flatBonus = resolveTag(effects, 'base_spell_dmg_flat') + enhancementFlatBonus;
            // Herança Aprimorada/Superior (Dracônica)'s "+1 ponto de dano
            // por dado" scales with the FINAL total die count — the
            // spell's own combined dice PLUS any other power's own dice
            // (e.g. Arcanista de Linha de Frente's extra die still counts,
            // it's still a die of the matching damage type) — resolved per
            // granting power (not one lumped "Bônus por Dado" line), same
            // "Power Name ±value" shape as every other bonus here.
            const nativeDiceCount = Number(combinedNotation.match(/^(\d+)d/)?.[1] ?? 0);
            const powerDiceCount = powerDiceLines.reduce((sum, line) => sum + line.diceCount, 0);
            const totalDiceCount = nativeDiceCount + powerDiceCount;
            const passiveSpellDmgPerDiePowers = this.staticRegistry.powers
              .filter(
                (power) =>
                  grantedPowerIds.has(power.id) &&
                  power.usability === 'passive' &&
                  matchesSpellAppliesWhen(power.applies_when, { school: spell.school, damageType: spell.damage_type }) &&
                  (power.effects ?? []).some((effect) => effect.tag === 'mod_spell_dmg_per_die' && effect.op === 'add'),
              )
              .map((power) => ({ power, bonus: resolveTag(power.effects ?? [], 'mod_spell_dmg_per_die') * totalDiceCount }));

            const powerDiceTotal = powerDiceLines.reduce((sum, line) => sum + line.total, 0);
            const passiveFlatBonus = passiveSpellDmgPowers.reduce((sum, { bonus }) => sum + bonus, 0);
            const passivePerDieBonus = passiveSpellDmgPerDiePowers.reduce((sum, { bonus }) => sum + bonus, 0);
            total = rolled + flatBonus + powerDiceTotal + passiveFlatBonus + passivePerDieBonus;
            const spellDamageTypeSuffix = spell.damage_type ? ` (${DAMAGE_TYPE_LABELS[spell.damage_type] ?? spell.damage_type})` : '';
            breakdown.push(
              `Dano da Magia (${combinedNotation}) ${this.signedValue(rolled + flatBonus)}${spellDamageTypeSuffix}`,
              ...powerDiceLines.map((line) => line.text),
              ...passiveSpellDmgPowers.map(({ power, bonus }) => `${power.name} ${this.signedValue(bonus)}`),
              ...passiveSpellDmgPerDiePowers.map(({ power, bonus }) => `${power.name} ${this.signedValue(bonus)}`),
            );
          }

          if (failMultiply && total !== null) {
            total = Math.floor(total * Number(failMultiply.value ?? 1));
          }
        }
      }

      // A checked enhancement's own condition/'override' entry (e.g.
      // Hipnotismo's truque: "em vez de fascinado, o alvo fica pasmo")
      // REPLACES the spell's own base inflict(s) outright instead of
      // stacking with them — same op: 'override' already used by
      // skill_attribute for "this replaces the normal value."
      const inflictOverride = enhancements.flatMap((enhancement, i) =>
        (counts[i] ?? 0) > 0 ? (enhancement.effects ?? []).filter((effect) => effect.trigger === trigger && effect.tag === 'condition' && effect.op === 'override') : [],
      );

      // Only an actual matching inflict entry produces a line — no
      // placeholder for "nothing was inflicted." Checked enhancement-level
      // inflicts (e.g. Leque Cromático's "vulnerável" pick) count alongside
      // the spell's own. Shared by both 'damage' (Adaga Mental) and pure
      // 'debuff' spells.
      const inflictEntries =
        inflictOverride.length > 0
          ? inflictOverride
          : [
              ...effects.filter((effect) => effect.trigger === trigger && effect.tag === 'condition' && effect.op === 'inflict'),
              ...enhancements.flatMap((enhancement, i) =>
                (counts[i] ?? 0) > 0 ? (enhancement.effects ?? []).filter((effect) => effect.trigger === trigger && effect.tag === 'condition' && effect.op === 'inflict') : [],
              ),
            ];
      inflictEntries.forEach((entry) => this.pushConditionLine(breakdown, entry.condition_id, entry.alt_condition_id));

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

    if (usability === 'buff') {
      // Anything the spell/its checked enhancements grant, other than the
      // tags 'damage'/'debuff' already handle above — mod_def, skill, ...
      // — we don't translate into a number, so it's persisted as-is into
      // character_active_spell_effects for getActiveEffects (Defesa/skill/
      // etc. calculators) to pick up, not just shown on this one screen.
      // change_usability is a dispatch-only tag (resolveEffectiveSpellUsability
      // consumes it once, at cast time, to pick this branch in the first
      // place) — not a lasting effect a buffed character carries, so it's
      // excluded here the same as the other one-shot-only tags below,
      // instead of getting persisted and showing up raw on the buff's own
      // details card.
      const knownTags = new Set(['base_spell_dmg', 'mod_spell_dmg', 'condition', 'change_usability']);
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

      // Passive powers (e.g. Chibi-Kabuto) that bump a spell buff's own
      // mod_def contribution — read from the CASTER's granted powers, but
      // only ever applied to the CASTER's own copy of buffEffects ("o
      // bônus... que você recebe" — self-only, not a bonus the caster's
      // familiar hands out to buffed allies too). Computed AFTER
      // mergeSumGroups so a multi-source mod_def (Armadura Arcana's base +
      // its own repeatable pick) only gets bumped once as a single
      // combined value, not once per contributing source.
      const grantedPowerIds = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
      const modSpellDefBonus = this.staticRegistry.powers
        .filter((power) => grantedPowerIds.has(power.id) && power.usability === 'passive')
        .flatMap((power) => power.effects ?? [])
        .filter((effect) => effect.tag === 'mod_spell_def' && effect.op === 'add')
        .reduce((sum, effect) => sum + Number(effect.value ?? 0), 0);
      const casterBuffEffects =
        modSpellDefBonus !== 0
          ? buffEffects.map((effect) => (effect.tag === 'mod_def' ? { ...effect, value: Number(effect.value ?? 0) + modSpellDefBonus } : effect))
          : buffEffects;

      if (buffEffects.length > 0 && targetCharacterIds.length > 0) {
        breakdown.push('Estatísticas Melhoradas');
        this.notifiesOtherTargets.set(targetCharacterIds.some((targetCharacterId) => targetCharacterId !== this.character().id));
        targetCharacterIds.forEach((targetCharacterId) => {
          const targetBuffEffects = targetCharacterId === this.character().id ? casterBuffEffects : buffEffects;
          this.apiService.addCharacterActiveSpellEffect(targetCharacterId, spell.id, targetBuffEffects, chosenEnhancementIndices, this.character().id).subscribe((active_spell_effects) => {
            // The caster's own detail-query cache is keyed by the route's
            // string id (this.id()), not their numeric character id — every
            // other target has no such cache entry to match anyway, so
            // patchCharacterCache's own no-op-when-absent guard covers them.
            const cacheId = targetCharacterId === this.character().id ? this.id() : targetCharacterId;
            this.useCharacter.patchCharacterCache(cacheId, { active_spell_effects });
          });
        });
      }
    }

    // 'utility' never resolves anything mechanical, and 'damage'/'debuff'/
    // 'buff' all fall back here too if they genuinely produced nothing
    // (fully negated on resist, or a buff with no coded effects yet).
    if (breakdown.length === 0) {
      breakdown.push('Sem Efeitos Mecânicos');
    }

    const finish = () => {
      if (dotsInterval !== undefined) {
        clearInterval(dotsInterval);
      }
      this.castResult.set({ total, breakdown });
      this.currentPage.set(4);
    };

    if (instant) {
      finish();
    } else {
      setTimeout(finish, this.damageRollMs);
    }
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

  // altConditionId (see Effect's own comment) renders "Causou A ou B" when
  // the real outcome depends on scene state we don't track — the player
  // self-adjudicates which one actually applies.
  private pushConditionLine(breakdown: string[], conditionId: number | undefined, altConditionId?: number): void {
    const condition = this.staticRegistry.conditions.find((c) => c.id === conditionId);
    if (!condition) {
      return;
    }
    const altCondition = altConditionId !== undefined ? this.staticRegistry.conditions.find((c) => c.id === altConditionId) : undefined;
    breakdown.push(altCondition ? `Causou ${condition.name} ou ${altCondition.name}` : `Causou ${condition.name}`);
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
