// A living localStorage mirror of CharacterDraft — every field here is a
// direct copy of one of that service's own signals, same names, same
// shapes. Written on every draft change and read once at construction, so
// an accidental refresh mid-creation doesn't wipe progress. Cleared
// whenever CharacterDraft.reset() runs (a real save, or Reiniciar Criação).
export interface CharacterDraftSnapshot {
  name: string;
  raceId: number | null;
  originId: number | null;
  godId: number | null;
  baseLevel: number | null;
  portraitId: number | null;
  portraitIdRaceId: number | null;
  classIds: (number | null)[];
  baseStr: number;
  baseDex: number;
  baseCon: number;
  baseInt: number;
  baseKnw: number;
  baseCar: number;
  otherAttributes: string[];
  originChoices: number[][];
  originChoicesOriginId: number | null;
  godPowerIds: number[];
  godPowerIdsGodId: number | null;
  classSkillChoices: number[][];
  classSkillChoicesSourceKey: string | null;
  generalComplicationId: number | null;
  generalComplicationPowerId: number | null;
  age: number | null;
  ageBracket: string | null;
  adolescenteOverride: number[];
  adultoPowerId: number | null;
  adultoAgeComplicationId: number | null;
  ambicaoHerdadaPowerId: number | null;
  arcanistaPathPowerId: number | null;
  espiaoSkillAttributeSkillId: number | null;
  chosenSpellIds: (number | null)[];
  maduroClassId: number | null;
  maduroAgeComplicationIds: (number | null)[];
  velhoClassIds: (number | null)[];
  velhoAgeComplicationIds: (number | null)[];
  anciaoClassIds: (number | null)[];
  anciaoAgeComplicationIds: (number | null)[];
  startingSimpleWeaponId: number | null;
  startingMartialWeaponId: number | null;
  originMartialWeaponId: number | null;
  originSimpleWeaponId: number | null;
  originToolId: number | null;
  startingArmorId: number | null;
  startingShieldId: number | null;
  purchasedItemKeys: (string | null)[];
  remainingTibares: number;
  classPowerIds: (number | null)[];
  classPowerIdsSourceKey: string | null;
}

const STORAGE_KEY = 't20plus-character-draft';

// Best-effort only, on every operation — a private window, cleared site
// data, or a full quota shouldn't ever break character creation itself.
export function saveDraftSnapshot(snapshot: CharacterDraftSnapshot): void {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(snapshot));
  } catch {
    // ignore
  }
}

export function loadDraftSnapshot(): CharacterDraftSnapshot | null {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    return raw ? (JSON.parse(raw) as CharacterDraftSnapshot) : null;
  } catch {
    return null;
  }
}

export function clearDraftSnapshot(): void {
  try {
    localStorage.removeItem(STORAGE_KEY);
  } catch {
    // ignore
  }
}
