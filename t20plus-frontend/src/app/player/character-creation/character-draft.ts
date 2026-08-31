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
  portraitId = signal<number | null>(null);

  /** Which raceId portraitId currently belongs to — same reasoning as originChoicesOriginId, since a portrait's available set depends on race. */
  portraitIdRaceId = signal<number | null>(null);

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

  /** Which originId originChoices currently belongs to — lets step 4 tell "origin changed, stale choices" apart from "same origin, re-rendering," even across remounting the step 4 component on navigation. */
  originChoicesOriginId = signal<number | null>(null);

  /** Step 5: power ids picked from the chosen god's divine_granted powers. */
  godPowerIds = signal<number[]>([]);

  /** Which godId godPowerIds currently belongs to — same reasoning as originChoicesOriginId. */
  godPowerIdsGodId = signal<number | null>(null);

  /** Step 6: per class skill-group (index-aligned), skill ids picked (options are already skill ids, unlike origin's GrantOption). */
  classSkillChoices = signal<number[][]>([]);

  /** Which race/class/origin/god combo classSkillChoices currently belongs to — same reasoning as originChoicesOriginId, but keyed on all four since each can change what's already trained (race/origin/god) or what the groups even are (class). */
  classSkillChoicesSourceKey = signal<string | null>(null);
}
