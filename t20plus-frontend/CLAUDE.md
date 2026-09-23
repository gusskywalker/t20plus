# Frontend Conventions

## SCSS

- Every distinct row/section/card/element in a component gets its own class, even when it looks identical to another one in the same file. Never share, reuse or nest a class between two distinct things — duplicate the full class set instead, including interaction-state signals (each one owns its own).
- Why: a visual inconsistency must be debuggable deterministically — changing a class must only ever affect THIS row/section, nothing else. A longer, repetitive stylesheet is preferred over anything clever (no shared/generic classes across distinct things, no mixins/abstractions to dedupe styles).
- Example: three rows that look the same get `.power-row`, `.spell-row`, `.item-row` each with the full style set, not one shared `.row`.
- `src/styles.scss` holds the global, mostly-static styling, used to keep a consistent pattern: the Tormenta `@font-face`, the color/font CSS variables (`--color-*`, `--font-*`), base `html`/`body`/heading styles, and the shared UI primitives (`.card`, `.inner-card`, `.btn-primary/secondary/tertiary`, `.button-row`, `.no-scrollbar`). Using those primitives (e.g. `.inner-card`) is fine and expected — the no-sharing rule is about classes written in component stylesheets. Use its variables instead of hardcoding colors/fonts. Component-specific styling stays in the component's own stylesheet.
- Margins use multiples of 20px (20, 40...) — 10, 12, 24 are used on VERY specific places, always by the user when styling something, never by Claude by default. Using individual lines and margins is ALWAYS preferred over adding gaps to a section.
- CSS/layout bugs: find the minimal root cause (a missing property, wrong value) via actual rendered measurements before proposing an architectural restructure (new component API, JS-computed positioning).

## Code

- All code identifiers (CSS classes, TS method/signal names) must be English, even for a Portuguese-labeled feature — only literal displayed text and free comments are Portuguese.
- Two independent mutations in one handler (e.g. create item + deduct tibares): fire both immediately, don't nest the second inside the first's `.subscribe()`. Only chain when there's a real data dependency (e.g. needing a server-assigned id back).
- Don't add a new VISIBLE UI element (button, card, section) to an already-real screen without a quick check-in first, even after the underlying feature was greenlit. Backend/logic alone: build freely.

## Components

- A shared/reusable component's self-clearing `effect()` must not assume it exclusively owns its bound signal — if multiple instances can bind the SAME underlying signal, the clear logic belongs at the list/caller level, not inside the reusable component.
- New shared components need an explicit `styleUrl` in the `@Component` decorator — Angular doesn't auto-wire a same-named stylesheet.

## Verification

- `tsc --noEmit -p tsconfig.app.json` does NOT catch Angular template binding type errors — any change touching template bindings or shared-component wiring needs a real `ng build` (or a confirmed clean dev-server recompile) before calling it done.
