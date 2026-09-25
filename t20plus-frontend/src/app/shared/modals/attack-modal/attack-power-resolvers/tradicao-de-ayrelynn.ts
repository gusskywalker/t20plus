import { Character, CharacterActiveEffectRow, Power, Weapon } from '../../../../api.service';
import { attributeCode } from '../../../helpers/attribute-code/attribute-code';
import { getActiveEffects } from '../../../helpers/get-active-effects/get-active-effects';
import { isTriggerSatisfied } from '../../../helpers/is-trigger-satisfied/is-trigger-satisfied';
import { resolveEffectSentinels } from '../../../helpers/resolve-effect-sentinels/resolve-effect-sentinels';

interface AttackPowerRow {
  effect: CharacterActiveEffectRow;
  power: Power;
}

// swap_dmg_attribute (Tradição de Ayrelynn): while the granting power is
// active, a firearm's damage bonus that adds the `from` attribute (Estilo de
// Disparo's Destreza) adds `value` instead. getActiveEffects has already
// turned those bonuses into numbers, so a swapped row is rebuilt from the
// catalog power with the attribute changed, then resolved again. The row
// takes the swapping power's name.
export function swapDmgAttributeRows(rows: AttackPowerRow[], character: Character, weapon: Weapon, catalogPowers: Power[]): AttackPowerRow[] {
  if (!weapon.is_firearm) {
    return rows;
  }
  const swaps = getActiveEffects(character).filter((effect) => effect.tag === 'swap_dmg_attribute' && effect.op === 'set' && typeof effect.from === 'string' && typeof effect.value === 'string');
  if (swaps.length === 0) {
    return rows;
  }
  return rows.map((row) => {
    const catalogPower = catalogPowers.find((power) => power.id === row.power.id);
    const swap = swaps.find((candidate) => (catalogPower?.effects ?? []).some((effect) => effect.tag === 'mod_dmg' && effect.value === attributeCode(candidate.from as string)));
    if (!catalogPower || !swap) {
      return row;
    }
    const from = attributeCode(swap.from as string);
    const to = attributeCode(swap.value as string);
    const swappedEffects = catalogPower.effects!
      .filter((effect) => isTriggerSatisfied(effect, row.effect.other_sources_state))
      .map((effect) => (effect.tag === 'mod_dmg' && effect.value === from ? { ...effect, value: to } : effect));
    const swappingPower = catalogPowers.find((power) => power.id === swap.source_power_id);
    return {
      effect: row.effect,
      power: { ...row.power, name: swappingPower?.name ?? row.power.name, effects: resolveEffectSentinels(swappedEffects, character, catalogPowers) },
    };
  });
}
