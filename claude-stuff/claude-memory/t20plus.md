# Claude Memory

Single source of truth for how Claude should work in this repo — stack facts, domain rules, and every standing convention. Read this file whole at the start of a session, not piecemeal. Terse bullets, grouped by section, no history/dates/incident retelling — just what's true and what the rules are.

When something new is worth remembering, add a bullet to the relevant section below (or a new section if none fits) — never create a separate memory file. Don't track "current status"/progress here (which races/classes are done, etc.) — that's directly derivable from the actual seeder files, so a duplicated summary here is just another thing to go stale. Check the code for that.

## Stack & Environment

- Laravel 13 (PHP 8.3) backend `t20plus-api/`, Angular 21 SPA `t20plus-frontend/` (SCSS, no SSR, Phaser installed), MySQL db `t20plus`. Repo `F:\t20plus`.
- Angular pinned to 21 not 22, global Node/CLI stay on old pins — plusbarber compat, don't suggest bumping without checking global state first.
- WAMP MySQL 9.1 defaults to MyISAM — forced InnoDB via `config/database.php` `'engine' => 'InnoDB'`. Relevant to any new Laravel project on this same WAMP install too.
- Auth: Google-only login planned, not yet implemented.
- No combat engine exists or is planned — every mechanic not natively covered by a screen is self-reported by the player. Don't design as if one is coming.
- Detailed schema/tag docs live in the repo itself, not here: `claude-stuff/tag-system.md` (canonical tag/op/usability reference), `claude-stuff/tag-library.md` (terse tag lookup), `claude-stuff/t20-rules-summary.md`.

## Domain / Schema Facts

- `characters.base_str/dex/con/int/knw/car` = full effective saved value (race mod + free point already baked in at creation). Consumers read it directly — never `+ race.mod_str` on top, that double-counts.
- `races.mod_*`/`base_movement`/`base_size` are real fixed columns. `base_size` is an integer centered on Médio=0 (Minúsculo -2 … Colossal +3).
- `character_active_effects` is the single source of truth for currently-active powers (`characters.power_ids` was dropped, went stale). `character_levels.power_id` stays separate — historical provenance of which level granted what.
- `powers.source` is a fixed DB enum (`0008_create_powers_table.php`): `general, class, class_granted, divine_granted, race_granted, race_optional, tormenta, group, item_granted, consumable_granted, complication_granted, age_granted, origin_granted, specific, power_granted, general_action, condition_granted`. Never invent a new value — pick from this list or ask first. `specific` = bespoke-granted, invisible to every generic power-picker dropdown.
- `powers.duration` (`turn`/`scene`/`day`, nullable) is an abandoned-combat-engine leftover — today it ONLY gates whether an `active` power shows an on/off toggle on the sheet. Set it based on "should this be toggleable," not the rulebook's literal stated duration.
- `action_cost` (`movement`/`standard`/`complete`/`free`, etc.) is purely informative — nothing tracks or enforces actions-per-turn anywhere, there's no action economy system to consume it. Seed it accurately from the rules text, it just isn't resolved by any code.
- Icons: `icon_file_name` string column, flat file under `public/images/icons/` (no subfolders, no FK). Cropped via `scripts/crop-grid.php`.
- `gods` has no `grants` column — a `divine_granted` power carries a `{type:'god', god_id}` prerequisite instead.
- `item_improvements`/`item_enchantments` never carry a gameplay tag directly — only `{tag:'power', op:'grant', power_id, when_category?, when_type?}`; the granted power's own `effects` carry the real mechanic.
- Migration filenames: sequential zero-padded (`0001_create_users_table.php`...), not Laravel's timestamp prefix.

## Migration Conventions

- Edit the table's existing base `00NN_create_..._table.php` migration directly for ANY schema change (new column, type/nullable tweak) — never a new incremental migration. Only a genuinely new table gets a new file.
- Never run `php artisan migrate` / `migrate:fresh --seed` proactively after a schema/seeder edit. Report what changed and stop — wait for an explicit "migrate"/"seed it" every time, even if it was approved earlier in the same session (permission is per-request, not standing).

## Seeder Conventions

- Explicit per-record data always — never a bulk `Model::where(...)->update(...)` shortcut, even for a placeholder default across many rows.
- Never a `foreach`/loop to generate multiple `Power::create` (or similar) rows, even near-identical tiers — write each one out literally.
- Always `'icon_file_name' => null` explicitly when no art exists yet — never omit the key.
- Don't write `<br>No APP, ...` player-facing notes into `description` — the user adds those themselves. This is the standard move for a power that would otherwise need bespoke one-off resolver code: leave `effects` empty/omitted rather than building custom logic just for it, and the user documents the gap in the description themselves.
- A condition's description referencing another condition inlines that condition's CURRENT resolved numbers directly as flat facts — no "em vez de X" framing, no reader has to look anything up.
- Never guess concrete T20 mechanical data (weapon grip/purpose/damage/cost, any stat) from real-world intuition — T20 categorizes its own way. Ask or wait for verbatim rule text.

