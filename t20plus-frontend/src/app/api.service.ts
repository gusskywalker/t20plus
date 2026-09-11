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
  weapon_id?: number;
  general_item_id?: number;
  value?: number;
  quantity?: number;
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

export interface SpellEnhancement {
  description: string;
  pm_cost: number;
  repeatable: boolean;
  is_truque: boolean;
  // A "muda" enhancement (rewrites part of the spell's description instead
  // of adding a numeric effect) — a group key, not a flag: at most one
  // checked entry per distinct group value, across the whole spell (e.g.
  // a muda-alcance entry and a muda-alvo entry use different group names
  // so they can be picked together, while two entries both changing alvo
  // share one name so only one of them can ever be checked).
  unique_change_group?: string;
  min_circle?: number;
  // What checking this enhancement actually grants — an array (not a
  // single tag/op/value) since one pick can carry more than one effect at
  // once (e.g. Arma de Jade's "+1 hit e dano" needs both mod_hit and
  // mod_dmg from the same checkbox). Each entry can carry its own
  // trigger/condition_id, same as Effect everywhere else. Absent/empty
  // means the enhancement is pure flavor text (self-reported, no
  // resolvable effect) — most muda-type entries are exactly this.
  effects?: Effect[];
  // This enhancement only makes sense once another one in the same spell
  // is already checked (e.g. Arma Espiritual's "aumenta o bônus na Defesa"
  // only means something once the +1 Defesa pick it builds on is active)
  // — index into the same spell's own enhancements array.
  requires_enhancement_index?: number;
  // This repeatable enhancement's stack count is capped by the max spell
  // círculo currently accessible through whichever class taught THIS
  // spell (see resolve-spell-caster-info.ts) — not the PM limit, and not
  // the character's best caster class if a different one taught it.
  max_stacks_by_max_circle?: boolean;
  // Only pickable by a devotee of this god (character.god_id must match)
  // — e.g. Arma de Jade's Lin-Wu-only upgrade. Same disable-the-checkbox
  // treatment as requires_enhancement_index, just checked against the
  // character instead of another enhancement's count.
  requires_god_id?: number;
}

export interface Spell {
  id: number;
  name: string;
  description: string;
  type: 'arcana' | 'divina' | 'universal';
  circle: number;
  school: string;
  // Drives which top-level branch resolveCast() (spell-casting-modal.ts)
  // resolves through — a pure dispatch key, not a replacement for the tag
  // system underneath. A damage spell that also inflicts a condition on
  // success (Adaga Mental) still stays 'damage'; the condition-on-success
  // logic (trigger/tag/condition_id) works exactly the same regardless of
  // this field. 'utility' is for spells with no mechanical resolution at
  // all (Alarme, Abençoar Alimentos).
  usability: 'damage' | 'buff' | 'debuff' | 'utility';
  // Only meaningful when usability is 'damage' — which type the
  // base_spell_dmg roll deals, needed so a power like Explosão Fulgente
  // ("só pode ser aplicado em magias que causam dano de fogo") can gate
  // itself generically instead of a hardcoded spell id list.
  damage_type: 'acid' | 'electricity' | 'fire' | 'cold' | 'light' | 'darkness' | 'essence' | 'magic' | 'psychic' | null;
  action_cost: string;
  range: string | null;
  // Split from a single old `effect` column — WHO/WHAT the spell targets
  // (e.g. "1 humanoide") vs. the spatial shape/size it covers (e.g. "cone
  // de 4,5m"). Usually only one or the other, occasionally both (e.g. Área
  // Escorregadia's "quadrado de 3m ou 1 objeto" splits into affected_area:
  // "quadrado de 3m" + affects: "1 objeto"). 'caster' is the one
  // standardized literal (self-only spells, e.g. Armadura Arcana) — a
  // future ally-targeting feature (buffing another character in the same
  // campaign) matches against it directly; every other value is still free
  // Portuguese text. See affectsLabel() in spell-casting-modal.ts for the
  // 'caster' -> "Você" display translation.
  affects: string | null;
  affected_area: string | null;
  duration: string | null;
  resistance: string | null;
  reagent_ids: number[] | null;
  effects: Effect[] | null;
  enhancements: SpellEnhancement[] | null;
  icon_file_name: string | null;
}

export interface Condition {
  id: number;
  name: string;
  description: string;
  type: string | null;
}

