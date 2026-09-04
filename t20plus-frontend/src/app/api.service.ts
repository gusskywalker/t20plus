import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../environments/environment';

export interface AuthResponse {
  token: string;
  user: {
    id: number;
    name: string;
    email: string;
  };
}

export interface Campaign {
  id: number;
  user_id: number;
  name: string;
}

export interface Race {
  id: number;
  name: string;
  mod_str: number;
  mod_dex: number;
  mod_con: number;
  mod_int: number;
  mod_knw: number;
  mod_car: number;
  mod_other: number;
  // Attribute keys (str/dex/con/int/knw/car) mod_other's free points can
  // NOT go into — e.g. Meio-Elfo's "+1 em dois atributos, exceto
  // Constituição." Null/empty = no restriction.
  mod_other_excluded_attributes: string[] | null;
  base_movement: number;
  base_size: number;
}

export interface GrantOption {
  tag: string;
  op: string;
  skill_id?: number;
  power_id?: number;
  accessory_id?: number;
  armor_id?: number;
  value?: number;
}

export interface GrantGroup {
  type: 'choice';
  label: string;
  picks: number;
  options: GrantOption[];
}

export interface Origin {
  id: number;
  name: string;
  grants: GrantGroup[] | null;
}

export interface Skill {
  id: number;
  name: string;
  description: string;
  key_attribute: string;
  trained_only: boolean;
  armor_penalty: boolean;
}

export interface Effect {
  tag: string;
  op: string;
  skill_id?: number;
  value?: number | string;
  // Only meaningful with op: 'add_per_level' — total bonus =
  // floor(character.level / per_levels) * value (e.g. Vontade de Ferro's
  // "+1 PM a cada dois níveis" is value: 1, per_levels: 2).
  per_levels?: number;
}

// Gates whether a power is even relevant to surface in a self-report
// checklist UI (e.g. the planned attack-mode picker), independent of
// whether the power's own effects are numerically modeled at all — see
// powers.visibility_reqs migration comment. Not consumed anywhere yet.
export interface VisibilityReqs {
  weapon_grip?: string;
  weapon_purpose?: string[];
  weapon_ability?: number;
  weapon_any?: { grip?: string; purpose?: string; ability?: number }[];
}

export interface Prerequisite {
  type: string;
  attribute?: string;
  min?: number;
  power_id?: number;
  class_ids?: number[];
  min_level?: number;
  skill_id?: number;
  god_id?: number;
  race_ids?: number[];
  value?: string;
}

export interface Power {
  id: number;
  name: string;
  description: string;
  // Where this power originates in a character's build — renamed from
  // "type" 2026-09-04 (see powers table migration for the full history).
  source: string;
  usability: string;
  // Only meaningful for roll_active powers shown in a roll screen's
  // checklist — pure UX default (starts the checkbox checked or not),
  // never a correctness mechanism. The player can always flip it either
  // way per roll.
  default_checked: boolean;
  action_cost: string;
  // Only meaningful when usability is 'active' — null means it resolves
  // instantly (Medicina), a real value (turn/scene/day) means it persists
  // until turned off (Percepção Temporal). Drives whether the sheet shows
  // a one-shot "Usar" button or a real Ativar/Desativar toggle.
  duration: string | null;
  pm_cost: number;
  prerequisites: Prerequisite[] | null;
  effects: Effect[] | null;
  visibility_reqs: VisibilityReqs | null;
  icon_file_name: string | null;
}

export interface Accessory {
  id: number;
  name: string;
  description: string;
  cost: number; // -1 = not purchasable
  slots: number;
  mp_cost: number;
  icon_file_name: string | null;
}

export interface Armor {
  id: number;
  name: string;
  description: string;
  type: string;
  mod_def: number;
  armor_penalty: number;
  cost: number;
  slots: number;
  icon_file_name: string | null;
}

export interface Portrait {
  id: number;
  file_name: string;
  race_ids: number[] | null;
}

export interface Complication {
  id: number;
  name: string;
  description: string;
  type: string;
  power_ids: number[] | null;
}

export interface God {
  id: number;
  name: string;
  energy_type: number | null;
}

export interface ClassSkillGroup {
  picks: number;
  options: number[];
}

export interface CharacterClass {
  id: number;
  name: string;
  initial_pv: number;
  initial_pm: number;
  level_pv: number;
  level_pm: number;
  divine_power_picks: number;
  skills: ClassSkillGroup[] | null;
  proficiency_ids: number[] | null;
}

