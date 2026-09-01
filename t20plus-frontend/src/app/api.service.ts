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
  type: string;
  usability: string;
  action_cost: string;
  pm_cost: number;
  prerequisites: Prerequisite[] | null;
  effects: Effect[] | null;
}

export interface Accessory {
  id: number;
  name: string;
  description: string;
  cost: number; // -1 = not purchasable
  slots: number;
  mp_cost: number;
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
  price: number;
  proficiency_id: number | null;
  purpose: string;
  grip: string;
  base_dmg: string;
  base_margin: number;
  base_multiplier: number;
  base_reach: number;
  damage_type: string;
  space: number;
  ability_ids: number[] | null;
  effects: Effect[] | null;
  is_exoteric: boolean;
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
  power_ids: number[] | null;
  campaign: Campaign | null;
}

export interface CreateCharacterLevel {
  level: number;
  class_id: number;
  class_level: number;
  power_id: number | null;
}

export interface CreateCharacterInventoryItem {
  item_type: 'accessory' | 'armor' | 'weapon' | 'shield';
  item_id: number;
  worn: boolean;
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
}
