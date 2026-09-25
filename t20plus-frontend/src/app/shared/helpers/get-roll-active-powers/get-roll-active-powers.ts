import { Character, CharacterActiveEffectRow, Power } from '../../../api.service';
import { getStaticRegistry } from '../../hooks/static-registry';
import { resolveReplacedPowerIds } from '../resolve-replaced-power-ids/resolve-replaced-power-ids';

export type RollActiveOrigin = 'power' | 'spell';

export interface RollActiveRow {
  effect: CharacterActiveEffectRow;
  power: Power;
  origin: RollActiveOrigin;
}

export type RollActiveSource = Pick<Character, 'id' | 'active_effects' | 'active_spell_effects'>;

const SPELL_ROW_ID_OFFSET = -1000000;

interface CachedRollActiveRows {
  catalogs: unknown[];
  rows: RollActiveRow[];
}

const rollActiveCache = new WeakMap<object, CachedRollActiveRows>();

/**
 * Every roll_active power a character can tick on a roll: the powers of its
 * character_active_effects rows (its own and the ones its items grant) and
 * the roll_active entries of spell buffs, one synthetic row per buff. Each
 * roll modal keeps the rows it needs (matching skill, weapon requirements,
 * the source inventory row) and adds its own roll-dependent sources on top.
 *
 * Memoized per character object, like getActiveEffects.
 */
export function getRollActivePowers(character: RollActiveSource): RollActiveRow[] {
  const registry = getStaticRegistry();
  const catalogs: unknown[] = registry ? [registry.powers, registry.spells] : [];
  const cached = rollActiveCache.get(character);
  if (cached && cached.catalogs.length === catalogs.length && cached.catalogs.every((catalog, i) => catalog === catalogs[i])) {
    return cached.rows;
  }
  const rows = registry ? computeRollActivePowers(character, registry) : [];
  rollActiveCache.set(character, { catalogs, rows });
  return rows;
}

function computeRollActivePowers(character: RollActiveSource, registry: NonNullable<ReturnType<typeof getStaticRegistry>>): RollActiveRow[] {
  const rows: RollActiveRow[] = [];
  const powers = registry.powers;

  const replacedPowerIds = resolveReplacedPowerIds(new Set((character.active_effects ?? []).map((row) => row.power_id)), powers);
  for (const effect of character.active_effects ?? []) {
    const power = powers.find((p) => p.id === effect.power_id);
    if (!power || replacedPowerIds.has(power.id) || power.usability !== 'roll_active') {
      continue;
    }
    rows.push({ effect, power, origin: 'power' });
  }

  for (const spellRow of character.active_spell_effects ?? []) {
    const rollEffects = spellRow.effects.filter((effect) => effect.usability === 'roll_active');
    if (rollEffects.length === 0) {
      continue;
    }
    const syntheticId = SPELL_ROW_ID_OFFSET - spellRow.id;
    rows.push({
      effect: { id: syntheticId, character_id: character.id, power_id: syntheticId, is_active: false, is_favorite: false },
      power: {
        id: syntheticId,
        name: registry.spells.find((spell) => spell.id === spellRow.spell_id)?.name ?? 'Magia',
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
      origin: 'spell',
    });
  }

  return rows;
}