export interface Weapon {
  id: number;
  name: string;
  description: string;
  cost: number;
  proficiency_id: number | null;
  purpose: string;
  grip: string;
  base_dmg: string;
  base_margin: number;
  base_multiplier: number;
  base_reach: number;
  damage_type: string;
  slots: number;
  ability_ids: number[] | null;
  effects: Effect[] | null;
  is_exoteric: boolean;
  icon_file_name: string | null;
}

export interface Shield {
  id: number;
  name: string;
  description: string;
  type: string;
  mod_def: number;
  armor_penalty: number;
  cost: number;
  slots: number;
  effects: Effect[] | null;
  is_exoteric: boolean;
  icon_file_name: string | null;
}

export interface GeneralItem {
  id: number;
  name: string;
  description: string;
  type: 'tools' | 'alchemic' | 'food' | 'potion' | 'ammunition';
  cost: number; // -1 = not purchasable
  slots: number;
  icon_file_name: string | null;
  effects: Effect[] | null;
  consumable: boolean;
  base_dmg: string | null; // dice notation, e.g. "1d6" — only thrown alchemic items use this
}

export interface CharacterLevelRow {
  id: number;
  character_id: number;
  level: number;
  class_id: number;
  class_level: number;
  power_id: number | null;
  // Eloquent auto-snake-cases relation names on serialization — the
  // backend method is characterClass(), but the JSON key comes out
  // character_class.
  character_class: CharacterClass | null;
}

export interface CharacterInventoryRow {
  id: number;
  character_id: number;
  item_type: 'accessory' | 'armor' | 'weapon' | 'shield' | 'general_item';
  item_id: number;
  worn: boolean;
  // Always 1 for weapons/armors/shields/accessories — each row is one
  // physical instance. Stacks for general_items.
  quantity: number;
  improvement_ids: number[] | null;
  enchantment_ids: number[] | null;
}

export interface CharacterHandRow {
  id: number;
  character_id: number;
  name: 'hand_1' | 'hand_2' | 'hand_3' | 'hand_4';
  // Whether this hand exists on the character right now — every character
  // has all 4 rows, only hand_1/hand_2 start enabled.
  enabled: boolean;
  // character_inventory.id, not {item_type, item_id} — lets two identical
  // owned items (e.g. two Espada Curta) be told apart. Independent of
  // CharacterInventoryRow.worn, which still drives whether an item's
  // effects are active — this is only "which hand holds what".
  inventory_ids: number[] | null;
}

export interface CharacterAccessoryRow {
  id: number;
  character_id: number;
  name: 'accessory_1' | 'accessory_2' | 'accessory_3' | 'accessory_4' | 'accessory_5';
  // Whether this slot exists on the character right now — every character
  // has all 5 rows, only accessory_1..4 start enabled (accessory_5 needs a
  // not-yet-built power effect to unlock).
  enabled: boolean;
  // character_inventory.id — a single value, not an array like
  // CharacterHandRow.inventory_ids, since an accessory slot only ever
  // holds one item.
  inventory_id: number | null;
}

export interface CharacterActiveEffectRow {
  id: number;
  character_id: number;
  power_id: number;
  // Whether this row currently contributes to Defesa/PV/PM/skill totals —
  // true for passive powers from the moment they're granted, false
  // otherwise until an 'active' power's own Ativar button flips it.
  is_active: boolean;
}

// One golpe slot — created empty the moment Golpe Pessoal (power id 115)
// is picked (CharacterController::store), filled in later by the
// character-sheet build modal. null fields = not built yet.
export interface CharacterGolpePessoalRow {
  id: number;
  character_id: number;
  name: string | null;
  // Guerreiro class-relative level this golpe was last (re)built at — null
  // until first built. Matching the character's CURRENT Guerreiro level
  // means it was already (re)built this level, so the modal goes view-only
  // until the next level-up ("Quando sobe de nível, você pode reconstruir
  // seu Golpe Pessoal").
  guerreiro_level_picked: number | null;
  // Ids into powers (source: 'specific') — the picked menu options
  // (Elemental, Brutal, Letal, etc.), duplicates allowed for repeatable
  // ones. PM cost/effects are resolved live from these, never cached.
  power_ids: number[] | null;
}