## Tag / Mechanism Design Principles

- Before adding a new tag/field, check for an existing canonical mechanism doing the same conceptual job (another sentinel, another `applies_when` key, another contextual tag) and extend that instead of writing a parallel one.
- Fork a tag (e.g. `mod_dmg` → `mod_spell_dmg`) only if the existing tag's resolution logic is coupled to a specific pipeline (weapon-combat math). Reuse it if it's a generic subject-agnostic stat bump (`mod_max_pm`, `skill`, etc.).
- A recurring "don't show this here" / "only applies when X" rule across 2+ screens: express it as a trait on the data itself (a tag/effect) and resolve it once in a shared helper — never a per-screen hardcoded id list.
- Don't invent a tag for a mechanic with no realistic future resolver (multi-target/AoE/positional — no combat engine, no consumer will ever exist). Leave it fully self-reported with a plain comment.
- DO seed the mechanically-correct tag now even when its resolver (a not-yet-built screen/system) doesn't exist yet — never fake a working-but-wrong shape just to have something resolve today.
- A power doing 2+ things only needs a `vessel` + children split when the pieces genuinely need different `usability` values (or one piece is itself a separately-shared power). If everything coexists under one `usability`, keep it one flat power.
- Don't add a DB column/field for a hypothetical future screen/query that hasn't actually been described — build exactly the described shape, ask before adding more.
- Before declaring a tag/mechanic unresolved or broken, grep EVERY consumer of the field/function, not just the first one found — this codebase has near-duplicate parallel pipelines (e.g. attack-modal's checkbox list vs. its separate auto-included passive/active list) that look like one but aren't.

## Frontend Conventions

- Never reuse/nest/share an SCSS class or selector across two different sections/cards, even when visually identical — duplicate the full class set instead, including interaction-state signals (each section owns its own).
- All code identifiers (CSS classes, TS method/signal names) must be English, even for a Portuguese-labeled feature — only literal displayed text and free comments are Portuguese.
- CSS/layout bugs: find the minimal root cause (a missing property, wrong value) via actual rendered measurements before proposing an architectural restructure (new component API, JS-computed positioning).
- Two independent mutations in one handler (e.g. create item + deduct tibares): fire both immediately, don't nest the second inside the first's `.subscribe()`. Only chain when there's a real data dependency (e.g. needing a server-assigned id back).
- Don't add a new VISIBLE UI element (button, card, section) to an already-real screen without a quick check-in first, even after the underlying feature was greenlit. Backend/logic alone: build freely.
- A shared/reusable component's self-clearing `effect()` must not assume it exclusively owns its bound signal — if multiple instances can bind the SAME underlying signal, the clear logic belongs at the list/caller level, not inside the reusable component.
- New shared components need an explicit `styleUrl` in the `@Component` decorator — Angular doesn't auto-wire a same-named stylesheet.
- `tsc --noEmit -p tsconfig.app.json` does NOT catch Angular template binding type errors — any change touching template bindings or shared-component wiring needs a real `ng build` (or a confirmed clean dev-server recompile) before calling it done.

## Comment Discipline

- Default to NO comments. Solo project, no team — a comment is only ever justified as a note for Claude's own future reading, never as documentation (that lives in `claude-stuff/*.md`).
- Migration files (`database/migrations/*.php`) get ZERO comments, no exceptions — a full pass already stripped every one out. What a column/tag means belongs in `tag-library.md` (terse lookup) and `tag-system.md` (deeper explanation, only when actually needed) — never inline on the `$table->json(...)` line itself.
- The reflex to explain a new column/field right where it's written (common in most codebases) is the wrong default here — it has to be caught BEFORE writing, not after. The moment a comment is about to go on a migration line, that's the cue to route it to tag-library.md/tag-system.md instead — don't wait to be corrected again.
- If you do write a comment, it exists purely to explain what the thing in front of you does — never to narrate history. Never write "we don't have X" or "when X is implemented" or any other past/future-tense justification. This includes present-tense phrasing that just rephrases a future-tense claim ("no penalty system to counteract exists" is the same violation wearing different tense). Concretely: never write "no consumer" / "purely informational" on a tag-library.md entry — it's the same violation, just recurring in a docs file instead of code, and it actively misleads the moment a real consumer gets built. State only what the tag/value means.
- This is NOT scoped to migrations/tag-library.md — it recurred a third time in plain new .ts code (roll-dice.ts/attack-modal.ts: "not consumed by anything yet") within the same session Destruidor's reroll went on to consume exactly that function. The reflex fires on ANY new function/signal/field, in any file, the moment its only current caller feels incomplete — that's precisely when "not used yet" feels most tempting to write, and precisely when it's guaranteed to go stale first. Trigger phrases to catch before typing: "not consumed", "no consumer", "not yet", "nothing reads this", "doesn't do anything yet". If a comment is needed at all, describe the shape/purpose only — never its current caller count.
- The one exception: `//TODO` comments, specifically to flag that something was left behind and must be revisited/updated once a given system is implemented. That's the only place forward-looking language belongs.
- Never restate a multi-step design discussion/plan inside a comment — one short factual line max, if any.
- This applies to `claude-stuff/*.md` design docs too (tag-system.md, tag-library.md), not just code — a doc describing "not yet built" status goes stale exactly the same way a code comment does. Describe the design/rationale in present tense there; build-status tracking belongs exclusively in `known-todos.md`, which is actively pruned (`~~done~~ 🎉`).

## Content / Docs Writing

- `claude-stuff/tag-library.md` entries: ONE line, `` `tag` (`fields`) -> what it does ``. No reasoning, no history, no justification/cross-reference. Design reasoning belongs in `tag-system.md` instead.

## Process / Collaboration

- When explaining a decision or how something works, use small decisive bullets, not walls of text — they don't get read. Prefer step form when the thing is a chain: "X fetches Y, then Y feeds Z, then Z does W" — each step its own short line.
- Never guess a game mechanic or rule interpretation when it's ambiguous — stop and ask. The user will either tell you directly or go research it. This is broader than just concrete stats (see the weapon-data rule in Seeder Conventions) — it covers any ruling you're not actually certain of.
- Before guessing how something works (or asking the user), investigate first: check `claude-stuff/tag-library.md`, then `claude-stuff/tag-system.md`, then the actual code where it's implemented/consumed — in that order. Only ask the user once that chain doesn't resolve it.
- For new mechanics, weigh both "how does this show on the sheet/roll screen now" and "how would a combat engine use this later" — but only for stuff that's cheap to generalize anyway (flat numeric/reusable tags). Don't force a future-engine shape onto a weird one-off power — model `usability`/`pm_cost`/`prerequisites`, skip `effects` on those.
- State the full resolution chain (table → column → lookup → power → effects) out loud when proposing a new mechanic/schema piece — not just the data shape in isolation.
- Accept small mechanical imprecision/gaps over blocking for 100% rule fidelity, especially where self-reporting already covers it. Flag the gap in one short comment and move on.
- No deadline exists — personal project, not in production, no rush to finish anything. Don't let pace pressure factor into any recommendation ("let's just self-report this to move faster"). Deferring a hard design question should be driven by genuinely not having enough information yet, never by wanting to move on quickly — there's always time to come back and design it properly once there's more to go on.
- When told existing logic is "fucked"/broken: strip to the bare confirmed-good minimum immediately, then rebuild rule-by-rule as each one is stated explicitly. Don't reverse-engineer intent and propose a full redesign upfront.
- Don't add an effect/tag that wasn't asked for just because a plausible-looking mechanism exists nearby — if the user says "just X, no effect," that's literal, not a starting point to embellish.
- When the user says they're going to think about/research an open design question themselves ("I'm checking if this will be reusable," "let me research"), that's not a request for a recommendation to act on — stop and wait, even if a fix seems obvious or was already being discussed. Caught twice in a row on the same decision (Natureza Venenosa's weapon-scoping shape): kept editing the seeder mid-thought instead of pausing once the user signaled they wanted to decide it themselves. Being confident in the technical answer doesn't make it your decision to make.
- Whenever a change needs manual verification in the running app (a new/reworked UI flow, a shared mechanism now used by a second consumer, anything not provable by `tsc`/`php -l` alone), add a line to `claude-stuff/known-to-test.md` so it isn't forgotten before the next play session.
- Default reflex when reading a power/spell's raw rule text is to treat every clause as mechanically significant and start building a pipeline for it — including a purely descriptive qualifier like "(como uma magia divina)" that's just explaining flavor/provenance, not a distinct rule. The user pre-emptively flags "forget that part"/"that's just fluff" specifically to head this off before it happens, since past behavior (unwatched) has been to over-engineer these. When a clause reads like it's explaining WHY something works rather than WHAT it changes, treat it as flavor by default and ask rather than assume it needs its own mechanism.

## Tooling

- Use the Grep tool for content search, never bash grep/rg — unreliable in this repo (comes back too wide/empty).
- `tsc --noEmit` for routine frontend checks — fast, sufficient most of the time. Reserve `ng build` for real checkpoints (finishing a feature, or template/shared-component changes — see Frontend Conventions).
- Icon crops: run with the best numeric guess and hand the result over — never self-declare a crop "clean." The user inspects visually and reports back exact values to use next.
