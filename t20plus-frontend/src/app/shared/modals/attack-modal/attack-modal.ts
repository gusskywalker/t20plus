import { Component, WritableSignal, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterActiveEffectRow, CharacterHandRow, CharacterInventoryRow, Effect, Power, Weapon } from '../../../api.service';
import { getItemGrantedEffects, getItemGrantedPowers } from '../../helpers/get-item-granted-effects/get-item-granted-effects';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { Checkbox } from '../../inputs/checkbox/checkbox';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';
import { calculateDamage } from '../../helpers/calculators/calculate-damage/calculate-damage';
import { calculateHit } from '../../helpers/calculators/calculate-hit/calculate-hit';
import { calculateMargin } from '../../helpers/calculators/calculate-margin/calculate-margin';
import { calculateMultiplier } from '../../helpers/calculators/calculate-multiplier/calculate-multiplier';
import { calculateWeaponDice } from '../../helpers/calculators/calculate-weapon-dice/calculate-weapon-dice';
import { calculateSkillBonus } from '../../helpers/calculators/calculate-skill-bonus/calculate-skill-bonus';
import { calculateStatBonus } from '../../helpers/calculators/calculate-stat-bonus/calculate-stat-bonus';
import { calculateAttributeDmg } from '../../helpers/calculators/calculate-attribute-dmg/calculate-attribute-dmg';
import { resolveGolpePessoalEffects } from '../../helpers/golpe-pessoal-solver/golpe-pessoal-solver';
import { resolveEffectSentinels } from '../../helpers/resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../../helpers/tag-solver/tag-solver';
import { rollDice } from '../../helpers/roll-dice/roll-dice';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { spendPm } from '../../helpers/spend-pm/spend-pm';
import { spendPv } from '../../helpers/spend-pv/spend-pv';
import { extraDieStepIndex, stepDieNotation, stepExtraDie } from '../../helpers/step-extra-die/step-extra-die';

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
  imports: [Checkbox, SearchableDropdown],
  templateUrl: './attack-modal.html',
  styleUrl: './attack-modal.scss',
})
export class AttackModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason golpe-pessoal-modal needs its own:
  // patchCharacterCache's key must match whatever characterQuery() was
  // built with (character-main.ts's own string id), not the numeric
  // Character.id.
  id = input.required<string>();
  cancel = output<void>();

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  // Explicit screen the modal is on — 1: pick a hand, 2: carousel + power
  // checklist, 3: rolled, breakdown shown, 4: damage. Set to 1 by default
  // (modal opens on step 1), set to 2 in selectHand(), set to 3 in roll(),
  // set to 4 in markPassed().
  protected readonly currentStep = signal<1 | 2 | 3 | 4>(1);

  // "Rolando." / "Rolando.." / "Rolando..." — cycles while step 4's damage
  // total is held back (same 2s hold as the hit roll's carousel spin, but
  // there's no per-die animation to wait on here: some builds roll 100+
  // dice, so the total is always computed instantly and just held behind
  // this timer, same as calculateHit's reveal).
  private readonly damageRollMs = 2000;
  protected readonly rollingDots = signal(1);
  protected rollingText(): string {
    return 'Rolando' + '.'.repeat(this.rollingDots());
  }

  protected readonly damageResult = signal<number | null>(null);
  // One line per term ("Dados da Arma +23", "Ataque Poderoso +5") — Dados
  // is a lump sum, not per-die, on purpose (see rollDice). `critical` flags
  // the weapon-die line red with "(Crítico Xn!)" prepended, n being
  // calculateMultiplier's result (attack-modal.html) — never true for
  // extra_die/power lines, only the weapon's own die scales by it.
  protected readonly damageBreakdown = signal<{ text: string; critical: boolean }[] | null>(null);

  // Passou advances to step 4 and immediately starts the damage roll.
  // Falhou has no method of its own — it's just Cancelar under a
  // different label in step 3 (see attack-modal.html).
  protected markPassed(): void {
    const weapon = this.selectedWeapon();
    if (!weapon) {
      return; // markPassed() is only reachable after selectHand() picked one
    }

    this.currentStep.set(4);
    this.damageResult.set(null);
    this.damageBreakdown.set(null);
    this.rollingDots.set(1);

    const dotsInterval = setInterval(() => {
      this.rollingDots.set((this.rollingDots() % 3) + 1);
    }, 500);

    const checkedPowerRows = this.attackPowerRows().filter((row) => this.isPowerChecked(row.effect.id));
    // Ataque Especial's dmg-side share (if any) rides along as an ordinary
    // mod_dmg effect — same flat treatment as any other checked power's
    // bonus, not scaled by the crit multiplier, which only touches the
    // weapon's own die.
    const ataqueEspecialEffects = this.ataqueEspecialEffects();
    const checkedEffects = [...checkedPowerRows.flatMap((row) => row.power.effects ?? []), ...ataqueEspecialEffects, ...this.selectedWeaponGrantedEffects()];
    const ataqueEspecialDmg = resolveTag(ataqueEspecialEffects, 'mod_dmg');

    // Weapon's own die — stepped by any checked weapon_step_increase
    // (calculateWeaponDice), THEN the crit multiplier touches the result.
    const critical = this.isCriticalStrike();
    const weaponDice = calculateWeaponDice(weapon, checkedEffects, this.selectedWeaponInventoryRow()?.weapon_size ?? 0);
    const rawDiceTotal = rollDice(weaponDice);
    const multiplier = calculateMultiplier(weapon, checkedEffects);
    const diceTotal = critical ? rawDiceTotal * multiplier : rawDiceTotal;

    // Whichever Marca da Presa tier is currently checked, if any — feeds
    // the 'marca_da_presa_die' sentinel below (e.g. Inimigo de (Criatura)),
    // resolved fresh per roll so it always matches whatever tier the
    // character actually has checked, not a specific baked-in die size.
    const marcaDaPresaRow = checkedPowerRows.find((row) => this.marcaDaPresaPowerIds.includes(row.power.id));
    const marcaDaPresaDice = marcaDaPresaRow
      ? String((marcaDaPresaRow.power.effects ?? []).find((e) => e.tag === 'mod_dmg' && e.op === 'extra_die')?.value ?? '0')
      : '0';

    // extra_die entries are rolled separately from flat add/set mod_dmg —
    // resolveTag (tag-solver.ts) only sums add/set/override, so it already
    // ignores extra_die entries on its own. One line per power (not lumped
    // into a single "Dados Extras" bucket) so e.g. Elemental and Destruidor
    // read as their own named bonuses — never scaled by the crit
    // multiplier, unlike the weapon's own die. weapon_die (Brutal) rolls an
    // additional die matching the already-stepped weaponDice, not the raw
    // base_dmg.
    // marca_da_presa_die (Inimigo de (Criatura)) reuses whichever Marca da
    // Presa tier is checked, same sentinel shape as weapon_die.
    //
    // all_die_step_increase (Primeiro Sangue) steps EVERY damage die, but
    // weapon_die-sourced entries already get it folded into weaponDice
    // itself (calculate-weapon-dice.ts sums it alongside weapon_step_increase)
    // — applying it again here would double it. Every other, genuinely
    // independent die (fixed value, marca_da_presa_die, die_steps_per_levels)
    // gets its own separate step-up, applied last, after its own resolution.
    const allDieStepIncrease = resolveTag(checkedEffects, 'all_die_step_increase');
    const allExtraDieEntries = checkedPowerRows.flatMap((row) =>
      (row.power.effects ?? [])
        .filter((e) => e.tag === 'mod_dmg' && e.op === 'extra_die')
        .map((effect) => {
          if (effect.value === 'weapon_die') {
            return { row, effect, notation: weaponDice };
          }
          const baseNotation =
            effect.value === 'marca_da_presa_die'
              ? marcaDaPresaDice
              : effect.die_steps_per_levels
                ? stepExtraDie(String(effect.value), this.character().level, effect.die_steps_per_levels)
                : String(effect.value);
          const notation = stepDieNotation(baseNotation, allDieStepIncrease);
          return { row, effect, notation };
        }),
    );

    // Same stack_group convention as resolveTag (tag-solver.ts) — entries
    // sharing a stack_group don't stack, only the bigger die step survives
    // (e.g. Escaramuça's +1d8 vs Escaramuça Superior's +1d12). Compared by
    // DAMAGE_STEPS position (extraDieStepIndex), not by rolling/averaging.
    const bestByStackGroup = new Map<string, (typeof allExtraDieEntries)[number]>();
    for (const entry of allExtraDieEntries) {
      const group = entry.effect.stack_group;
      if (!group) {
        continue;
      }
      const current = bestByStackGroup.get(group);
      if (!current || extraDieStepIndex(entry.notation) > extraDieStepIndex(current.notation)) {
        bestByStackGroup.set(group, entry);
      }
    }
    const survivingExtraDieEntries = allExtraDieEntries.filter(
      (entry) => !entry.effect.stack_group || bestByStackGroup.get(entry.effect.stack_group) === entry,
    );

    const extraDieLines = checkedPowerRows
      .map((row) => {
        const rowEntries = survivingExtraDieEntries.filter((entry) => entry.row === row);
        if (rowEntries.length === 0) {
          return null;
        }
        const rowTotal = rowEntries.reduce((sum, entry) => {
          console.log(`[extra_die] ${row.power.name}: rolling ${entry.notation}`);
          return sum + rollDice(entry.notation);
        }, 0);
        return { text: `${row.power.name} ${this.signedValue(rowTotal)}`, critical: false, rowTotal };
      })
      .filter((line): line is { text: string; critical: boolean; rowTotal: number } => line !== null);
    const extraDieTotal = extraDieLines.reduce((sum, line) => sum + line.rowTotal, 0);

    // Which attribute (if any) adds to this weapon's damage — melee/thrown
    // default to Força, fired defaults to none, either overridable by a
    // checked mod_dmg_attribute effect (see calculate-attribute-dmg.ts).
    // Always its own line right after Dados da Arma when an attribute
    // applies, even at +0 — same "always shown, not filtered by nonzero"
    // treatment as step 3's skill line, since it's a permanent weapon-purpose
    // fact, not a conditional checked bonus.
    const dmgAttribute = calculateAttributeDmg(weapon, checkedEffects);
    const dmgAttributeBonus = dmgAttribute ? calculateStatBonus(this.character(), dmgAttribute, this.staticRegistry.powers) : 0;

    const total = calculateDamage(diceTotal, checkedEffects) + extraDieTotal + dmgAttributeBonus;

    // Informational only — never touches `total`. value: "<meters>m/
    // <amount><unit>", computed against the FINAL damage total (Impactante:
    // "1,5m para cada 10 pontos de dano causado"). Only the 'damage' unit
    // is handled for now — a future push_distance keyed on something else
    // (e.g. PM spent) needs its own branch here when it actually shows up.
    const pushLines = checkedPowerRows
      .flatMap((row) => row.power.effects ?? [])
      .filter((e) => e.tag === 'push_distance')
      .map((e) => /^([\d.]+)m\/([\d.]+)damage$/.exec(String(e.value)))
      .filter((match): match is RegExpExecArray => match !== null)
      .map((match) => {
        const meters = Number(match[1]);
        const perDamage = Number(match[2]);
        const pushed = Math.floor(total / perDamage) * meters;
        return { text: `Empurrar ${pushed}m`, critical: false };
      });

    // Informational only, same as pushLines — no target RD is tracked, so
    // this just tells the player how much to subtract themselves before
    // applying whatever RD the target actually has. Flat points and
    // percent sources are each summed into their own line — one line for
    // "Ignorar N RD", one for "Ignorar N% RD" — since mixing those two
    // scales into a single number would be meaningless.
    const ignoreDrEffects = checkedPowerRows.flatMap((row) => row.power.effects ?? []).filter((e) => e.tag === 'ignore_dr');
    const ignoreDrFlat = ignoreDrEffects.filter((e) => typeof e.value === 'number').reduce((sum, e) => sum + Number(e.value), 0);
    const ignoreDrPercent = ignoreDrEffects
      .filter((e) => typeof e.value === 'string' && e.value.endsWith('%'))
      .reduce((sum, e) => sum + Number(String(e.value).slice(0, -1)), 0);
    const ignoreDrLines = [
      ...(ignoreDrFlat > 0 ? [{ text: `Ignorar ${ignoreDrFlat} RD`, critical: false }] : []),
      ...(ignoreDrPercent > 0 ? [{ text: `Ignorar ${ignoreDrPercent}% RD`, critical: false }] : []),
    ];

    // Informational only, same as ignoreDrLines — grant-only, no value to
    // sum, just a reminder line when any checked source has it.
    const hasIgnoreLefeuCriticalImmunity = checkedPowerRows
      .flatMap((row) => row.power.effects ?? [])
      .some((e) => e.tag === 'ignore_lefeu_critical_immunity');
    const ignoreLefeuCriticalImmunityLines = hasIgnoreLefeuCriticalImmunity ? [{ text: 'Ignora imunidade a crítico de lefeu', critical: false }] : [];

    const breakdown = [
      { text: `${critical ? `(X${multiplier}!) ` : ''}Dados da Arma ${this.signedValue(diceTotal)}`, critical },
      ...(dmgAttribute ? [{ text: `${this.attributeLabel(dmgAttribute)} ${this.signedValue(dmgAttributeBonus)}`, critical: false }] : []),
      ...extraDieLines.map(({ text, critical }) => ({ text, critical })),
      // Only powers that actually carry a flat (add/set) mod_dmg entry —
      // extra_die already has its own line above, and a checked
      // mod_hit-only power already showed up in step 3's breakdown, so
      // neither belongs here with a misleading +0.
      ...checkedPowerRows
        .filter((row) => (row.power.effects ?? []).some((e) => e.tag === 'mod_dmg' && e.op !== 'extra_die'))
        .map((row) => ({ text: `${row.power.name} ${this.signedValue(resolveTag(row.power.effects ?? [], 'mod_dmg'))}`, critical: false })),
      ...(ataqueEspecialDmg !== 0 ? [{ text: `Ataque Especial ${this.signedValue(ataqueEspecialDmg)}`, critical: false }] : []),
      ...pushLines,
      ...ignoreDrLines,
      ...ignoreLefeuCriticalImmunityLines,
    ];

    setTimeout(() => {
      clearInterval(dotsInterval);
      this.damageResult.set(total);
      this.damageBreakdown.set(breakdown);
    }, this.damageRollMs);
  }

  // Step 1: which weapon this specific attack uses — undefined means no
  // choice made yet (template shows the hand-picker buttons instead of the
  // carousel), a Weapon means that hand's item (Desarmado resolves to the
  // synthetic Unarmed weapon row — see resolveHandWeapon — so there's no
  // separate null case to special-case past this point).
  protected readonly selectedWeapon = signal<Weapon | undefined>(undefined);

  // The real owned instance behind selectedWeapon — undefined for the
  // synthetic Unarmed fallback (nothing to own). This is what actually
  // carries improvement_ids/enchantment_ids/weapon_size, none of which live
  // on the catalog Weapon row itself (see get-item-granted-effects.ts).
  protected readonly selectedWeaponInventoryRow = signal<CharacterInventoryRow | undefined>(undefined);

  // The selected weapon's own granted effects (its improvement_ids/
  // enchantment_ids resolved through their granted powers) — joins the same
  // checkedEffects pool every checked power/Ataque Especial effect already
  // feeds, everywhere that pool gets built (currentMargin/roll/markPassed/
  // hasAdvantage). Never merged with character.active_effects — an item-
  // granted power only applies while this specific physical weapon is the
  // one selected (see tag-system.md's item-vs-character resolution split).
  // type is always null — weapons never branch on when_type, only armor/
  // general_item do.
  private selectedWeaponGrantedEffects(): Effect[] {
    const inventoryRow = this.selectedWeaponInventoryRow();
    if (!inventoryRow) {
      return [];
    }
    return getItemGrantedEffects(inventoryRow, this.staticRegistry.itemImprovements, this.staticRegistry.itemEnchantments, this.staticRegistry.powers, null);
  }

  private readonly handOrder: CharacterHandRow['name'][] = ['hand_1', 'hand_2', 'hand_3', 'hand_4'];

  // Weapons.id 4 — synthetic, not a real owned item (see WeaponSeeder).
  private readonly unarmedWeaponId = 4;

  // Short hand-tag prefixed onto the button label so two buttons with the
  // same weapon name (e.g. two Adaga) or two Desarmado hands stay tellable
  // apart — D/E for hand_1/hand_2 mirrors character-main.ts's Mão
  // Direita/Esquerda convention, 3/4 just numbered like there too.
  private readonly handPrefixes: Record<CharacterHandRow['name'], string> = {
    hand_1: '[D]',
    hand_2: '[E]',
    hand_3: '[3]',
    hand_4: '[4]',
  };

  // A two_hand-grip weapon in hand_1 occupies hand_2 too, so hand_2 is
  // hidden from the picker whenever that's the case — no need to inspect
  // hand_2's own contents for it (see claude-stuff/tag-system.md).
  protected handOptions(): { name: CharacterHandRow['name']; label: string; weapon: Weapon; inventoryRow: CharacterInventoryRow | undefined }[] {
    const character = this.character();
    const handsByName = new Map((character.hands ?? []).map((hand) => [hand.name, hand]));
    const hand1 = handsByName.get('hand_1');
    const hand1Resolved = hand1 ? this.resolveHandWeapon(character, hand1) : undefined;
    const hideHand2 = hand1Resolved?.weapon?.grip === 'two_hand';

    const options: { name: CharacterHandRow['name']; label: string; weapon: Weapon; inventoryRow: CharacterInventoryRow | undefined }[] = [];
    for (const name of this.handOrder) {
      if (name === 'hand_2' && hideHand2) {
        continue;
      }
      const hand = handsByName.get(name);
      if (!hand?.enabled) {
        continue;
      }
      const resolved = name === 'hand_1' ? hand1Resolved : this.resolveHandWeapon(character, hand);
      if (!resolved?.weapon) {
        continue; // static weapon data not loaded yet — shouldn't happen in practice
      }
      // No prefix on the two_hand case — it's the only option shown (hand_2
      // is hidden above), so there's no hand to disambiguate.
      const label = resolved.weapon.grip === 'two_hand' ? resolved.weapon.name : `${this.handPrefixes[name]} ${resolved.weapon.name}`;
      options.push({ name, label, weapon: resolved.weapon, inventoryRow: resolved.inventoryRow });
    }
    return options;
  }

  // Only resolves actual weapons — a shield (or anything else, or an empty
  // hand) falls back to the synthetic Unarmed weapon, same as Desarmado.
  // inventoryRow is undefined for that fallback (nothing owned to carry
  // improvements/enchantments/weapon_size).
  private resolveHandWeapon(character: Character, hand: CharacterHandRow): { weapon: Weapon | undefined; inventoryRow: CharacterInventoryRow | undefined } {
    const inventoryId = hand.inventory_ids?.[0];
    const inventoryRow = inventoryId !== undefined ? (character.inventory ?? []).find((row) => row.id === inventoryId) : undefined;
    const weapon =
      inventoryRow && inventoryRow.item_type === 'weapon'
        ? this.staticRegistry.weapons.find((w) => w.id === inventoryRow.item_id)
        : undefined;
    if (weapon) {
      return { weapon, inventoryRow };
    }
    return { weapon: this.staticRegistry.weapons.find((w) => w.id === this.unarmedWeaponId), inventoryRow: undefined };
  }

  protected selectHand(weapon: Weapon, inventoryRow: CharacterInventoryRow | undefined): void {
    this.selectedWeapon.set(weapon);
    this.selectedWeaponInventoryRow.set(inventoryRow);
    const defaultChecked = this.attackPowerRows()
      .filter((row) => row.power.default_checked)
      .map((row) => row.effect.id);
    this.checkedPowerIds.set(new Set(defaultChecked));
    this.rollResult.set(null);
    this.rollBreakdown.set(null);
    this.isCriticalStrike.set(false);
    // Highest tier the character actually has, or null (Não usar) if none.
    this.selectedAtaqueEspecialId.set(this.ataqueEspecialOptions()[0]?.id ?? null);
    this.ataqueEspecialMode.set('hit');
    this.currentStep.set(2);
  }

  // Ataque Especial (power ids 1-5, PowerSeeder.php) — every tier the
  // character has ever unlocked stays granted (class_granted, one row per
  // tier reached), so a high-level character can have several of these ids
  // as separate active_effects at once. Hardcoded id list on purpose —
  // that's the actual shape of this power in the data, not something
  // derivable from a shared tag (other powers could use mod_hit_or_dmg
  // too, that's not what identifies Ataque Especial itself).
  private readonly ataqueEspecialPowerIds = [1, 2, 3, 4, 5];

  // Marca da Presa's 5 tiers (ClassPowerSeeder.php ids 196-200) — same
  // hardcoded-id convention as ataqueEspecialPowerIds above, for powers
  // like Inimigo de (Criatura) whose own extra_die value is the sentinel
  // 'marca_da_presa_die' (see markPassed): "reuse whatever tier is
  // currently checked," rather than a specific baked-in die size, since a
  // leveled Caçador holds every tier at once and only one is ever checked
  // per roll.
  private readonly marcaDaPresaPowerIds = [196, 197, 198, 199, 200];

  // Espreitar (Passiva) — power_granted child of Espreitar (id 225, see
  // ClassPowerSeeder.php). Never appears as a checkbox (passive powers are
  // excluded from attackPowerRows entirely) — its bonus is computed here
  // in roll() instead: whichever Marca da Presa tier is checked's own
  // pm_cost, doubled if any Inimigo de (Criatura) id is also checked.
  private readonly espreitarPassivaPowerId = 226;
  private readonly inimigoDeCriaturaPowerIds = [219, 220, 221, 222, 223, 224];

  // Ponto Fraco (id 233) — same bespoke shape as Espreitar (Passiva): a
  // passive power, never a checkbox, whose bonus only counts when the
  // character has it AND a Marca da Presa tier is checked this roll,
  // doubled if an Inimigo de (Criatura) is also checked. Returned as a
  // synthetic mod_margin effect (widening = negative, see tag-library.md)
  // so it flows through calculateMargin the normal way instead of needing
  // its own bespoke margin calculation at each call site.
  private readonly pontoFracoPowerId = 233;
  private pontoFracoMarginEffects(checkedPowerRows: { power: Power }[]): Effect[] {
    const hasPontoFraco = (this.character().active_effects ?? []).some((e) => e.power_id === this.pontoFracoPowerId);
    const marcaDaPresaRow = checkedPowerRows.find((row) => this.marcaDaPresaPowerIds.includes(row.power.id));
    if (!hasPontoFraco || !marcaDaPresaRow) {
      return [];
    }
    const inimigoChecked = checkedPowerRows.some((row) => this.inimigoDeCriaturaPowerIds.includes(row.power.id));
    return [{ tag: 'mod_margin', op: 'add', value: -2 * (inimigoChecked ? 2 : 1) }];
  }

  // Only the tiers this character actually has granted — highest bonus
  // first, which selectHand() uses as the default pick. id/name match
  // SearchableDropdown's expected item shape.
  protected ataqueEspecialOptions(): { id: number; name: string; bonus: number }[] {
    const grantedIds = new Set((this.character().active_effects ?? []).map((e) => e.power_id));
    return this.ataqueEspecialPowerIds
      .filter((id) => grantedIds.has(id))
      .map((id) => {
        const power = this.staticRegistry.powers.find((p) => p.id === id);
        return { id, name: `[${power?.pm_cost ?? 0}PM] ${power?.name ?? ''}`, bonus: resolveTag(power?.effects ?? [], 'mod_hit_or_dmg') };
      })
      .sort((a, b) => b.bonus - a.bonus);
  }

  // null = the "Ataque Especial" checkbox is unchecked (not using it).
  protected readonly selectedAtaqueEspecialId = signal<number | null>(null);
  protected readonly ataqueEspecialMode = signal<'hit' | 'dmg' | 'split'>('hit');

  // id/name match SearchableDropdown's expected item shape — static, unlike
  // ataqueEspecialOptions() which depends on the character's granted tiers.
  protected readonly ataqueEspecialModeOptions: { id: 'hit' | 'dmg' | 'split'; name: string }[] = [
    { id: 'hit', name: 'Ataque' },
    { id: 'dmg', name: 'Dano' },
    { id: 'split', name: 'Dividir' },
  ];

  // The checkbox itself just toggles selectedAtaqueEspecialId between null
  // and the default (highest) tier — no separate enabled flag needed, null
  // already means "not using it" everywhere downstream.
  protected toggleAtaqueEspecial(checked: boolean): void {
    this.selectedAtaqueEspecialId.set(checked ? (this.ataqueEspecialOptions()[0]?.id ?? null) : null);
  }

  // A checked ability's real PM cost — its own pm_cost reduced by the
  // selected weapon's mod_pm_cost_each (e.g. Madeira Tollon's -1), only for
  // abilities costing more than 1 PM to begin with (never reduces a 1-PM
  // ability to free), floored at 1 (never goes below that either). Shared
  // by checkedPmCost() and pmCostEachInfoLine() so both agree on the exact
  // same number. Callers only ever pass an already-checked row's cost — the
  // "only when checked" half of the rule is enforced by that filtering,
  // not here.
  private costedAbilityPmCost(baseCost: number): number {
    if (baseCost <= 1) {
      return baseCost;
    }
    const reduction = resolveTag(this.selectedWeaponGrantedEffects(), 'mod_pm_cost_each');
    return Math.max(1, baseCost + reduction);
  }

  // Every checked power/golpe row's own pm_cost, plus the selected Ataque
  // Especial tier's — same "spent regardless of whether the attack
  // connects" rule as the checklist comment above, so this runs once in
  // roll(), not markPassed().
  private checkedPmCost(): number {
    const checkedRowsCost = this.attackPowerRows()
      .filter((row) => this.isPowerChecked(row.effect.id))
      .reduce((sum, row) => sum + this.costedAbilityPmCost(row.power.pm_cost ?? 0), 0);

    const ataqueEspecialId = this.selectedAtaqueEspecialId();
    const ataqueEspecialBaseCost = ataqueEspecialId === null ? 0 : (this.staticRegistry.powers.find((p) => p.id === ataqueEspecialId)?.pm_cost ?? 0);

    return checkedRowsCost + this.costedAbilityPmCost(ataqueEspecialBaseCost);
  }

  // Informational row (step 2) for a weapon carrying mod_pm_cost_each
  // (Madeira Tollon) — shows the total PM being saved on whatever's
  // currently checked, as a permanently-checked/disabled checkbox (nothing
  // to toggle, it's a passive property of the weapon). null hides the row
  // entirely when the selected weapon doesn't grant this tag at all.
  protected pmCostEachInfoLine(): { label: string; savedAmount: number } | null {
    const inventoryRow = this.selectedWeaponInventoryRow();
    if (!inventoryRow) {
      return null;
    }
    const grantedPowers = getItemGrantedPowers(inventoryRow, this.staticRegistry.itemImprovements, this.staticRegistry.itemEnchantments, this.staticRegistry.powers, null);
    const sourcePower = grantedPowers.find((p) => (p.effects ?? []).some((e) => e.tag === 'mod_pm_cost_each'));
    if (!sourcePower) {
      return null;
    }

    const checkedRowsSaved = this.attackPowerRows()
      .filter((row) => this.isPowerChecked(row.effect.id))
      .reduce((sum, row) => {
        const baseCost = row.power.pm_cost ?? 0;
        return sum + (baseCost - this.costedAbilityPmCost(baseCost));
      }, 0);

    const ataqueEspecialId = this.selectedAtaqueEspecialId();
    const ataqueEspecialBaseCost = ataqueEspecialId === null ? 0 : (this.staticRegistry.powers.find((p) => p.id === ataqueEspecialId)?.pm_cost ?? 0);
    const ataqueEspecialSaved = ataqueEspecialBaseCost - this.costedAbilityPmCost(ataqueEspecialBaseCost);

    return { label: sourcePower.name, savedAmount: checkedRowsSaved + ataqueEspecialSaved };
  }

  // Live threat-range readout shown above Rolar — recomputes from
  // whatever's currently checked, same pool roll()'s own isCriticalStrike
  // uses once Rolar is actually pressed. 20 (never a crit) when no weapon
  // is selected yet, though the template only renders this at step 2 where
  // a weapon is already guaranteed.
  protected currentMargin(): number {
    const weapon = this.selectedWeapon();
    if (!weapon) {
      return 20;
    }
    const checkedPowerRows = this.attackPowerRows().filter((row) => this.isPowerChecked(row.effect.id));
    const checkedEffects = [
      ...checkedPowerRows.flatMap((row) => row.power.effects ?? []),
      ...this.ataqueEspecialEffects(),
      ...this.selectedWeaponGrantedEffects(),
      ...this.pontoFracoMarginEffects(checkedPowerRows),
    ];
    return calculateMargin(weapon, checkedEffects);
  }

  private ataqueEspecialBonus(): number {
    const id = this.selectedAtaqueEspecialId();
    if (id === null) {
      return 0;
    }
    return this.ataqueEspecialOptions().find((option) => option.id === id)?.bonus ?? 0;
  }

  // Turns the tier + split choice into ordinary mod_hit/mod_dmg effect
  // entries — feeds straight into the same checkedEffects list every other
  // checked power already goes through (resolveTag/calculateHit/
  // calculateDamage), instead of two bespoke bonus-number methods
  // duplicating that math in both roll() and markPassed().
  private ataqueEspecialEffects(): Effect[] {
    const bonus = this.ataqueEspecialBonus();
    if (bonus === 0) {
      return [];
    }
    const mode = this.ataqueEspecialMode();
    const effects: Effect[] = [];
    if (mode !== 'dmg') {
      effects.push({ tag: 'mod_hit', op: 'add', value: mode === 'split' ? bonus / 2 : bonus });
    }
    if (mode !== 'hit') {
      effects.push({ tag: 'mod_dmg', op: 'add', value: mode === 'split' ? bonus / 2 : bonus });
    }
    return effects;
  }

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

  // Second carousel — only shown/rolled when a checked power grants
  // advantage on the hit roll (see hasAdvantage below). Duplicated state
  // rather than a shared/generalized carousel, same convention as every
  // other section in this codebase (duplicate over nest/share).
  protected readonly carousel2Numbers = signal<number[]>(this.buildCarouselLoops(4));
  protected readonly carousel2Index = signal(this.carouselStartIndex);

  protected carousel2Offset(): number {
    return -(this.carousel2Index() * this.carouselItemWidth) + this.carouselViewportWidth / 2 - this.carouselItemWidth / 2;
  }

  protected carousel2Distance(index: number): number {
    return Math.abs(index - this.carousel2Index());
  }

  // Live, not a snapshot — reacts immediately as the player checks/
  // unchecks powers in step 2, same as ataqueEspecialOptions() etc.
  // Ataque Especial's own effects are included for completeness, even
  // though nothing grants advantage through it today.
  protected hasAdvantage(): boolean {
    const checkedEffects = [
      ...this.attackPowerRows()
        .filter((row) => this.isPowerChecked(row.effect.id))
        .flatMap((row) => row.power.effects ?? []),
      ...this.ataqueEspecialEffects(),
      ...this.selectedWeaponGrantedEffects(),
    ];
    return checkedEffects.some((effect) => effect.tag === 'advantage' && effect.scope === 'hit');
  }

  // Luta (melee) vs Pontaria (thrown/fired) — see weapon-rules.md. Unarmed's
  // own purpose is 'melee' so it naturally tests Luta too, no special case.
  private readonly meleeSkillId = 19; // Luta
  private readonly rangedSkillId = 25; // Pontaria

  // Mirrors .carousel-track's `transition: transform 1.4s ...` — kept in
  // sync manually, same deal as carouselItemWidth/carouselViewportWidth
  // above. The total is computed immediately but only revealed once the
  // spin animation visually lands, not while it's still spinning.
  private readonly carouselTransitionMs = 1400;

  // The two raw d20 results when advantage applies — null/null when there's
  // only one roll (no advantage) or before roll() has run. Only used to
  // dim whichever carousel landed on the lesser value (isCarousel1Loser/
  // isCarousel2Loser) — a tie dims neither.
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
  // One line per term that fed the total ("d20 +14", "Luta +3", "Ataque
  // Poderoso -2") — built alongside rollResult, revealed at the same time.
  protected readonly rollBreakdown = signal<string[] | null>(null);
  // Raw d20 result at or above calculateMargin's threat range (weapon's own
  // base_margin plus any checked mod_margin). No natural-20 special case
  // needed: margin is never above 20, so 20 always already qualifies.
  protected readonly isCriticalStrike = signal(false);

  // Shared by both carousels — spins whichever (numbers, index) pair is
  // passed in to land on `result`. Extracted since advantage needs the
  // exact same spin math run twice, not because carousels 1/2 share any
  // rendering (their template blocks stay fully duplicated).
  private spinCarouselTo(numbers: WritableSignal<number[]>, index: WritableSignal<number>, result: number): void {
    const currentValue = (index() % 20) + 1;
    const stepsToResult = ((result - currentValue) + 20) % 20;
    const spinLoops = 3; // purely visual — how many full loops it spins before landing
    const newIndex = index() + spinLoops * 20 + stepsToResult;

    // Extend the strip so real items exist all the way to the landing spot.
    const strip = numbers();
    while (strip.length <= newIndex + 20) {
      strip.push(...this.buildCarouselLoops(1));
    }
    numbers.set([...strip]);

    index.set(newIndex);
  }

  protected roll(): void {
    const weapon = this.selectedWeapon();
    if (!weapon) {
      return; // roll() is only reachable after selectHand() picked one
    }

    spendPm(this.apiService, this.useCharacter, this.id(), this.character(), this.checkedPmCost());

    this.currentStep.set(3);
    this.rollResult.set(null);
    this.rollBreakdown.set(null);

    // Checked before rolling — hasAdvantage() reads the same checked state
    // this roll is about to use, so it can't disagree with what the
    // player actually sees checked at the moment they hit Rolar.
    const advantage = this.hasAdvantage();

    // roll1Value/roll2Value (drive the loser-dimming color) aren't set here
    // — only once the spin animation actually lands, in the setTimeout
    // below, alongside rollResult/rollBreakdown. Setting them immediately
    // would dim the losing carousel while it's still visibly spinning,
    // telegraphing the outcome before the reveal.
    this.roll1Value.set(null);
    this.roll2Value.set(null);

    const roll1 = Math.floor(Math.random() * 20) + 1;
    this.spinCarouselTo(this.carouselNumbers, this.carouselIndex, roll1);

    // Roll two, take the best — the second carousel only spins (and only
    // exists visually, per the template's own @if) when advantage applies.
    let result = roll1;
    let roll2: number | null = null;
    if (advantage) {
      roll2 = Math.floor(Math.random() * 20) + 1;
      this.spinCarouselTo(this.carousel2Numbers, this.carousel2Index, roll2);
      result = Math.max(roll1, roll2);
    }

    const checkedPowerRows = this.attackPowerRows().filter((row) => this.isPowerChecked(row.effect.id));
    // Ataque Especial's hit-side share (if any) rides along as an ordinary
    // mod_hit effect, same as any other checked power.
    const ataqueEspecialEffects = this.ataqueEspecialEffects();
    const checkedEffects = [
      ...checkedPowerRows.flatMap((row) => row.power.effects ?? []),
      ...ataqueEspecialEffects,
      ...this.selectedWeaponGrantedEffects(),
      ...this.pontoFracoMarginEffects(checkedPowerRows),
    ];
    const ataqueEspecialHit = resolveTag(ataqueEspecialEffects, 'mod_hit');

    this.isCriticalStrike.set(result >= calculateMargin(weapon, checkedEffects));

    const skillId = weapon.purpose !== 'melee' ? this.rangedSkillId : this.meleeSkillId;
    const skill = this.staticRegistry.skills.find((s) => s.id === skillId);
    const skillBonus = skill
      ? calculateSkillBonus(this.character(), skill, this.staticRegistry.armors, this.staticRegistry.shields, this.staticRegistry.powers)
      : 0;

    // Espreitar (Passiva) — auto-applied, never a checkbox (see
    // espreitarPassivaPowerId above). Only counts when the character
    // actually has it AND a Marca da Presa tier is checked this roll.
    const hasEspreitarPassiva = (this.character().active_effects ?? []).some((e) => e.power_id === this.espreitarPassivaPowerId);
    const marcaDaPresaRow = checkedPowerRows.find((row) => this.marcaDaPresaPowerIds.includes(row.power.id));
    const inimigoChecked = checkedPowerRows.some((row) => this.inimigoDeCriaturaPowerIds.includes(row.power.id));
    const espreitarBonus =
      hasEspreitarPassiva && marcaDaPresaRow ? (marcaDaPresaRow.power.pm_cost ?? 0) * (inimigoChecked ? 2 : 1) : 0;

    // Self-inflicted PV cost (e.g. Golpe Pessoal's Sacrifício) — same
    // "spent regardless of whether the attack connects" timing as PM,
    // resolved straight from the same checkedEffects pool since it's an
    // ordinary effect tag, not a separate field like pm_cost.
    spendPv(this.apiService, this.useCharacter, this.id(), this.character(), resolveTag(checkedEffects, 'self_damage'));

    const total = calculateHit(result, skillBonus, checkedEffects) + espreitarBonus;
    const breakdown = [
      `d20 ${this.signedValue(result)}`,
      `${skill?.name ?? 'Luta'} ${this.signedValue(skillBonus)}`,
      // Only powers that actually carry a mod_hit entry — a checked
      // mod_dmg-only power (e.g. a pure damage boost) belongs in step 4's
      // damage breakdown instead, not here with a misleading +0.
      ...checkedPowerRows
        .filter((row) => (row.power.effects ?? []).some((e) => e.tag === 'mod_hit'))
        .map((row) => `${row.power.name} ${this.signedValue(resolveTag(row.power.effects ?? [], 'mod_hit'))}`),
      ...(ataqueEspecialHit !== 0 ? [`Ataque Especial ${this.signedValue(ataqueEspecialHit)}`] : []),
      ...(espreitarBonus !== 0 ? [`Espreitar ${this.signedValue(espreitarBonus)}`] : []),
    ];

    setTimeout(() => {
      this.rollResult.set(total);
      this.rollBreakdown.set(breakdown);
      this.roll1Value.set(roll1);
      this.roll2Value.set(roll2);
    }, this.carouselTransitionMs);
  }

  private signedValue(value: number): string {
    return value >= 0 ? `+${value}` : `${value}`;
  }

  // Same six-way attribute code -> Portuguese name mapping duplicated
  // wherever it's needed (e.g. character-main.ts's attribute list) rather
  // than shared, per this codebase's convention.
  private attributeLabel(attribute: string): string {
    const labels: Record<string, string> = {
      str: 'Força',
      dex: 'Destreza',
      con: 'Constituição',
      int: 'Inteligência',
      knw: 'Conhecimento',
      car: 'Carisma',
    };
    return labels[attribute] ?? attribute;
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

  // Power checklist (step 2) — every power the character has whose
  // usability rides an attack roll (active/roll_active — trigger/
  // trigger_active dropped 2026-09-04, no combat engine planned) AND whose
  // effects include a mod_hit or mod_dmg tag. Both tags share one checklist
  // shown before rolling — the player must declare which powers they're
  // using up front (PM cost etc. is spent regardless of whether the attack
  // connects), not retroactively once damage is being calculated in step 4.
  // Checked state isn't persisted anywhere yet — resolveTag (tag-solver.ts)
  // is what sums the checked ones into the real roll totals.
  private readonly attackUsabilities = ['active', 'roll_active'];
  private readonly attackTags = ['mod_hit', 'mod_dmg'];

  // Same "[XPM] Name" convention as the Ataque Especial dropdown options —
  // pm_cost defaults to 0 for powers with none, so only a real cost shows.
  protected powerChecklistLabel(power: Power): string {
    return power.pm_cost > 0 ? `[${power.pm_cost}PM] ${power.name}` : power.name;
  }

  protected attackPowerRows(): { effect: CharacterActiveEffectRow; power: Power }[] {
    const weapon = this.selectedWeapon();
    if (!weapon) {
      return [];
    }
    const rows: { effect: CharacterActiveEffectRow; power: Power }[] = [];
    for (const effect of this.character().active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || !this.attackUsabilities.includes(power.usability)) {
        continue;
      }
      if (!(power.effects ?? []).some((e) => this.attackTags.includes(e.tag))) {
        continue;
      }
      if (!this.matchesVisibilityReqs(power, weapon)) {
        continue;
      }
      rows.push({ effect, power });
    }
    rows.push(...this.golpePessoalRows());
    // Resolves sentinel value/limit (attribute code -> current stat bonus,
    // via calculateStatBonus, not base_*; `character_level` -> the
    // character's level) once, here, so every downstream consumer
    // (breakdown lines, checkedEffects pooling, extra_die/push_distance
    // filtering) already sees a clean number — no special-casing needed
    // anywhere else. Real powers and golpe-merged effects both go through
    // this the same way.
    return rows.map((row) => ({
      effect: row.effect,
      power: { ...row.power, effects: resolveEffectSentinels(row.power.effects ?? [], this.character(), this.staticRegistry.powers) },
    }));
  }

  // Every BUILT golpe (name/power_ids set) shows up unconditionally as its
  // own checkbox — effects are its merged power_ids (resolveGolpePessoalEffects),
  // which join the same checkedEffects pool every other checked power/
  // Ataque Especial already feeds into (checkedPowerRows.flatMap below),
  // no separate resolution path needed. Synthetic id is negative so it can
  // never collide with a real active_effects/power id in the same
  // checkedPowerIds Set.
  private golpePessoalRows(): { effect: CharacterActiveEffectRow; power: Power }[] {
    const character = this.character();
    return (character.golpes_pessoais ?? [])
      .filter((golpe) => golpe.name !== null)
      .map((golpe) => {
        // Same "sum the picked options' own pm_cost" rule as the build
        // modal's currentCost() — resolved live from powers, never cached.
        const pmCost = (golpe.power_ids ?? []).reduce((sum, id) => sum + (this.staticRegistry.powers.find((p) => p.id === id)?.pm_cost ?? 0), 0);
        return {
          effect: { id: -golpe.id, character_id: character.id, power_id: -golpe.id, is_active: false },
          power: {
            id: -golpe.id,
            name: golpe.name!,
            description: '',
            source: 'specific',
            usability: 'roll_active',
            default_checked: false,
            action_cost: 'none',
            duration: null,
            pm_cost: pmCost,
            prerequisites: null,
            effects: resolveGolpePessoalEffects(golpe, this.staticRegistry.powers),
            visibility_reqs: null,
            icon_file_name: null,
          },
        };
      });
  }

  // Null visibility_reqs = always relevant.
  private matchesVisibilityReqs(power: Power, weapon: Weapon): boolean {
    const reqs = power.visibility_reqs;
    if (!reqs) {
      return true;
    }
    if (reqs.weapon_any) {
      return reqs.weapon_any.some((option) =>
        this.matchesWeaponCondition(weapon, option.grip, option.purpose ? [option.purpose] : undefined, option.ability),
      );
    }
    return this.matchesWeaponCondition(weapon, reqs.weapon_grip, reqs.weapon_purpose, reqs.weapon_ability);
  }

  private matchesWeaponCondition(weapon: Weapon, grip?: string, purpose?: string[], ability?: number): boolean {
    if (grip && weapon.grip !== grip) {
      return false;
    }
    if (purpose && !purpose.includes(weapon.purpose)) {
      return false;
    }
    if (ability !== undefined && !(weapon.ability_ids ?? []).includes(ability)) {
      return false;
    }
    return true;
  }

  // Seeded from each row's own power.default_checked when a hand is picked
  // (selectHand) — attackPowerRows depends on selectedWeapon, so this can't
  // run any earlier than that user action anyway.
  protected readonly checkedPowerIds = signal<Set<number>>(new Set());

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
