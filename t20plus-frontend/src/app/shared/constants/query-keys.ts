export const createQueryKeys = () => {
  return {
    CHARACTERS: ['characters'] as const,
    CAMPAIGNS: ['campaigns'] as const,
    RACES: ['races'] as const,
    ORIGINS: ['origins'] as const,
    GODS: ['gods'] as const,
    CLASSES: ['classes'] as const,
    SKILLS: ['skills'] as const,
    SPELLS: ['spells'] as const,
    CONDITIONS: ['conditions'] as const,
    POWERS: ['powers'] as const,
    ACCESSORIES: ['accessories'] as const,
    ARMORS: ['armors'] as const,
    PORTRAITS: ['portraits'] as const,
    COMPLICATIONS: ['complications'] as const,
    WEAPONS: ['weapons'] as const,
    SHIELDS: ['shields'] as const,
    GENERAL_ITEMS: ['general-items'] as const,
    ITEM_IMPROVEMENTS: ['item-improvements'] as const,
    ITEM_ENCHANTMENTS: ['item-enchantments'] as const,
    WEAPON_ABILITIES: ['weapon-abilities'] as const,
  };
};

export const QUERY_KEYS = createQueryKeys();