export interface Effect {
  tag: string;
  op: string;
  // Which pool this specific effect belongs in — absent/'passive' means it
  // always contributes (getActiveEffects folds it straight into Defesa/
  // skill/etc. totals); 'roll_active' means it's a fresh per-roll self-
  // report instead (skill-roll-modal's own checklist), same distinction
  // Power.usability already draws, just per-effect instead of per-row —
  // one spell's own effects array can mix both (e.g. a passive buff
  // alongside a situational skill bonus that only applies against one
  // target), which a single row/spell-level flag couldn't express
  // correctly.
  usability?: string;
  // When this entry applies, for an effect conditional on a spell's resist
  // outcome — absent means unconditional (always active). 'on_spell_success'
  // (target failed to resist) / 'on_spell_fail' (target resisted). Kept
  // orthogonal to `tag` on purpose: `tag` says WHAT domain this affects
  // (mod_spell_dmg, condition, ...), `trigger` says WHEN — so a resolver
  // never has to infer a hidden target from the trigger's own op (e.g. a
  // half-damage-on-resist entry is tag: 'mod_spell_dmg', op: 'multiply',
  // trigger: 'on_spell_fail' — not a made-up op living under a generic
  // 'on_spell_fail' tag that every consumer would have to special-case).
  trigger?: 'on_spell_success' | 'on_spell_fail';
  skill_id?: number;
  value?: number | string;
  // Only meaningful with op: 'add_per_level' — total bonus =
  // ceil(character.level / per_levels) * value, counting from level 1 (e.g.
  // Vontade de Ferro's "+1 PM a cada dois níveis" is value: 1, per_levels: 2,
  // granting at levels 1/3/5/7...).
  per_levels?: number;
  // Meaning is tag-specific (see claude-stuff/tag-library.md) — currently
  // only `advantage` (which roll it's granted for, e.g. 'hit').
  scope?: string;
  // Caps the resolved value — an attribute code (`knw`) or the literal
  // string `character_level`, never bare `level`. See
  // resolve-effect-sentinels.ts for how this and a sentinel `value` are
  // both turned into real numbers.
  limit?: string;
  // Entries sharing this value don't sum — only the highest value among
  // them applies. See tag-solver.ts.
  stack_group?: string;
  // Opposite of stack_group, and spell-only (not read by tag-solver.ts) —
  // entries sharing this value, from a single spell casting (its own base
  // effects plus any checked enhancements', repeated once per stack), are
  // summed into one combined effect before being persisted into
  // character_active_spell_effects. Needed so a spell's own total can then
  // get a single stack_group stamped onto it (e.g. Armadura Arcana's base
  // +5 and its repeatable "+1 Defesa" enhancement share sum_group so they
  // first become one +N mod_def entry, which only then competes against
  // worn armor's own bonus — stamping stack_group on each piece
  // separately would wrongly pit them against each other too, since
  // resolveTag's dedup keeps only the single highest entry per group).
  // Absent means never merged with anything, same as stack_group's own
  // absent-default. See spell-casting-modal.ts's resolveCast.
  sum_group?: string;
  // Only meaningful with tag: 'power', op: 'grant' (item_improvements/
  // item_enchantments granting a power onto whichever item carries them —
  // see get-item-granted-effects.ts). when_category/when_type narrow the
  // grant to a specific item_type/type (e.g. Matéria Vermelha's weapon vs.
  // armor vs. shield branches) — absent means the grant always applies.
  power_id?: number;
  when_category?: string;
  when_type?: string;
  // Only meaningful with tag: 'mod_dmg', op: 'extra_die' — steps this die's
  // own notation up by one every N character levels past level 1 (e.g.
  // Executor: value '1d6', die_steps_per_levels 4). See step-extra-die.ts.
  die_steps_per_levels?: number;
  // Only meaningful with tag: 'skill_group' — which attribute's skills this
  // targets (matched against Skill.key_attribute).
  attribute?: string;
  // Only meaningful with tag: 'skill_group' — one skill id carved out of the
  // group (e.g. Matéria Vermelha's penalty excludes Intimidação).
  exclude_skill_id?: number;
  // Only meaningful with tag: 'waive_weapon_proficiency' — which weapon
  // catalog ids this waiver covers (e.g. Arquearia Élfica's bows). Hardcoded
  // ids rather than a real weapon category, since there's no "bow"/etc.
  // grouping in the schema yet — same pattern as weapon-ammo-solver.ts's
  // AMMO_COMPATIBLE_WEAPON_IDS.
  weapon_ids?: number[];
  // Only meaningful with tag: 'condition', op: 'inflict' (or a bespoke
  // spell-specific tag like 'on_sono_cast', not yet migrated to the
  // trigger/tag split) — which Condition this casts informationally shows
  // as a "Causou X" breakdown line. Never applied to any tracked target/
  // state — purely display text, same as attack-modal's push/ignore-RD
  // lines.
  condition_id?: number;
  // Only meaningful alongside condition_id — for a spell whose own text
  // makes the actual outcome depend on state we don't track (e.g. Explosão
  // Fulgente: Cego the first time this scene, Ofuscado if reapplied — we
  // have no scene/turn tracker to know which). Shown as "Causou A ou B"
  // instead of silently picking one, same self-report philosophy as the
  // Passou/Falhou choice itself — the player already knows which applies.
  alt_condition_id?: number;
}

