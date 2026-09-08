import { Character, Power } from '../../../../api.service';
import { getActiveEffects } from '../../../helpers/get-active-effects/get-active-effects';

// "Alvo em Combate Corpo a Corpo" (id 262, GeneralActionPowerSeeder.php) —
// the -5 self-report checkbox for firing/arremessando at a melee-engaged
// target. Hidden entirely (not just unchecked) whenever
// nullify_ranged_weapon_melee_penalty is present — granted by Mirar (id
// 253) while toggled active, or Disparo Preciso (id 265) passively. Checked
// generically via getActiveEffects, not either power's id directly — same
// convention as allow_dual_wield_full.
export function isRangedMeleePenaltyNullified(character: Character, powers: Power[]): boolean {
  return getActiveEffects(character, powers).some((e) => e.tag === 'nullify_ranged_weapon_melee_penalty');
}
