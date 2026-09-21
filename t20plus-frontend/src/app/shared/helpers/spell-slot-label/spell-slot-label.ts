import { SpellSlot } from '../calculators/calculate-spell-slot-circle-caps/calculate-spell-slot-circle-caps';

export function spellSlotLabel(slot: SpellSlot, className: string): string {
  const source = slot.sourceName ? ` - ${slot.sourceName}` : '';
  return `[Nv. ${slot.classLevel}] ${className}${source} (${slot.cap}º Círculo)`;
}
