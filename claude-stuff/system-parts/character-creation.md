# Character creation

A wizard of routed steps that fills one `CharacterDraft`, then posts a flattened payload that the backend saves in one transaction.

## Steps (routes, in order)
- `character-creation-basic-info-step`, `-attributes-step`, `-age-step`, `-origin-step`, `-classes-step`, `-god-step`, `-skills-step`, `-items-step`, `-powers-step`, `-spells-step`. Each is a component under `player/character-creation/`.
- `character-creation-saving` is the modal that posts the payload. It stays up for a minimum time, then navigates to `/player` on success; on error it closes and the player stays to retry.
- `character-creation-specifics/` holds section components for one-off choices (e.g. Espião's skill).

## `CharacterDraft`
- An injectable provided on the wizard route, so one instance lives for the whole wizard. Every field is a signal.
- Mirrors itself into localStorage on every change (`character-draft-storage.ts`, `CharacterDraftSnapshot`); read once at construction so a refresh keeps progress. `reset()` clears it (a real save, or Reiniciar Criação).
- `grantedPowerIds` is the set of every power id on the draft, from every source at once: origin grants, god powers, complication and age-bracket powers, the level-up picks (`classPowerIds`), `class_granted` powers the class levels qualify for, `general_action` powers, `race_granted` powers, `choice_power` picks, and the one-off choice ids. Powers named by a `removes_power` effect are dropped last. It doesn't record where an id came from; each picker adds its own current pick back into its list.
- The draft satisfies the shape `getActiveEffects` and `calculateStatBonus` read: `base_*` getters (raw input + free point + race mod), `level`, and `active_effects` (one placeholder row per power id, `is_active` for passive). Wizard previews call those helpers with the draft itself.

## Companions in the draft
- `grantedCompanionIds` are the companions the picked powers grant (`companion` `grant` effects).
- `companionGrantedPowerIds` are the powers those companions grant (`character_related_effects`, plus what those grant in turn). They are not in `grantedPowerIds`, so the payload never saves them as own powers.
- `active_effects` also carries a row for each companion-granted power (placeholder `source_companion_id`), so effect previews count them.
- `grantedSpellIds` are the spells the draft's powers (own and companion-granted) grant as known via `grant_spell`.

## Spells step
- Slots come from `resolveCasterSpellSlots`; each slot's options from `resolveSlotSpellOptions`, minus spells picked in another slot and minus `grantedSpellIds`.
- The Tatuagem Mística, Canção dos Mares and Magia das Fadas dropdowns and the limited-choice (`limit_spell_choices`) pools also exclude `grantedSpellIds` and each other's picks.
- A slot pick that becomes a granted spell is cleared.

## Payload (`buildCharacterPayload`)
- Flat resulting facts, no wizard provenance: name, base attributes (raw + other + race mod), size, race/origin/god/portrait ids, `trained_skill_ids`, age, `complication_ids`, tibares, `levels`, `inventory`.
- `power_ids` = `grantedPowerIds` plus the powers those grant that are not already roots; a root covered by a `levels[].power_id` row is not repeated.
- `levels`: one row per character level with `class_id`, `class_level`, `power_id` (from class level 2) and the class's chosen `spell_ids`.
- `custom_effects` carries per-power choices a shared power row can't hold (Espião skill, Tatuagem Mística/Canção dos Mares/Magia das Fadas spells, limited spell choices, Tradição Perdida, skill-bonus choices). `satisfied_power_ids` marks powers reached from two sources.

## Backend save (`CharacterController::store`)
- In one transaction: creates the character and its `levels` (a level's power contributes its `grant_spell` ids to `spell_ids` and its discount ids to `other_source_spell_ids`), `inventory`, four hands and five accessory slots.
- One `character_active_effects` row per `power_ids` entry (`is_active` for passive, `custom_effect`, `other_sources_state`). It also enables hands, lands discount spell ids on the first level, and collects natural weapons.
- Each granted power with a `companion` `grant` effect adds that companion (`grantCompanion`), which grants its powers and spells.
- Ends with `syncItemPowers`, then returns the character with levels, inventory, hands, accessories, active effects, spell effects, golpes pessoais and companions.
