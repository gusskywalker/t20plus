# Resistances

## The 3 resistance skills

- Fortitude: skill id 10, key attribute `con`.
- Reflexos: skill id 26, key attribute `dex`.
- Vontade: skill id 29, key attribute `knw`.

## Modeling a flat bonus to "testes de resistência"

- Text saying plain "testes de resistência" (all 3, unconditional) -> one `skill` / `add` effect per skill id (10, 26, 29), `usability: passive`.
- Example: Símbolo Sagrado (power 317).

## Modeling a bonus to a specific resistance (magic, poison, etc.)

- Text naming a specific kind of resistance (e.g. "resistência a magia +1") is NOT a separate stat — there's no dedicated tag for it.
- Model it the same way (`skill` / `add` to the relevant save skill id(s)), but `usability: roll_active` instead of `passive` — the user self-reports whether the roll in front of them is against that specific thing (magic, poison, etc.) and checks the row only then.
- Example: Patuá (power 14012), roll_active +1 to skill ids 10/26/29 for "resistência a magia +1".
