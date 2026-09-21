import { Character, Power, Weapon } from '../../../../api.service';

export const ESTILO_DE_DISPARO_POWER_ID = 263;
const tradicaoDeAyrelynnAttackPowerId = 17125;

// While Tradição de Ayrelynn (Ataque) is toggled on, Estilo de Disparo's
// Destreza damage bonus becomes Sabedoria on firearms.
export function isEstiloDeDisparoReplaced(character: Character, weapon: Weapon): boolean {
  return weapon.is_firearm && (character.active_effects ?? []).some((effect) => effect.power_id === tradicaoDeAyrelynnAttackPowerId && effect.is_active);
}

export function swapEstiloDeDisparoAttribute(power: Power): Power {
  return {
    ...power,
    name: 'Tradição de Ayrelynn',
    effects: (power.effects ?? []).map((effect) => (effect.tag === 'mod_dmg' && effect.value === 'dex' ? { ...effect, value: 'knw' } : effect)),
  };
}
