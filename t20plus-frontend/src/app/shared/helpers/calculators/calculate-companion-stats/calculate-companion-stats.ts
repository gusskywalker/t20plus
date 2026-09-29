import { Character, CharacterCompanionRow, Companion, Effect } from '../../../../api.service';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';

export interface CompanionAttack {
  dmg: string | null;
  reach: number | null;
  damageTypes: string[];
}

export interface CompanionStats {
  numbers: Record<string, number | string>;
  maxPv: number;
  attack: CompanionAttack;
  immunities: string[];
}

const NUMERIC_STATS = ['size', 'defense', 'movement', 'str', 'dex', 'con', 'int', 'knw', 'car', 'fortitude', 'reflexes', 'vontade', 'max_pv'];

function resolveStat(base: unknown, stat: string, effects: Effect[], size: number): number | string | undefined {
  const setEffects = effects.filter((effect) => effect.tag === `companion_${stat}` && effect.op === 'set');
  const allModEffects = effects.filter((effect) => effect.tag === `companion_mod_${stat}`);
  const bestByGroup = new Map<string, Effect>();
  for (const effect of allModEffects) {
    const current = effect.stack_group ? bestByGroup.get(effect.stack_group) : undefined;
    if (effect.stack_group && (!current || Number(effect.value ?? 0) > Number(current.value ?? 0))) {
      bestByGroup.set(effect.stack_group, effect);
    }
  }
  const modEffects = allModEffects.filter((effect) => !effect.stack_group || bestByGroup.get(effect.stack_group) === effect);
  if (setEffects.length === 0 && modEffects.length === 0) {
    return base === undefined ? undefined : (base as number | string);
  }
  const baseValue = setEffects.length > 0 ? Number(setEffects[setEffects.length - 1].value ?? 0) : base;
  if (typeof baseValue !== 'number') {
    return baseValue as string | undefined;
  }
  const added = modEffects.reduce((sum, effect) => {
    if (effect.op === 'add') {
      return sum + Number(effect.value ?? 0);
    }
    if (effect.op === 'add_per_size') {
      return sum + Number(effect.value ?? 0) * Math.max(0, size - Number(effect.from_size ?? 0));
    }
    return sum;
  }, 0);
  const multiplier = modEffects.filter((effect) => effect.op === 'multiply').reduce((acc, effect) => acc * Number(effect.value ?? 1), 1);
  return (baseValue + added) * multiplier;
}

/**
 * A companion's resolved stats — its seeded base_stats with every
 * companion_<stat> (op set = new base, last wins) and companion_mod_<stat>
 * (op add stacks, add_per_size scales by the resolved size above from_size,
 * multiply applies last) effect on top. Effects come from the instance's own
 * companion_related_effects plus every active character effect carrying a
 * companion_ tag. Size resolves first since add_per_size reads it.
 */
export function calculateCompanionStats(character: Character, row: CharacterCompanionRow, companion: Companion): CompanionStats {
  const stats = companion.base_stats ?? {};
  const effects: Effect[] = [
    ...(row.companion_related_effects ?? []),
    ...getActiveEffects(character).filter((effect) => effect.tag.startsWith('companion_')),
  ];

  const size = Number(resolveStat(stats['base_size'], 'size', effects, 0) ?? 0);
  const numbers: Record<string, number | string> = {};
  for (const stat of NUMERIC_STATS) {
    const value = resolveStat(stats[`base_${stat}`], stat, effects, size);
    if (value !== undefined) {
      numbers[stat] = value;
    }
  }

  const baseAttack = (stats['base_attack'] ?? {}) as { dmg?: string; reach?: number; damage_types?: string[] };
  const lastSet = (tag: string): unknown => effects.filter((effect) => effect.tag === tag && effect.op === 'set').at(-1)?.value;
  const attackType = lastSet('companion_change_attack_type');
  const attackDmg = lastSet('companion_attack_dmg');
  const attackReach = lastSet('companion_attack_reach');

  return {
    numbers,
    maxPv: Number(numbers['max_pv'] ?? 0),
    attack: {
      dmg: attackDmg !== undefined ? String(attackDmg) : (baseAttack.dmg ?? null),
      reach: attackReach !== undefined ? Number(attackReach) : (baseAttack.reach ?? null),
      damageTypes: attackType !== undefined ? [String(attackType)] : (baseAttack.damage_types ?? []),
    },
    immunities: Array.isArray(stats['base_immunities']) ? (stats['base_immunities'] as string[]) : [],
  };
}
