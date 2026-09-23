## Migration Conventions

- Edit the table's existing base `00NN_create_..._table.php` migration directly for ANY schema change (new column, type/nullable tweak) — never a new incremental migration. Only a genuinely new table gets a new file.
- Never run `php artisan migrate` / `migrate:fresh --seed` proactively after a schema/seeder edit. Report what changed and stop — wait for an explicit "migrate"/"seed it" every time, even if it was approved earlier in the same session (permission is per-request, not standing).

## Seeder Conventions

- Explicit per-record data always — never a bulk `Model::where(...)->update(...)` shortcut, even for a placeholder default across many rows.
- Never a `foreach`/loop to generate multiple `Power::create` (or similar) rows, even near-identical tiers — write each one out literally.
- Always `'icon_file_name' => null` explicitly when no art exists yet — never omit the key.
- A condition's description referencing another condition inlines that condition's CURRENT resolved numbers directly as flat facts — no "em vez de X" framing, no reader has to look anything up.
- Never guess concrete T20 mechanical data (weapon grip/purpose/damage/cost, any stat) from real-world intuition — T20 categorizes its own way. Ask or wait for verbatim rule text.