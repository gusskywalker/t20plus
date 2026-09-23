## Tag / Mechanism Design Principles

- Before adding a new tag/field, check for an existing canonical mechanism doing the same conceptual job (another sentinel, another `applies_when` key, another contextual tag) and extend that instead of writing a parallel one.
- Fork a tag (e.g. `mod_dmg` → `mod_spell_dmg`) only if the existing tag's resolution logic is coupled to a specific pipeline (weapon-combat math). Reuse it if it's a generic subject-agnostic stat bump (`mod_max_pm`, `skill`, etc.).
- A recurring "don't show this here" / "only applies when X" rule across 2+ screens: express it as a trait on the data itself (a tag/effect) and resolve it once in a shared helper — never a per-screen hardcoded id list.
- Don't invent a tag for a mechanic with no realistic future resolver (multi-target/AoE/positional — no combat engine, no consumer will ever exist). Leave it fully self-reported with a plain comment.
- DO seed the mechanically-correct tag now even when its resolver (a not-yet-built screen/system) doesn't exist yet — never fake a working-but-wrong shape just to have something resolve today.
- A power doing 2+ things only needs a `vessel` + children split when the pieces genuinely need different `usability` values (or one piece is itself a separately-shared power). If everything coexists under one `usability`, keep it one flat power.
- Don't add a DB column/field for a hypothetical future screen/query that hasn't actually been described — build exactly the described shape, ask before adding more.
- Before declaring a tag/mechanic unresolved or broken, grep EVERY consumer of the field/function, not just the first one found — this codebase has near-duplicate parallel pipelines (e.g. attack-modal's checkbox list vs. its separate auto-included passive/active list) that look like one but aren't.
- When proposing a new mechanic/schema piece, state the full resolution chain (table → column → lookup → power → effects), not just the data shape.
- Consider how a new mechanic shows on the sheet/roll screen only when it's cheap to generalize (flat numeric/reusable tags). For a weird one-off power, model `usability`/`pm_cost`/`prerequisites` and skip `effects`.
- Accept small mechanical gaps over blocking on 100% rule fidelity, especially where self-reporting already covers it. Flag the gap briefly and move on.
