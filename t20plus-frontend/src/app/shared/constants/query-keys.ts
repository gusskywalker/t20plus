export const createQueryKeys = () => {
  return {
    CHARACTERS: ['characters'] as const,
    CAMPAIGNS: ['campaigns'] as const,
    RACES: ['races'] as const,
    ORIGINS: ['origins'] as const,
    GODS: ['gods'] as const,
    CLASSES: ['classes'] as const,
    SKILLS: ['skills'] as const,
    POWERS: ['powers'] as const,
    ACCESSORIES: ['accessories'] as const,
    ARMORS: ['armors'] as const,
    PORTRAITS: ['portraits'] as const,
    ICONS: ['icons'] as const,
    COMPLICATIONS: ['complications'] as const,
    WEAPONS: ['weapons'] as const,
    SHIELDS: ['shields'] as const,
    GENERAL_ITEMS: ['general-items'] as const,
  };
};

export const QUERY_KEYS = createQueryKeys();
