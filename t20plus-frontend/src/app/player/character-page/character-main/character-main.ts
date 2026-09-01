import { Component, effect, inject, input } from '@angular/core';
import { CardHeader } from '../../../shared/card-header/card-header';
import { UseCharacter } from '../../../shared/hooks/use-character';
import { ApiService, Character } from '../../../api.service';
import { calculateMaxPv } from '../../../shared/helpers/max-pv/max-pv';
import { calculateMaxPm } from '../../../shared/helpers/max-pm/max-pm';
import { environment } from '../../../../environments/environment';

// Cumulative XP required to REACH each level (Nível de Personagem table,
// claude-stuff/rules/levels-and-experience.md) — not a formula, the
// per-level jumps aren't regular (e.g. level 6→7 needs +6.000, but 7→8
// only +7.000 while the skill-bonus column skips a step), so this has to
// stay a lookup.
const XP_BY_LEVEL: Record<number, number> = {
  1: 0,
  2: 1000,
  3: 3000,
  4: 6000,
  5: 10000,
  6: 15000,
  7: 21000,
  8: 28000,
  9: 36000,
  10: 45000,
  11: 55000,
  12: 66000,
  13: 78000,
  14: 91000,
  15: 105000,
  16: 120000,
  17: 136000,
  18: 153000,
  19: 171000,
  20: 190000,
};

@Component({
  selector: 'app-character-main',
  imports: [CardHeader],
  templateUrl: './character-main.html',
  styleUrl: './character-main.scss',
})
export class CharacterMain {
  private useCharacter = inject(UseCharacter);
  private apiService = inject(ApiService);

  // Bound straight from the :id route segment — see withComponentInputBinding() in app.config.ts.
  id = input.required<string>();

  protected readonly characterQuery = this.useCharacter.characterQuery(this.id);

  constructor() {
    // First time the sheet loads a character whose current_pv/current_pm
    // were never initialized (null, not 0 — see the migration comment),
    // compute max and persist it as the starting current value. Only
    // fires once per character: after the PATCH + invalidate below, both
    // fields are no longer null, so this effect's own guard stops it from
    // firing again.
    effect(() => {
      const character = this.characterQuery.data();
      if (!character || (character.current_pv !== null && character.current_pm !== null)) {
        return;
      }
      const current_pv = character.current_pv ?? calculateMaxPv(character);
      const current_pm = character.current_pm ?? calculateMaxPm(character);
      this.apiService.updateCharacter(character.id, { current_pv, current_pm }).subscribe(() => {
        // Same convention as step 9's save — invalidate() refetches every
        // query under the 'characters' key (TanStack's default matching
        // is prefix-based), which covers both /player's list AND this
        // detail query in one call, not just this one instance.
        this.useCharacter.invalidate();
      });
    });
  }

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

  // XP needed to reach the NEXT level — the "z" in "XP y/z". Level 20 is
  // the cap (no level 21 row), so it just holds at its own threshold.
  protected xpForNextLevel(level: number): number {
    return XP_BY_LEVEL[level + 1] ?? XP_BY_LEVEL[20];
  }

  // "Gue 2/Bár 3/Caç 6" — first 3 letters of each class name + how many
  // character_levels rows belong to it, in the order each class first
  // appears (level order), not alphabetical, so it reads the way the
  // character was actually built. Abbreviated so a multiclass character
  // still fits on one row next to the "Classes" label.
  protected classSummary(character: Character): string {
    const counts = new Map<number, { name: string; count: number }>();
    for (const level of character.levels ?? []) {
      const existing = counts.get(level.class_id);
      if (existing) {
        existing.count++;
      } else {
        counts.set(level.class_id, { name: level.character_class?.name ?? '', count: 1 });
      }
    }
    return [...counts.values()].map(({ name, count }) => `${name.slice(0, 3)} ${count}`).join('/');
  }

  // base_* already IS the effective value — the race's fixed mod_* and the
  // "other" bonus point are both baked in at creation time (see
  // character-payload.ts), so nothing gets added here.
  protected readonly attributeRows = (character: Character) => [
    { label: 'Força', value: character.base_str },
    { label: 'Destreza', value: character.base_dex },
    { label: 'Constituição', value: character.base_con },
    { label: 'Inteligência', value: character.base_int },
    { label: 'Sabedoria', value: character.base_knw },
    { label: 'Carisma', value: character.base_car },
  ];
}
