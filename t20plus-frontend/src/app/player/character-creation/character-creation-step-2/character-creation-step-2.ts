import { Component, computed, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { NumberStepper } from '../../../shared/inputs/number-stepper/number-stepper';
import { CharacterDraft } from '../character-draft';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { Race } from '../../../api.service';
import { formatDigits } from '../../../shared/utils/digit-display';

const STARTING_POINTS = 10;
const MIN_BASE = -1;
const MAX_BASE = 4;

const COST: Record<number, number> = {
  [-1]: -1,
  0: 0,
  1: 1,
  2: 2,
  3: 4,
  4: 7,
};

@Component({
  selector: 'app-character-creation-step-2',
  imports: [CardHeader, NumberStepper],
  templateUrl: './character-creation-step-2.html',
  styleUrl: './character-creation-step-2.scss',
})
export class CharacterCreationStep2 {
  private draft = inject(CharacterDraft);
  private staticRegistry = inject(StaticRegistry);
  private router = inject(Router);

  protected readonly formatDigits = formatDigits;

  constructor() {
    // Dev convenience: pre-fill a default 10-point spend so this screen
    // doesn't need to be clicked through by hand every test run. Only
    // applies to a fresh draft (all zero) so it never clobbers real picks.
    // TODO: remove once this stops being useful during development.
    const draft = this.draft;
    const untouched =
      draft.baseStr() === 0 &&
      draft.baseDex() === 0 &&
      draft.baseCon() === 0 &&
      draft.baseInt() === 0 &&
      draft.baseKnw() === 0 &&
      draft.baseCar() === 0;

    if (untouched) {
      draft.baseStr.set(2);
      draft.baseDex.set(2);
      draft.baseCon.set(2);
      draft.baseInt.set(2);
      draft.baseKnw.set(1);
      draft.baseCar.set(1);
    }
  }

  private readonly attributeConfigs = [
    { key: 'str', label: 'FOR', base: this.draft.baseStr, modField: 'mod_str' as const },
    { key: 'dex', label: 'DES', base: this.draft.baseDex, modField: 'mod_dex' as const },
    { key: 'con', label: 'CON', base: this.draft.baseCon, modField: 'mod_con' as const },
    { key: 'int', label: 'INT', base: this.draft.baseInt, modField: 'mod_int' as const },
    { key: 'knw', label: 'SAB', base: this.draft.baseKnw, modField: 'mod_knw' as const },
    { key: 'car', label: 'CAR', base: this.draft.baseCar, modField: 'mod_car' as const },
  ];

  private readonly selectedRace = computed(() => {
    const raceId = this.draft.raceId();
    return this.staticRegistry.races.find((race) => race.id === raceId) ?? null;
  });

  protected readonly otherPoints = computed(() => this.selectedRace()?.mod_other ?? 0);

  private readonly otherChosen = computed(() => this.draft.otherAttributes());

  protected readonly otherRemaining = computed(
    () => this.otherPoints() - this.otherChosen().length,
  );

  private readonly pointsSpent = computed(() =>
    this.attributeConfigs.reduce((sum, attr) => sum + COST[attr.base()], 0),
  );

  protected readonly pointsRemaining = computed(() => STARTING_POINTS - this.pointsSpent());

  protected readonly rows = computed(() => {
    const race = this.selectedRace();
    const remaining = this.pointsRemaining();
    const chosen = this.otherChosen();
    const otherLeft = this.otherRemaining();

    return this.attributeConfigs.map((attr) => {
      const base = attr.base();
      const raceMod = race ? (race[attr.modField as keyof Race] as number) : 0;
      const isOther = chosen.includes(attr.key);
      const racial = raceMod + (isOther ? 1 : 0);

      return {
        key: attr.key,
        label: attr.label,
        base: attr.base,
        max: this.maxAffordable(base, remaining),
        racial,
        total: base + racial,
        isOther,
        otherDisabled: !isOther && (otherLeft <= 0 || raceMod !== 0),
      };
    });
  });

  private maxAffordable(currentValue: number, remaining: number): number {
    const budget = COST[currentValue] + remaining;
    let max = currentValue;

    for (let value = MIN_BASE; value <= MAX_BASE; value++) {
      if (COST[value] <= budget) {
        max = Math.max(max, value);
      }
    }

    return max;
  }

  protected readonly canContinue = computed(
    () => this.pointsRemaining() === 0 && this.otherRemaining() === 0,
  );

  toggleOther(key: string): void {
    const current = this.draft.otherAttributes();

    if (current.includes(key)) {
      this.draft.otherAttributes.set(current.filter((k) => k !== key));
      return;
    }

    const attr = this.attributeConfigs.find((a) => a.key === key);
    const race = this.selectedRace();
    const raceMod = attr && race ? (race[attr.modField as keyof Race] as number) : 0;

    if (raceMod === 0 && current.length < this.otherPoints()) {
      this.draft.otherAttributes.set([...current, key]);
    }
  }

  back(): void {
    this.router.navigate(['/character-creation-step-1']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-step-3']);
  }
}
