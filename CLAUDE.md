# Claude Memory

How Claude should work in this repo — stack facts and standing conventions. Area-specific rules live in the files listed under "Where to look".

When something new is worth remembering, add it to the matching file (this one for general rules, the area's own file otherwise) — never to a separate local memory file. Don't track "current status"/progress here (which races/classes are done, etc.) — that's directly derivable from the actual seeder files, so a duplicated summary here is just another thing to go stale. Check the code for that.

## Stack & Environment

- Laravel 13 (PHP 8.3) backend `t20plus-api/`, Angular 21 SPA `t20plus-frontend/` (SCSS, no SSR, Phaser installed), MySQL db `t20plus`. Repo `F:\t20plus`.
- Angular pinned to 21 not 22, global Node/CLI stay on old pins — plusbarber compat, don't suggest bumping without checking global state first.
- WAMP MySQL 9.1 defaults to MyISAM — forced InnoDB via `config/database.php` `'engine' => 'InnoDB'`. Relevant to any new Laravel project on this same WAMP install too.
- Auth: Google-only login planned, not yet implemented.
- No combat engine exists or is planned — every mechanic not natively covered by a screen is self-reported by the player. Don't design as if one is coming.

## Comment Discipline

- Comments only state what the thing in front of you is or does. Never narrate history or status: no "not implemented yet", "nothing consumes this", "when X exists" — they go stale and mislead later reads. Trigger phrases to catch before typing: "not yet", "no consumer", "not consumed", "nothing reads this", "purely informational".
- No comments at all in seeders, migrations, and similar data files, except `//TODO` (seeders) to flag something that must be revisited once a system exists. A finished `//TODO` is deleted, not rewritten. Modeling notes go in the reply, not the file.
- What a column/tag means goes in `t20plus-stuff/tag-library.md` / `tags-in-depth.md`, never inline. The same no-status rule applies to `claude-stuff/*.md` docs.

## Content / Docs Writing

- `claude-stuff/t20plus-stuff/tag-library.md` entries: ONE line, `` `tag` (`fields`) -> what it does ``. No reasoning, no history, no justification/cross-reference. Design reasoning belongs in `tags-in-depth.md` instead. "One line" means short enough not to wrap in the editor (~150 chars): no "(e.g. Power: ...)" walkthroughs, no explaining which modal reads it.

## Process / Collaboration

- Explain in short decisive bullets, not walls of text. For chains use step form, one short line per step.
- Never guess a game mechanic or rule interpretation. If it's ambiguous, stop and ask — the user will answer or go research it.
- Before asking how something works, investigate in order: `claude-stuff/t20plus-stuff/tag-library.md`, `claude-stuff/t20plus-stuff/tags-in-depth.md`, then the code that implements/consumes it. Ask only if that doesn't resolve it.
- Seeding data with existing tags needs no go-ahead. Any new or changed logic (new tag, resolver/modal/calculator change, schema field) is proposed first and written only after the user says go, even when the design felt agreed.
- Don't add a new VISIBLE UI element (button, card, section) to an already-real screen without a quick check-in first, even after the underlying feature was greenlit. Backend/logic alone: build freely.
- Build exactly what was asked. "Just X, no effect" is literal — don't add effects/tags because a similar mechanism exists nearby.
- Rule-text clauses that explain WHY something works (flavor/provenance, e.g. "(como uma magia divina)") are flavor, not mechanics. If unsure, ask.
- When the user says they're thinking about or researching a design question, stop and wait — no edits, no recommendation to act on, even if the fix seems obvious.
- When the user says existing logic is broken: strip to the confirmed-good minimum, then rebuild rule by rule as each is stated. Don't propose a full redesign upfront.
- No deadline, personal project. Don't let pace pressure shape a recommendation; defer a design question only when information is genuinely missing.
- `known-todos.md` and `known-to-test.md` are the user's own files — never edit them.

## Tooling

- Use the Grep tool for content search, never bash grep/rg — unreliable in this repo (comes back too wide/empty).
- `tsc --noEmit` for routine frontend checks — fast, sufficient most of the time. Reserve `ng build` for real checkpoints (finishing a feature, or any change touching template bindings/shared-component wiring — `tsc` doesn't catch Angular template type errors; see `t20plus-frontend/CLAUDE.md`).
- Icon crops: run with the best numeric guess and hand the result over — never self-declare a crop "clean." The user inspects visually and reports back exact values to use next.

## Where to look

Read the matching file before starting; don't rely on memory.

- Backend work: `t20plus-api/CLAUDE.md` (loads on its own). Frontend work: `t20plus-frontend/CLAUDE.md` (loads on its own).
- Touching powers, spells, characters or items (how the data works, one-off facts): list `claude-stuff/domain-specifics/` and read the files matching the area.
- Any doubt about a game rule: list `claude-stuff/tormenta-book-rules/` and read the matching file (`t20-rules-summary.md` first).
- Building/running the app locally: `claude-stuff/infra-stuff/`.