export interface Character {
  id: number;
  user_id: number;
  campaign_id: number | null;
  name: string;
  level: number;
  secret_code: string;
  base_str: number;
  base_dex: number;
  base_con: number;
  base_int: number;
  base_knw: number;
  base_car: number;
  race_id: number | null;
  origin_id: number | null;
  god_id: number | null;
  portrait_id: number | null;
  trained_skill_ids: number[] | null;
  age: number | null;
  age_bracket: string | null;
  complication_ids: number[] | null;
  is_dead: boolean;
  xp: number;
  tibares: number;
  current_pv: number | null;
  current_pm: number | null;
  campaign: Campaign | null;
  race: Race | null;
  portrait: Portrait | null;
  god: God | null;
  // origin/levels only present on the show() response — index() doesn't
  // eager-load them, the character-list cards don't need this detail.
  origin?: Origin | null;
  levels?: CharacterLevelRow[];
  inventory?: CharacterInventoryRow[];
  hands?: CharacterHandRow[];
  // Eloquent auto-snake-cases relation names on serialization — the
  // backend method is accessorySlots(), but the JSON key comes out
  // accessory_slots (see CharacterLevelRow.character_class for the same rule).
  accessory_slots?: CharacterAccessoryRow[];
  // Same rule — backend method is activeEffects(), JSON key active_effects.
  active_effects?: CharacterActiveEffectRow[];
  // Same rule — backend method is golpesPessoais(), JSON key golpes_pessoais.
  golpes_pessoais?: CharacterGolpePessoalRow[];
}

export interface CreateCharacterLevel {
  level: number;
  class_id: number;
  class_level: number;
  power_id: number | null;
}

export interface CreateCharacterInventoryItem {
  item_type: 'accessory' | 'armor' | 'weapon' | 'shield' | 'general_item';
  item_id: number;
  worn: boolean;
  quantity?: number; // defaults to 1 backend-side if omitted
}

/** Everything character-creation-step-9's continue() sends in one request — see player/character-creation/character-payload.ts. */
export interface CreateCharacterPayload {
  name: string;
  level: number;
  base_str: number;
  base_dex: number;
  base_con: number;
  base_int: number;
  base_knw: number;
  base_car: number;
  race_id: number | null;
  origin_id: number | null;
  god_id: number | null;
  portrait_id: number | null;
  trained_skill_ids: number[];
  age: number | null;
  age_bracket: string | null;
  complication_ids: number[];
  power_ids: number[];
  tibares: number;
  levels: CreateCharacterLevel[];
  inventory: CreateCharacterInventoryItem[];
}

