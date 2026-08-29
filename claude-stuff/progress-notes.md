# T20Plus progress notes

## Where things stand (2026-08-29)

Seeded lookup tables: `races` (55), `origins` (132), `gods` (88), `skills` (29, descriptions with `<br>`-joined paragraphs), `classes` (Guerreiro only — deliberately building the whole flow around one class before adding more), `powers` (Ataque Especial x5 tiers so far).

`powers.prerequisites` and `powers.power_effects` are both flat JSON arrays of typed entries (`{type: 'attribute'|'power'|'class'|'skill', ...}` / `{tag, op, value}`), kept in one column each on purpose — see the `project-t20plus-schema` memory for the full reasoning (short version: the user wants every prereq for a power in one reachable place, not split across pivot tables, even though that trades away FK integrity for `power`/`class` references).

`character_levels` is an event log (one row per level-up, ever), not a mutable "current level" pivot — that's how multiclass order/history gets preserved.

Character-creation wizard: steps 1–3 built (name/race/origin/god → point-buy stats → Classe Inicial). Step 4 not started.

## Open thread — pick this up first

Step 3's "PV Inicial X • PM Inicial Y" (shown inside the Classe Inicial dropdown while it's open, via `secondaryFn`) should also keep showing after a class is picked and the dropdown closes.

- Attempt 1: plain line below the closed input — worked, was approved.
- Attempt 2: nest it inside the same bordered box as the input — user said "scrap it, I have a better idea" mid-build and manually reverted `searchable-dropdown.html`/`.ts` back to baseline before saying what the idea was.

**Don't re-attempt either approach — ask what the better idea was.**
