import { Injectable, signal } from '@angular/core';

/**
 * In-progress character being built across the creation wizard's steps.
 * Provided at the character-creation route, not root — a fresh instance
 * is created on entering the flow and discarded on leaving it, so there's
 * no stale draft to clean up.
 */
@Injectable()
export class CharacterDraft {
  name = signal('');
  raceId = signal<number | null>(null);
  originId = signal<number | null>(null);
  godId = signal<number | null>(null);
  level = signal<number | null>(null);

  /** One class id per character level, index 0 = level 1 (Classe Inicial). */
  classIds = signal<(number | null)[]>([]);

  baseStr = signal(0);
  baseDex = signal(0);
  baseCon = signal(0);
  baseInt = signal(0);
  baseKnw = signal(0);
  baseCar = signal(0);

  /** Attribute keys ('str', 'dex', ...) chosen for a race's mod_other points. */
  otherAttributes = signal<string[]>([]);

  /** Step 4: per origin choice-group (index-aligned), indices into that group's options[] that were picked. */
  originChoices = signal<number[][]>([]);
}
