# Backend Conventions

@../claude-stuff/code-conventions/migration-and-seeder-conventions.md

Before adding or changing a tag/effect: read `claude-stuff/code-conventions/tag-conventions.md`, then `claude-stuff/t20plus-stuff/tag-library.md` (and `tags-in-depth.md` if unclear).

## Modeling powers as tags

- A power's effect tags should read as what the description tells the reader. Reading only the tags, you should understand the power without the description.
- Example: "Sempre que faz um ataque corpo a corpo, você pode sofrer –2 no teste de ataque para receber +5 na rolagem de dano." is a roll-active power with two effects: `mod_hit` add -2, and `mod_dmg` add 5.
- A newly seeded power starts with `'icon_file_name' => null`, never an invented file name. After a bulk seeding pass the user asks for the list of nulls to generate the icons.
- Exception: a child power split off a parent or vessel (`power_granted`, e.g. a "(Descanso)" child) reuses the parent's `icon_file_name`, `null` only when the parent has none.
- If part of a description isn't modeled, it simply isn't modelled. Never build bespoke resolver logic or invent a tag just to cover it.
- The user may add a `<br><br>No APP, ...` note to the description telling the player what to do about the gap, or leave it ignored. Never write those notes yourself.