// Scopes WHEN a power counts (currently equipped weapon; may grow to cover
// other runtime context later) — distinct from `prerequisites`, which gates
// having the power at all. See powers.applies_when migration comment.
export interface AppliesWhen {
  weapon_grip?: string;
  weapon_purpose?: string[];
  weapon_ability?: number;
  // Hardcoded catalog ids — for restrictions that don't map to any
  // grip/purpose/ability distinction (e.g. Arquearia Élfica's "arcos,"
  // which purpose alone can't isolate from firearms). Same pattern as
  // proficiency-penalty-solver.ts's waive_weapon_proficiency.weapon_ids.
  weapon_ids?: number[];
  weapon_any?: { grip?: string; purpose?: string; ability?: number }[];
  // Only meaningful for a usability: 'spell_enhancement' power — which
  // spell action_cost values it's allowed to attach to (e.g. Magia
  // Acelerada only applies to movement/standard/complete spells, not ones
  // already free/reaction). Checked in spell-casting-modal.ts against the
  // spell actually being cast, same "runtime context" role weapon_* plays
  // for attack-modal.
  spell_action_costs?: string[];
  // Only meaningful for a usability: 'spell_enhancement' power on a
  // 'damage' spell — which damage_type values it's allowed to attach to
  // (e.g. Explosão Fulgente only applies to fire-damage spells). A damage
  // spell always has a resistance test by construction (that's the whole
  // Passou/Falhou flow), so no separate resistance check is needed here.
  spell_damage_types?: string[];
}

export interface Prerequisite {
  type: string;
  attribute?: string;
  min?: number;
  power_id?: number;
  // "Any one of these" — same OR-across-array shape as class_ids/race_ids,
  // for a prerequisite satisfied by having ANY one of several powers
  // (e.g. Armadilheiro's "um poder de armadilha") rather than one specific
  // power_id. Named _any (not power_ids) since a plain array name would
  // read as ambiguous with AND-across-separate-entries, which is already
  // how multiple required powers are expressed (see tag-system.md).
  power_ids_any?: number[];
  class_ids?: number[];
  min_level?: number;
  skill_id?: number;
  god_ids?: number[];
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
  applies_when: AppliesWhen | null;
  icon_file_name: string | null;
}

export interface Accessory {
  id: number;
  name: string;
  description: string;
  cost: number; // -1 = not purchasable
  slots: number;
  effects: Effect[] | null;
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
  effects: Effect[] | null;
  is_exoteric: boolean;
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
  is_firearm: boolean;
  grip: string;
  base_dmg: string;
  base_margin: number;
  base_multiplier: number;
  base_reach: number;
  damage_type: string;
  slots: number;
  ability_ids: number[] | null;
  effects: Effect[] | null;
  pre_applied_upgrade_ids: { improvement_ids?: number[]; enchantment_ids?: number[] } | null;
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
  type: 'tools' | 'alchemic' | 'food' | 'potion' | 'ammo';
  cost: number; // -1 = not purchasable
  slots: number;
  icon_file_name: string | null;
  effects: Effect[] | null;
  consumable: boolean;
  base_dmg: string | null; // dice notation, e.g. "1d6" — only thrown alchemic items use this
}