@Injectable({
  providedIn: 'root',
})
export class ApiService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  devLogin(): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/auth/dev-login`, {});
  }

  createCharacter(payload: CreateCharacterPayload): Observable<Character> {
    return this.http.post<Character>(`${this.apiUrl}/characters`, payload);
  }

  getCharacter(id: number | string): Observable<Character> {
    return this.http.get<Character>(`${this.apiUrl}/characters/${id}`);
  }

  updateCharacter(id: number | string, payload: Partial<Pick<Character, 'current_pv' | 'current_pm' | 'tibares'>>): Observable<Character> {
    return this.http.patch<Character>(`${this.apiUrl}/characters/${id}`, payload);
  }

  destroyCharacter(id: number | string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/characters/${id}`);
  }

  updateCharacterInventoryItem(
    characterId: number | string,
    inventoryId: number,
    payload: Partial<Pick<CharacterInventoryRow, 'worn'>>,
  ): Observable<CharacterInventoryRow[]> {
    // Returns the character's full inventory, not just this row — an
    // armor equip can unequip other rows too (see CharacterInventoryController).
    return this.http.patch<CharacterInventoryRow[]>(`${this.apiUrl}/characters/${characterId}/inventory/${inventoryId}`, payload);
  }

  destroyCharacterInventoryItem(
    characterId: number | string,
    inventoryId: number,
  ): Observable<{ hands: CharacterHandRow[]; accessory_slots: CharacterAccessoryRow[]; inventory: CharacterInventoryRow[] }> {
    return this.http.delete<{ hands: CharacterHandRow[]; accessory_slots: CharacterAccessoryRow[]; inventory: CharacterInventoryRow[] }>(
      `${this.apiUrl}/characters/${characterId}/inventory/${inventoryId}`,
    );
  }

  addCharacterActiveEffect(characterId: number | string, powerId: number): Observable<CharacterActiveEffectRow[]> {
    return this.http.post<CharacterActiveEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-effects`, { power_id: powerId });
  }

  updateCharacterActiveEffect(characterId: number | string, activeEffectId: number, isActive: boolean): Observable<CharacterActiveEffectRow[]> {
    return this.http.patch<CharacterActiveEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-effects/${activeEffectId}`, {
      is_active: isActive,
    });
  }

  destroyCharacterActiveEffect(characterId: number | string, activeEffectId: number): Observable<CharacterActiveEffectRow[]> {
    return this.http.delete<CharacterActiveEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-effects/${activeEffectId}`);
  }

  equipCharacterHand(
    characterId: number | string,
    handId: number,
    inventoryId: number,
  ): Observable<{ hands: CharacterHandRow[]; inventory: CharacterInventoryRow[] }> {
    return this.http.post<{ hands: CharacterHandRow[]; inventory: CharacterInventoryRow[] }>(
      `${this.apiUrl}/characters/${characterId}/hands/${handId}/equip`,
      { inventory_id: inventoryId },
    );
  }

  unequipCharacterHand(
    characterId: number | string,
    handId: number,
    inventoryId: number,
  ): Observable<{ hands: CharacterHandRow[]; inventory: CharacterInventoryRow[] }> {
    return this.http.post<{ hands: CharacterHandRow[]; inventory: CharacterInventoryRow[] }>(
      `${this.apiUrl}/characters/${characterId}/hands/${handId}/unequip`,
      { inventory_id: inventoryId },
    );
  }

  equipCharacterAccessory(
    characterId: number | string,
    slotId: number,
    inventoryId: number,
  ): Observable<{ accessory_slots: CharacterAccessoryRow[]; inventory: CharacterInventoryRow[] }> {
    return this.http.post<{ accessory_slots: CharacterAccessoryRow[]; inventory: CharacterInventoryRow[] }>(
      `${this.apiUrl}/characters/${characterId}/accessories/${slotId}/equip`,
      { inventory_id: inventoryId },
    );
  }

  unequipCharacterAccessory(
    characterId: number | string,
    slotId: number,
    inventoryId: number,
  ): Observable<{ accessory_slots: CharacterAccessoryRow[]; inventory: CharacterInventoryRow[] }> {
    return this.http.post<{ accessory_slots: CharacterAccessoryRow[]; inventory: CharacterInventoryRow[] }>(
      `${this.apiUrl}/characters/${characterId}/accessories/${slotId}/unequip`,
      { inventory_id: inventoryId },
    );
  }

  getCharacters(): Observable<Character[]> {
    return this.http.get<Character[]>(`${this.apiUrl}/characters`);
  }

  getCampaigns(): Observable<Campaign[]> {
    return this.http.get<Campaign[]>(`${this.apiUrl}/campaigns`);
  }

  getRaces(): Observable<Race[]> {
    return this.http.get<Race[]>(`${this.apiUrl}/races`);
  }

  getOrigins(): Observable<Origin[]> {
    return this.http.get<Origin[]>(`${this.apiUrl}/origins`);
  }

  getGods(): Observable<God[]> {
    return this.http.get<God[]>(`${this.apiUrl}/gods`);
  }

  getClasses(): Observable<CharacterClass[]> {
    return this.http.get<CharacterClass[]>(`${this.apiUrl}/classes`);
  }

  getSkills(): Observable<Skill[]> {
    return this.http.get<Skill[]>(`${this.apiUrl}/skills`);
  }

  getPowers(): Observable<Power[]> {
    return this.http.get<Power[]>(`${this.apiUrl}/powers`);
  }

  getAccessories(): Observable<Accessory[]> {
    return this.http.get<Accessory[]>(`${this.apiUrl}/accessories`);
  }

  getArmors(): Observable<Armor[]> {
    return this.http.get<Armor[]>(`${this.apiUrl}/armors`);
  }

  getPortraits(): Observable<Portrait[]> {
    return this.http.get<Portrait[]>(`${this.apiUrl}/portraits`);
  }

  getComplications(): Observable<Complication[]> {
    return this.http.get<Complication[]>(`${this.apiUrl}/complications`);
  }

  getWeapons(): Observable<Weapon[]> {
    return this.http.get<Weapon[]>(`${this.apiUrl}/weapons`);
  }

  getShields(): Observable<Shield[]> {
    return this.http.get<Shield[]>(`${this.apiUrl}/shields`);
  }

  getGeneralItems(): Observable<GeneralItem[]> {
    return this.http.get<GeneralItem[]>(`${this.apiUrl}/general-items`);
  }
}
