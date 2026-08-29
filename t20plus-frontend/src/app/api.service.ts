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

export interface Origin {
  id: number;
  name: string;
}

export interface God {
  id: number;
  name: string;
  energy_type: number | null;
}

export interface CharacterClass {
  id: number;
  name: string;
  initial_pv: number;
  initial_pm: number;
  level_pv: number;
  level_pm: number;
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
  campaign: Campaign | null;
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
}