export interface ItemRestrictions {
  grip?: string;
  purpose?: string;
  damage_type?: string;
  is_firearm?: boolean;
  type?: string;
}

export interface ItemImprovement {
  id: number;
  name: string;
  description: string;
  is_material: boolean;
  extra_cost: Record<string, number> | null; // {category: cost} — can vary by category
  categories: string[];
  restrictions: ItemRestrictions | null;
  effects: Effect[] | null;
  prerequisites: number[] | null;
  incompatible_ids: number[] | null;
}

export interface ItemEnchantment {
  id: number;
  name: string;
  description: string;
  categories: string[];
  restrictions: ItemRestrictions | null;
  effects: Effect[] | null;
  prerequisites: number[] | null;
  incompatible_ids: number[] | null;
}

export interface WeaponAbility {
  id: number;
  name: string;
  description: string;
  power_ids: number[] | null;
}

export interface CharacterLevelRow {
  id: number;
  character_id: number;
  level: number;
  class_id: number;
  class_level: number;
  power_id: number | null;
  spell_ids: number[] | null;
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
  // Weapon-size offset (Reduzida -1 .. Gigante 2) — only meaningful for
  // item_type 'weapon', but present (default 0/Normal) on every row.
  weapon_size: number;
  // Player-given nickname, set via Melhorar Item — null until named.
  custom_name: string | null;
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
  is_favorite: boolean;
  // Per-character customization for this specific granted-power instance,
  // shaped exactly like Power.effects — for open-ended player choices a
  // shared catalog Power row can't represent (e.g. Espião's "escolha uma
  // perícia... use Carisma", different per character). Folded into
  // getActiveEffects() alongside the power's own effects.
  custom_effect?: Effect[] | null;
}

export interface CharacterActiveSpellEffectRow {
  id: number;
  character_id: number;
  spell_id: number;
  // The final, already-resolved effects for THIS casting — never
  // re-derived from spell_id + chosen_enhancement_indices, same
  // "computed once at cast time" rule as everywhere else this shape shows
  // up. Folded into getActiveEffects() directly (no per-level scaling, no
  // power lookup — unlike CharacterActiveEffectRow, there's no catalog row
  // to join against).
  effects: Effect[];
  // Display-only — which of the spell's own enhancements were picked for
  // this casting. Never read by any calculator.
  chosen_enhancement_indices?: number[] | null;
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
  current_size: number;
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
  // Same rule — backend method is activeSpellEffects(), JSON key active_spell_effects.
  active_spell_effects?: CharacterActiveSpellEffectRow[];
  // Same rule — backend method is golpesPessoais(), JSON key golpes_pessoais.
  golpes_pessoais?: CharacterGolpePessoalRow[];
}

export interface CreateCharacterLevel {
  level: number;
  class_id: number;
  class_level: number;
  power_id: number | null;
  spell_ids?: number[];
}

export interface CreateCharacterInventoryItem {
  item_type: 'accessory' | 'armor' | 'weapon' | 'shield' | 'general_item';
  item_id: number;
  worn: boolean;
  quantity?: number; // defaults to 1 backend-side if omitted
  weapon_size?: number; // defaults to 0 backend-side if omitted — only meaningful for item_type 'weapon'
}

/** Everything character-creation-step-9's continue() sends in one request — see player/character-creation/character-payload.ts. */
export interface CreateCharacterPayload {
  name: string;
  base_str: number;
  base_dex: number;
  base_con: number;
  base_int: number;
  base_knw: number;
  base_car: number;
  current_size: number;
  race_id: number | null;
  origin_id: number | null;
  god_id: number | null;
  portrait_id: number | null;
  trained_skill_ids: number[];
  age: number | null;
  age_bracket: string | null;
  complication_ids: number[];
  power_ids: number[];
  // Per-power custom_effect for open-ended picks a shared Power row can't
  // represent (e.g. Espião's freely chosen skill_attribute target) —
  // matched to its active_effect row by power_id on the backend.
  custom_effects: { power_id: number; custom_effect: Effect[] }[];
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

