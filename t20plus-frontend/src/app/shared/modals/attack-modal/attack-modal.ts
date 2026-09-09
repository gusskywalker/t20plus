import { Component, WritableSignal, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterActiveEffectRow, CharacterHandRow, CharacterInventoryRow, Effect, GeneralItem, Power, Weapon } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { calculateAmmoSlots } from '../../helpers/calculators/calculate-ammo-slots/calculate-ammo-slots';
import { matchesPowerReqs } from '../../helpers/matches-power-reqs/matches-power-reqs';
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
import { AtaqueEspecialMode, getAtaqueEspecialBonus, getAtaqueEspecialEffects, getAtaqueEspecialOptions } from './attack-power-resolvers/ataque-especial';
import { getDualWieldEffects, resolveDualWieldPower } from './attack-power-resolvers/dual-wield-resolver';
import { resolveEspreitarBonus } from './attack-power-resolvers/espreitar';
import { resolveProficiencyPenaltyEffects } from '../../helpers/proficiency-penalty-solver/proficiency-penalty-solver';
import { resolveWeaponSizePenaltyEffects, weaponSizePenaltyLabel } from '../../helpers/weapon-size-penalty-solver/weapon-size-penalty-solver';
import { isMarcaDaPresaActive, findCheckedMarcaDaPresa, marcaDaPresaFinalDiceNotation } from './attack-power-resolvers/marca-da-presa';
import { isTiroDeAbateActive, resolveTiroDeAbateEffects } from './attack-power-resolvers/tiro-de-abate';
import { resolvePontoFracoMarginEffects } from './attack-power-resolvers/ponto-fraco';
import { isRangedMeleePenaltyNullified } from './attack-power-resolvers/ranged-melee-penalty-resolver';
import { resolveMiraApuradaEffects } from './attack-power-resolvers/mira-apurada';
import { isAmmoCompatibleWithWeapon } from './attack-power-resolvers/weapon-ammo-solver';

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

  // Explicit screen the modal is on — 1: pick a hand, 2: pick an ammo stack
  // (fired weapons only — skipped straight to 3 for anything else), 3:
  // carousel + power checklist, 4: rolled, breakdown shown, 5: damage. Set
  // to 1 by default (modal opens on step 1), set to 2 or 3 in selectHand()
  // depending on the weapon's purpose, set to 3 in selectAmmo(), set to 4
  // in roll(), set to 5 in markPassed().
  protected readonly currentStep = signal<1 | 2 | 3 | 4 | 5>(1);

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
  // One line per term ("Dados da Arma (3d6) +23", "Inimigo de Monstros
  // (1d12) +7") — the notation shown is what was actually rolled (post
  // step-increases), the number is a lump sum, not per-die, on purpose
  // (see rollDice). Any die size already baked into a power's own name
  // (Marca da Presa's tiers) is stripped first (stripDieNotationSuffix) so
  // it doesn't show twice. `critical` flags the weapon-die line red with
  // "(Crítico Xn!)" prepended, n being calculateMultiplier's result
  // (attack-modal.html) — never true for extra_die/power lines, only the
  // weapon's own die scales by it.
  protected readonly damageBreakdown = signal<{ text: string; critical: boolean }[] | null>(null);

  // Passou advances to step 5 and immediately starts the damage roll.
  // Falhou has no method of its own — it's just Cancelar under a
  // different label in step 4 (see attack-modal.html).
  protected markPassed(): void {
    const weapon = this.selectedWeapon();
    if (!weapon) {
      return; // markPassed() is only reachable after selectHand() picked one
    }

    this.currentStep.set(5);
    this.damageResult.set(null);
    this.damageBreakdown.set(null);
    this.rollingDots.set(1);

    const dotsInterval = setInterval(() => {
      this.rollingDots.set((this.rollingDots() % 3) + 1);
    }, 500);

    const checkedPowerRows = [
      ...this.attackPowerRows().filter((row) => this.isPowerChecked(row.effect.id)),
      ...this.currentlyActivePowerRows(),
    ];
    // Ataque Especial's dmg-side share (if any) rides along as an ordinary
    // mod_dmg effect — same flat treatment as any other checked power's
    // bonus, not scaled by the crit multiplier, which only touches the
    // weapon's own die.
    const ataqueEspecialEffects = this.ataqueEspecialEffects();
    const checkedEffects = [
      ...checkedPowerRows.flatMap((row) => row.power.effects ?? []),
      ...ataqueEspecialEffects,
      ...this.selectedWeaponGrantedEffects(),
      ...this.selectedAmmoGrantedEffects(),
    ];
    const ataqueEspecialDmg = resolveTag(ataqueEspecialEffects, 'mod_dmg');

    // Weapon's own die — stepped by any checked weapon_step_increase
    // (calculateWeaponDice), THEN the crit multiplier touches the result.
    const critical = this.isCriticalStrike();
    const weaponDice = calculateWeaponDice(weapon, checkedEffects, this.selectedWeaponInventoryRow()?.weapon_size ?? 0);
    const rawDiceTotal = rollDice(weaponDice);
    const multiplier = calculateMultiplier(weapon, checkedEffects);
    const diceTotal = critical ? rawDiceTotal * multiplier : rawDiceTotal;

    // extra_die entries are rolled separately from flat add/set mod_dmg —
    // resolveTag (tag-solver.ts) only sums add/set/override, so it already
    // ignores extra_die entries on its own. One line per power (not lumped
    // into a single "Dados Extras" bucket) so e.g. Elemental and Destruidor
    // read as their own named bonuses — never scaled by the crit
    // multiplier, unlike the weapon's own die. weapon_die (Brutal) rolls an
    // additional die matching the already-stepped weaponDice, not the raw
    // base_dmg. Marca da Presa's own die is handled separately below (op
    // marca_da_presa_dice, not extra_die) since it can double and
    // crit-multiply, unlike every entry in this loop.
    //
    // all_die_step_increase (Primeiro Sangue) steps EVERY damage die, but
    // weapon_die-sourced entries already get it folded into weaponDice
    // itself (calculate-weapon-dice.ts sums it alongside weapon_step_increase)
    // — applying it again here would double it. Every other, genuinely
    // independent die (fixed value, die_steps_per_levels) gets its own
    // separate step-up, applied last, after its own resolution.
    const allDieStepIncrease = resolveTag(checkedEffects, 'all_die_step_increase');
    const allExtraDieEntries = checkedPowerRows.flatMap((row) =>
      (row.power.effects ?? [])
        .filter((e) => e.tag === 'mod_dmg' && e.op === 'extra_die')
        .map((effect) => {
          if (effect.value === 'weapon_die') {
            return { row, effect, notation: weaponDice };
          }
          const baseNotation = effect.die_steps_per_levels
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
        const rowTotal = rowEntries.reduce((sum, entry) => sum + rollDice(entry.notation), 0);
        const notations = rowEntries.map((entry) => entry.notation).join('+');
        return { text: `${this.stripDieNotationSuffix(row.power.name)} (${notations}) ${this.signedValue(rowTotal)}`, critical: false, rowTotal };
      })
      .filter((line): line is { text: string; critical: boolean; rowTotal: number } => line !== null);
    const extraDieTotal = extraDieLines.reduce((sum, line) => sum + line.rowTotal, 0);

    // Marca da Presa's own die (op marca_da_presa_dice, kept out of the
    // generic extra_die loop above) — doubled by Inimigo, and crit-
    // multiplied when Tiro de Abate is active, same treatment the weapon's
    // own die gets above.
    const checkedMarcaDaPresa = findCheckedMarcaDaPresa(checkedPowerRows);
    const marcaDaPresaCritical = critical && isTiroDeAbateActive(this.character(), weapon, this.staticRegistry.powers);
    const marcaDaPresaNotation = checkedMarcaDaPresa ? marcaDaPresaFinalDiceNotation(checkedPowerRows, allDieStepIncrease) : null;
    const marcaDaPresaRawTotal = marcaDaPresaNotation ? rollDice(marcaDaPresaNotation) : 0;
    const marcaDaPresaTotal = marcaDaPresaCritical ? marcaDaPresaRawTotal * multiplier : marcaDaPresaRawTotal;
    const marcaDaPresaLine =
      checkedMarcaDaPresa && marcaDaPresaNotation
        ? [
            {
              text: `${marcaDaPresaCritical ? `(X${multiplier}!) ` : ''}${this.stripDieNotationSuffix(checkedMarcaDaPresa.power.name)} (${marcaDaPresaCritical ? this.multipliedDiceNotation(marcaDaPresaNotation, multiplier) : marcaDaPresaNotation}) ${this.signedValue(marcaDaPresaTotal)}`,
              critical: marcaDaPresaCritical,
            },
          ]
        : [];

    // Which attribute (if any) adds to this weapon's damage — melee/thrown
    // default to Força, fired defaults to none, either overridable by a
    // checked mod_dmg_attribute effect (see calculate-attribute-dmg.ts).
    // Always its own line right after Dados da Arma when an attribute
    // applies, even at +0 — same "always shown, not filtered by nonzero"
    // treatment as step 3's skill line, since it's a permanent weapon-purpose
    // fact, not a conditional checked bonus.
    const dmgAttribute = calculateAttributeDmg(weapon, checkedEffects);
    const dmgAttributeBonus = dmgAttribute ? calculateStatBonus(this.character(), dmgAttribute, this.staticRegistry.powers) : 0;

    const total = calculateDamage(diceTotal, checkedEffects) + extraDieTotal + dmgAttributeBonus + marcaDaPresaTotal;

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
      {
        text: `${critical ? `(X${multiplier}!) ` : ''}Dados da Arma (${critical ? this.multipliedDiceNotation(weaponDice, multiplier) : weaponDice}) ${this.signedValue(diceTotal)}`,
        critical,
      },
      ...(dmgAttribute ? [{ text: `${this.attributeLabel(dmgAttribute)} ${this.signedValue(dmgAttributeBonus)}`, critical: false }] : []),
      ...extraDieLines.map(({ text, critical }) => ({ text, critical })),
      ...marcaDaPresaLine,
      // Only powers that actually carry a flat (add/set) mod_dmg entry —
      // extra_die/marca_da_presa_dice already have their own line above,
      // and a checked mod_hit-only power already showed up in step 3's
      // breakdown, so none of those belong here with a misleading +0.
      ...checkedPowerRows
        .filter((row) => (row.power.effects ?? []).some((e) => e.tag === 'mod_dmg' && e.op !== 'extra_die' && e.op !== 'marca_da_presa_dice'))
        .map((row) => ({ text: `${row.power.name} ${this.signedValue(resolveTag(row.power.effects ?? [], 'mod_dmg'))}`, critical: false })),
      ...(ataqueEspecialDmg !== 0 ? [{ text: `Ataque Especial ${this.signedValue(ataqueEspecialDmg)}`, critical: false }] : []),
      ...this.itemGrantedLines('mod_dmg').map((text) => ({ text, critical: false })),
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

  // Same idea as selectedWeaponGrantedEffects, for the ammo stack picked on
  // step 2 (fired weapons only — selectedAmmoInventoryRow stays null for
  // everything else, so this is a no-op there). Ammo is treated as a
  // weapon for melhoria eligibility (see improve-item-modal.ts's
  // isAmmoSelected/melhoriaCategory) — GRANT resolution has to match that:
  // every weapon material (Adamante, Prata, Madeira Tollon, ...) branches
  // its own grant via when_category: 'weapon', which would never match
  // this row's real item_type ('general_item'). Passing a shallow copy
  // with item_type overridden to 'weapon' makes those branches resolve
  // correctly for ammo too, instead of silently granting nothing. type:
  // 'ammo' still passes the item's real sub-type through for when_type
  // (e.g. a future ammo-only grant that isn't weapon-shaped).
  private selectedAmmoGrantedEffects(): Effect[] {
    const inventoryRow = this.selectedAmmoInventoryRow();
    if (!inventoryRow) {
      return [];
    }
    return getItemGrantedEffects(
      { ...inventoryRow, item_type: 'weapon' },
      this.staticRegistry.itemImprovements,
      this.staticRegistry.itemEnchantments,
      this.staticRegistry.powers,
      'ammo',
    );
  }

  // One line per physical item (weapon, ammo) whose melhorias/encantamentos
  // grant a nonzero flat bonus for the given tag — labeled with the item's
  // own display name (custom_name if set, catalog name otherwise), not the
  // granting improvement's name, since a player thinks "my bow does that,"
  // not "Certeira does that." Reused for both the hit breakdown (mod_hit)
  // and damage breakdown (mod_dmg) — same shape, different tag.
  private itemGrantedLines(tag: string): string[] {
    const lines: string[] = [];

    const weapon = this.selectedWeapon();
    const weaponBonus = resolveTag(this.selectedWeaponGrantedEffects(), tag);
    if (weapon && weaponBonus !== 0) {
      const weaponName = this.selectedWeaponInventoryRow()?.custom_name ?? weapon.name;
      lines.push(`${weaponName} ${this.signedValue(weaponBonus)}`);
    }

    const ammoRow = this.selectedAmmoInventoryRow();
    const ammoBonus = resolveTag(this.selectedAmmoGrantedEffects(), tag);
    if (ammoRow && ammoBonus !== 0) {
      const generalItem = this.staticRegistry.generalItems.find((g) => g.id === ammoRow.item_id);
      const ammoName = ammoRow.custom_name ?? generalItem?.name ?? '';
      lines.push(`${ammoName} ${this.signedValue(ammoBonus)}`);
    }

    return lines;
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
    this.selectedAmmoInventoryRow.set(null);
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
    // Seeded from the resolved power's own default_checked (ids 77/259 both
    // have it set true) — same source of truth every other checklist
    // power's pre-check state comes from, not a hardcoded default here.
    // Only actually rendered/contributes when dualWieldPower() is non-null.
    this.dualWieldChecked.set(this.dualWieldPower()?.default_checked ?? false);
    // Fired weapons (bows, crossbows, firearms) need an ammo pick first —
    // thrown weapons don't (the thrown item itself IS the ammo, no
    // separate stack to draw from). See selectAmmo() for step 3's advance.
    this.currentStep.set(weapon.purpose === 'fired' ? 2 : 3);
  }

  // Which ammo stack (a general_item character_inventory row of type
  // 'ammo') this attack will draw from — only ever set for a fired
  // weapon (see selectHand). Not consumed until roll() actually fires the
  // attack — picking a stack here doesn't touch its quantity yet.
  protected readonly selectedAmmoInventoryRow = signal<CharacterInventoryRow | null>(null);

  // Same shape as character-main.ts's generalItemRows, restricted to
  // ammo specifically — every other general_item type is irrelevant
  // here (this step only exists to pick what a fired weapon is shooting).
  protected ammoRows(): { inventoryRow: CharacterInventoryRow; generalItem: GeneralItem; iconFileName: string | undefined }[] {
    const weaponId = this.selectedWeapon()?.id;
    const rows: { inventoryRow: CharacterInventoryRow; generalItem: GeneralItem; iconFileName: string | undefined }[] = [];
    for (const item of this.character().inventory ?? []) {
      if (item.item_type !== 'general_item') {
        continue;
      }
      const generalItem = this.staticRegistry.generalItems.find((g) => g.id === item.item_id);
      if (!generalItem || generalItem.type !== 'ammo') {
        continue;
      }
      if (weaponId === undefined || !isAmmoCompatibleWithWeapon(generalItem.id, weaponId)) {
        continue;
      }
      rows.push({ inventoryRow: item, generalItem, iconFileName: generalItem.icon_file_name ?? undefined });
    }
    return rows;
  }

  protected selectAmmo(inventoryRow: CharacterInventoryRow): void {
    this.selectedAmmoInventoryRow.set(inventoryRow);
    this.currentStep.set(3);
  }

  // Same quantity-bucketed rule as character-main.ts's generalItemSlots —
  // one ammo stack's card should read identically wherever it's shown.
  protected ammoSlots(row: { inventoryRow: CharacterInventoryRow; generalItem: GeneralItem }): number {
    return calculateAmmoSlots(row.generalItem.id, row.inventoryRow.quantity) ?? row.generalItem.slots;
  }

  protected slotsLabel(slots: number): string {
    return slots === 1 ? 'Espaço' : 'Espaços';
  }

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  // Ataque Especial's options/bonus/effects logic lives in
  // attack-power-resolvers/ataque-especial.ts — this component only owns
  // the picked-tier/mode UI state (signals below) and the dropdown itself.

  // Only the tiers this character actually has granted — highest bonus
  // first, which selectHand() uses as the default pick. id/name match
  // SearchableDropdown's expected item shape.
  protected ataqueEspecialOptions(): { id: number; name: string; bonus: number }[] {
    return getAtaqueEspecialOptions(this.character(), this.staticRegistry.powers);
  }

  // null = the "Ataque Especial" checkbox is unchecked (not using it).
  protected readonly selectedAtaqueEspecialId = signal<number | null>(null);
  protected readonly ataqueEspecialMode = signal<AtaqueEspecialMode>('hit');

  // id/name match SearchableDropdown's expected item shape — static, unlike
  // ataqueEspecialOptions() which depends on the character's granted tiers.
  protected readonly ataqueEspecialModeOptions: { id: AtaqueEspecialMode; name: string }[] = [
    { id: 'hit', name: 'Somente Acerto' },
    { id: 'dmg', name: 'Somente Dano' },
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
    const checkedPowerRows = [
      ...this.attackPowerRows().filter((row) => this.isPowerChecked(row.effect.id)),
      ...this.currentlyActivePowerRows(),
    ];
    const checkedEffects = [
      ...checkedPowerRows.flatMap((row) => row.power.effects ?? []),
      ...this.ataqueEspecialEffects(),
      ...this.selectedWeaponGrantedEffects(),
      ...this.selectedAmmoGrantedEffects(),
      ...resolvePontoFracoMarginEffects(this.character(), checkedPowerRows),
      ...resolveMiraApuradaEffects(this.character(), weapon, this.staticRegistry.powers),
      ...resolveTiroDeAbateEffects(this.character(), weapon, this.staticRegistry.powers),
    ];
    return calculateMargin(weapon, checkedEffects);
  }

  private ataqueEspecialBonus(): number {
    return getAtaqueEspecialBonus(this.ataqueEspecialOptions(), this.selectedAtaqueEspecialId());
  }

  // Feeds straight into the same checkedEffects list every other checked
  // power already goes through (resolveTag/calculateHit/calculateDamage).
  private ataqueEspecialEffects(): Effect[] {
    return getAtaqueEspecialEffects(this.ataqueEspecialBonus(), this.ataqueEspecialMode());
  }

  // Whichever of Ambidestria/Estilo de Duas Armas the character actually
  // owns (null if neither, or both — see dual-wield-resolver.ts), read live
  // off hand_1/hand_2's current contents so re-equipping mid-modal updates
  // it — same "live, not a snapshot" treatment as hasAdvantage().
  protected dualWieldPower(): Power | null {
    const character = this.character();
    const handsByName = new Map((character.hands ?? []).map((hand) => [hand.name, hand]));
    const hand1 = handsByName.get('hand_1');
    const hand2 = handsByName.get('hand_2');
    const hand1Resolved = hand1 ? this.resolveHandWeapon(character, hand1) : undefined;
    const hand2Resolved = hand2 ? this.resolveHandWeapon(character, hand2) : undefined;
    return resolveDualWieldPower(
      character,
      this.staticRegistry.powers,
      hand1Resolved?.inventoryRow !== undefined,
      hand2Resolved?.inventoryRow !== undefined,
    );
  }

  // Pre-checked by selectHand() — see the comment there.
  protected readonly dualWieldChecked = signal(true);

  private dualWieldEffects(): Effect[] {
    return getDualWieldEffects(this.dualWieldPower(), this.dualWieldChecked());
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
      ...this.currentlyActivePowerRows().flatMap((row) => row.power.effects ?? []),
      ...this.ataqueEspecialEffects(),
      ...this.selectedWeaponGrantedEffects(),
      ...this.selectedAmmoGrantedEffects(),
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

  // Decrements the picked ammo stack by 1 the moment the attack is actually
  // rolled (Rolar), not when it was merely selected on step 2 — a
  // reselected/cancelled hand never touches inventory. No-op for anything
  // that isn't a fired weapon with a stack picked.
  private spendAmmo(): void {
    const ammoRow = this.selectedAmmoInventoryRow();
    if (!ammoRow) {
      return;
    }
    const quantity = ammoRow.quantity - 1;
    if (quantity <= 0) {
      this.apiService.destroyCharacterInventoryItem(this.character().id, ammoRow.id).subscribe(({ hands, accessory_slots, inventory }) => {
        this.useCharacter.patchCharacterCache(this.id(), { hands, accessory_slots, inventory });
      });
      return;
    }
    this.apiService.updateCharacterInventoryItem(this.character().id, ammoRow.id, { quantity }).subscribe((inventory) => {
      this.useCharacter.patchCharacterCache(this.id(), { inventory });
    });
  }

  protected roll(): void {
    const weapon = this.selectedWeapon();
    if (!weapon) {
      return; // roll() is only reachable after selectHand() picked one
    }

    spendPm(this.apiService, this.useCharacter, this.id(), this.character(), this.checkedPmCost());
    this.spendAmmo();

    this.currentStep.set(4);
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

    const checkedPowerRows = [
      ...this.attackPowerRows().filter((row) => this.isPowerChecked(row.effect.id)),
      ...this.currentlyActivePowerRows(),
    ];
    // Ataque Especial's hit-side share (if any) rides along as an ordinary
    // mod_hit effect, same as any other checked power.
    const ataqueEspecialEffects = this.ataqueEspecialEffects();
    const dualWieldEffects = this.dualWieldEffects();
    const proficiencyPenaltyEffects = resolveProficiencyPenaltyEffects(weapon, this.character());
    const weaponSizePenaltyEffects = resolveWeaponSizePenaltyEffects(
      this.character().current_size,
      this.selectedWeaponInventoryRow()?.weapon_size ?? 0,
      this.character(),
      this.staticRegistry.powers,
    );
    const miraApuradaEffects = resolveMiraApuradaEffects(this.character(), weapon, this.staticRegistry.powers);
    const tiroDeAbateEffects = resolveTiroDeAbateEffects(this.character(), weapon, this.staticRegistry.powers);
    const checkedEffects = [
      ...checkedPowerRows.flatMap((row) => row.power.effects ?? []),
      ...ataqueEspecialEffects,
      ...dualWieldEffects,
      ...proficiencyPenaltyEffects,
      ...weaponSizePenaltyEffects,
      ...miraApuradaEffects,
      ...tiroDeAbateEffects,
      ...this.selectedWeaponGrantedEffects(),
      ...this.selectedAmmoGrantedEffects(),
      ...resolvePontoFracoMarginEffects(this.character(), checkedPowerRows),
    ];
    const ataqueEspecialHit = resolveTag(ataqueEspecialEffects, 'mod_hit');
    const dualWieldHit = resolveTag(dualWieldEffects, 'mod_hit');
    const proficiencyPenaltyHit = resolveTag(proficiencyPenaltyEffects, 'mod_hit');
    const weaponSizePenaltyHit = resolveTag(weaponSizePenaltyEffects, 'mod_hit');
    const miraApuradaHit = resolveTag(miraApuradaEffects, 'mod_hit');
    const tiroDeAbateHit = resolveTag(tiroDeAbateEffects, 'mod_hit');

    this.isCriticalStrike.set(result >= calculateMargin(weapon, checkedEffects));

    const skillId = weapon.purpose !== 'melee' ? this.rangedSkillId : this.meleeSkillId;
    const skill = this.staticRegistry.skills.find((s) => s.id === skillId);
    const skillBonus = skill
      ? calculateSkillBonus(this.character(), skill, this.staticRegistry.armors, this.staticRegistry.shields, this.staticRegistry.powers)
      : 0;

    // Espreitar (Combate) — auto-applied, never a checkbox (see
    // attack-power-resolvers/espreitar.ts). Only counts when the character
    // actually has it AND a Marca da Presa tier is checked this roll.
    const espreitarBonus = resolveEspreitarBonus(this.character(), checkedPowerRows);

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
      ...(dualWieldHit !== 0 ? [`${this.dualWieldPower()?.name} ${this.signedValue(dualWieldHit)}`] : []),
      ...(proficiencyPenaltyHit !== 0 ? [`Sem Proficiência ${this.signedValue(proficiencyPenaltyHit)}`] : []),
      ...(weaponSizePenaltyHit !== 0
        ? [`${weaponSizePenaltyLabel(this.character(), this.staticRegistry.powers)} ${this.signedValue(weaponSizePenaltyHit)}`]
        : []),
      ...(miraApuradaHit !== 0 ? [`Mira Apurada ${this.signedValue(miraApuradaHit)}`] : []),
      ...(tiroDeAbateHit !== 0 ? [`Tiro de Abate ${this.signedValue(tiroDeAbateHit)}`] : []),
      ...(espreitarBonus !== 0 ? [`Espreitar ${this.signedValue(espreitarBonus)}`] : []),
      ...this.itemGrantedLines('mod_hit'),
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

  // Some power names bake in a die size that only ever matched their own
  // fixed tier (e.g. "Marca da Presa (1d8)") — now that every extra_die
  // breakdown line appends the actual rolled notation itself, keeping the
  // name's own copy too would just show it twice ("Marca da Presa (1d8)
  // (1d8)"). Strips a trailing "(NdM)" group so the line reads clean.
  private stripDieNotationSuffix(name: string): string {
    return name.replace(/\s*\(\d+d\d+\)\s*$/, '');
  }

  // Display only — the weapon's own die count scaled by the crit
  // multiplier (1d12 -> 4d12 on a x4 crit), so the breakdown shows what a
  // critical actually represents (rolling the die that many extra times),
  // not the un-scaled notation next to an already-multiplied total. The
  // real roll (rawDiceTotal * multiplier) is unaffected — this never
  // re-rolls or changes the number, only how the notation reads.
  private multipliedDiceNotation(notation: string, multiplier: number): string {
    const match = notation.match(/^(\d+)d(\d+)$/);
    if (!match) {
      return notation;
    }
    return `${Number(match[1]) * multiplier}d${match[2]}`;
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

  // Power checklist (step 3) — every power the character has whose
  // usability is roll_active (a fresh per-attack self-report, e.g. Ataque
  // Poderoso, Valentão) AND whose effects include a mod_hit or mod_dmg tag.
  // 'active' powers (Percepção Temporal, Marca da Presa, Xadrez de
  // Batalha, ...) are deliberately excluded — those are standing sheet-
  // side Ativar/Desativar toggles, not something re-declared every roll.
  // Their contribution instead flows in via currentlyActivePowerRows()
  // below, merged into the same checkedPowerRows every call site already
  // builds — so an extra_die power like Marca da Presa gets its own named
  // breakdown line exactly like a checked roll_active power would, just
  // sourced from is_active instead of a fresh checkbox.
  // Both tags share one checklist shown before rolling — the player must
  // declare which powers they're using up front (PM cost etc. is spent
  // regardless of whether the attack connects), not retroactively once
  // damage is being calculated in step 5. Checked state isn't persisted
  // anywhere yet — resolveTag (tag-solver.ts) is what sums the checked
  // ones into the real roll totals.
  private readonly attackUsabilities = ['roll_active'];
  // doubles_marca_da_presa_dice (Inimigo de (Criatura)) has no mod_hit/
  // mod_dmg of its own — it's a checked flag another resolver consults
  // (isInimigoChecked) — but still needs to pass this filter to show up as
  // a checkbox at all.
  private readonly attackTags = ['mod_hit', 'mod_dmg', 'doubles_marca_da_presa_dice'];

  // Mestre Caçador (id 203) is otherwise an ordinary roll_active checkbox,
  // but its margin-widen only makes sense "quando usa a habilidade" —
  // hidden from the checklist entirely unless a Marca da Presa tier is
  // currently Ativado, rather than trusting a self-report checkbox that'd
  // always be checkable regardless.
  private readonly mestreCacadorPowerId = 203;

  // "Alvo em Combate Corpo a Corpo" (id 262) is otherwise an ordinary
  // roll_active mod_hit checkbox (applies_when already gates it to fired
  // weapons), but it should be hidden entirely — not just uncheckable —
  // once Mirar/Disparo Preciso nullify it, same reasoning as Mestre
  // Caçador above. See ranged-melee-penalty-resolver.ts.
  private readonly rangedMeleePenaltyPowerId = 262;

  // Ambidestria/Estilo de Duas Armas (ids 77/259) are both ordinary
  // roll_active mod_hit checkboxes on their own power rows, but checking
  // both at once would wrongly stack -4 — excluded from the generic
  // checklist entirely in favor of the single bespoke checkbox resolved by
  // dual-wield-resolver.ts (see dualWieldPower()).
  private readonly dualWieldExcludedPowerIds = [77, 259];

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
      if (this.dualWieldExcludedPowerIds.includes(power.id)) {
        continue;
      }
      if (!(power.effects ?? []).some((e) => this.attackTags.includes(e.tag))) {
        continue;
      }
      if (!this.matchesReqs(power, weapon)) {
        continue;
      }
      if (power.id === this.mestreCacadorPowerId && !isMarcaDaPresaActive(this.character())) {
        continue;
      }
      if (power.id === this.rangedMeleePenaltyPowerId && isRangedMeleePenaltyNullified(this.character(), this.staticRegistry.powers)) {
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

  // Standing powers that apply without a fresh per-roll checkbox — either
  // 'active' and currently toggled on (is_active, set from the character
  // sheet — Percepção Temporal, Marca da Presa, ...), or 'passive' (is_active
  // is true from the moment granted, see get-active-effects.ts's comment on
  // create_character_active_effects_table.php — e.g. Arqueiro, Esgrimista).
  // Same shape/tag filter as attackPowerRows() above, including the weapon
  // applies_when gate (Arqueiro only applies to thrown/fired weapons).
  // Every call site merges this straight into its own
  // checkedPowerRows, so these rows get their own named breakdown line
  // (extra_die included) exactly like a checked roll_active power would —
  // no separate resolution path needed anywhere downstream.
  // Mira Apurada (id 266) and Tiro de Abate (id 254) both have real mod_hit
  // effects, which would otherwise let them through this pipeline
  // unconditionally (neither has an applies_when of its own — their
  // relevance depends entirely on Mirar's state, not the weapon). Resolved
  // instead by mira-apurada.ts/tiro-de-abate.ts, merged in separately by
  // every call site below.
  private readonly bespokeResolvedPowerIds = [266, 254];

  protected currentlyActivePowerRows(): { effect: CharacterActiveEffectRow; power: Power }[] {
    const weapon = this.selectedWeapon();
    if (!weapon) {
      return [];
    }
    const rows: { effect: CharacterActiveEffectRow; power: Power }[] = [];
    for (const effect of this.character().active_effects ?? []) {
      if (!effect.is_active) {
        continue;
      }
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || (power.usability !== 'active' && power.usability !== 'passive')) {
        continue;
      }
      if (this.bespokeResolvedPowerIds.includes(power.id)) {
        continue;
      }
      if (!(power.effects ?? []).some((e) => this.attackTags.includes(e.tag))) {
        continue;
      }
      if (!this.matchesReqs(power, weapon)) {
        continue;
      }
      rows.push({ effect, power });
    }
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
          effect: { id: -golpe.id, character_id: character.id, power_id: -golpe.id, is_active: false, is_favorite: false },
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
            applies_when: null,
            icon_file_name: null,
          },
        };
      });
  }

  // Null applies_when = always relevant. Shared with character-main.ts —
  // see matches-power-reqs.ts.
  private matchesReqs(power: Power, weapon: Weapon): boolean {
    return matchesPowerReqs(power, weapon);
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