  googleLogin(accessToken: string): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/auth/google-login`, { access_token: accessToken });
  }

  createCharacter(payload: CreateCharacterPayload): Observable<Character> {
    return this.http.post<Character>(`${this.apiUrl}/characters`, payload);
  }

  getCharacter(id: number | string): Observable<Character> {
    return this.http.get<Character>(`${this.apiUrl}/characters/${id}`);
  }

  updateCharacter(
    id: number | string,
    payload: Partial<Pick<Character, 'current_pv' | 'current_pm' | 'tibares' | 'xp' | 'base_str' | 'base_dex' | 'base_con' | 'base_int' | 'base_knw' | 'base_car' | 'is_dead'>>,
  ): Observable<Character> {
    return this.http.patch<Character>(`${this.apiUrl}/characters/${id}`, payload);
  }

  destroyCharacter(id: number | string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/characters/${id}`);
  }

  createCharacterInventoryItem(characterId: number | string, payload: Omit<CreateCharacterInventoryItem, 'worn'>): Observable<CharacterInventoryRow[]> {
    return this.http.post<CharacterInventoryRow[]>(`${this.apiUrl}/characters/${characterId}/inventory`, payload);
  }

  updateCharacterInventoryItem(
    characterId: number | string,
    inventoryId: number,
    payload: Partial<Pick<CharacterInventoryRow, 'worn' | 'improvement_ids' | 'enchantment_ids' | 'custom_name' | 'quantity'>>,
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

  addCharacterActiveSpellEffect(
    characterId: number | string,
    spellId: number,
    effects: Effect[],
    chosenEnhancementIndices: number[],
  ): Observable<CharacterActiveSpellEffectRow[]> {
    return this.http.post<CharacterActiveSpellEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-spell-effects`, {
      spell_id: spellId,
      effects,
      chosen_enhancement_indices: chosenEnhancementIndices,
    });
  }

  destroyCharacterActiveSpellEffect(characterId: number | string, activeSpellEffectId: number): Observable<CharacterActiveSpellEffectRow[]> {
    return this.http.delete<CharacterActiveSpellEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-spell-effects/${activeSpellEffectId}`);
  }

  updateCharacterActiveEffect(characterId: number | string, activeEffectId: number, isActive: boolean): Observable<CharacterActiveEffectRow[]> {
    return this.http.patch<CharacterActiveEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-effects/${activeEffectId}`, {
      is_active: isActive,
    });
  }

  updateCharacterActiveEffectFavorite(characterId: number | string, activeEffectId: number, isFavorite: boolean): Observable<CharacterActiveEffectRow[]> {
    return this.http.patch<CharacterActiveEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-effects/${activeEffectId}`, {
      is_favorite: isFavorite,
    });
  }

  destroyCharacterActiveEffect(characterId: number | string, activeEffectId: number): Observable<CharacterActiveEffectRow[]> {
    return this.http.delete<CharacterActiveEffectRow[]>(`${this.apiUrl}/characters/${characterId}/active-effects/${activeEffectId}`);
  }

  createCharacterLevel(characterId: number | string, payload: { class_id: number; power_id: number | null }): Observable<Character> {
    return this.http.post<Character>(`${this.apiUrl}/characters/${characterId}/levels`, payload);
  }

  destroyHighestCharacterLevel(characterId: number | string): Observable<Character> {
    return this.http.delete<Character>(`${this.apiUrl}/characters/${characterId}/levels/highest`);
  }

  updateCharacterGolpePessoal(
    characterId: number | string,
    golpePessoalId: number,
    name: string,
    powerIds: number[],
  ): Observable<CharacterGolpePessoalRow[]> {
    return this.http.patch<CharacterGolpePessoalRow[]>(`${this.apiUrl}/characters/${characterId}/golpes-pessoais/${golpePessoalId}`, {
      name,
      power_ids: powerIds,
    });
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

  getSpells(): Observable<Spell[]> {
    return this.http.get<Spell[]>(`${this.apiUrl}/spells`);
  }

  getConditions(): Observable<Condition[]> {
    return this.http.get<Condition[]>(`${this.apiUrl}/conditions`);
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

  getItemImprovements(): Observable<ItemImprovement[]> {
    return this.http.get<ItemImprovement[]>(`${this.apiUrl}/item-improvements`);
  }

  getItemEnchantments(): Observable<ItemEnchantment[]> {
    return this.http.get<ItemEnchantment[]>(`${this.apiUrl}/item-enchantments`);
  }

  getWeaponAbilities(): Observable<WeaponAbility[]> {
    return this.http.get<WeaponAbility[]>(`${this.apiUrl}/weapon-abilities`);
  }
}
