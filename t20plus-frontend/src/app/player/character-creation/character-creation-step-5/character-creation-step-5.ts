import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Checkbox } from '../../../shared/inputs/checkbox/checkbox';
import { SearchableDropdown, SecondarySegment } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { God, Power } from '../../../api.service';

const ENERGY_LABELS: Record<number, { text: string; color: string }> = {
  1: { text: 'Positiva', color: 'var(--color-tormenta-green)' },
  0: { text: 'Qualquer', color: 'var(--color-dark-cream)' },
  [-1]: { text: 'Negativa', color: 'var(--color-tormenta-red)' },
};

@Component({
  selector: 'app-character-creation-step-5',
  imports: [CardHeader, Checkbox, SearchableDropdown],
  templateUrl: './character-creation-step-5.html',
  styleUrl: './character-creation-step-5.scss',
})
export class CharacterCreationStep5 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  private readonly startingClass = computed(() => {
    const classId = this.draft.classIds()[0] ?? null;
    return this.staticRegistry.classes.find((c) => c.id === classId) ?? null;
  });

  protected get gods() {
    return this.staticRegistry.gods;
  }

  protected get draftGodId() {
    return this.draft.godId;
  }

  protected godEnergy = (god: God): SecondarySegment[] => {
    const energy = ENERGY_LABELS[god.energy_type ?? 0] ?? ENERGY_LABELS[0];
    return [{ text: energy.text, color: energy.color }];
  };

  protected readonly picks = computed(() => this.startingClass()?.divine_power_picks ?? 1);

  protected readonly availablePowers = computed<Power[]>(() => {
    const godId = this.draft.godId();
    if (godId === null) {
      return [];
    }
    return this.staticRegistry.powers.filter(
      (power) =>
        power.source === 'divine_granted' &&
        (power.prerequisites ?? []).some((p) => p.type === 'god' && p.god_id === godId),
    );
  });

  constructor() {
    // Dev convenience: pre-fill so this screen doesn't need manual clicking
    // through every test run. TODO: remove once this stops being useful
    // during development.
    effect(() => {
      const gods = this.staticRegistry.gods;
      if (gods.length > 0 && this.draft.godId() === null) {
        this.draft.godId.set(gods[0].id);
      }
    });

    // Reset godPowerIds whenever the god actually changes — stale power ids
    // from a previous god wouldn't even match this god's prerequisites, but
    // clearing them explicitly avoids relying on that coincidence.
    effect(() => {
      const godId = this.draft.godId();
      if (godId === null) {
        return;
      }
      if (this.draft.godPowerIdsGodId() === godId) {
        return;
      }
      this.draft.godPowerIdsGodId.set(godId);
      this.draft.godPowerIds.set([]);
    });

    // Dev convenience: pre-select the first `picks` available powers so
    // this screen doesn't need manual clicking through every test run.
    // preFilledGodId is a plain field, not a signal — reading godPowerIds()
    // itself as the "already filled?" guard was the bug: unchecking the
    // pre-filled power drops it back to empty, which re-triggered this same
    // effect and immediately snapped it back, making every other option
    // look permanently disabled. A plain field can't be read as a
    // dependency, so this only ever fires once per god, win or lose.
    // TODO: remove once this stops being useful during development.
    let preFilledGodId: number | null = null;
    effect(() => {
      const godId = this.draft.godId();
      const powers = this.availablePowers();
      if (godId === null || powers.length === 0 || preFilledGodId === godId) {
        return;
      }
      preFilledGodId = godId;
      this.draft.godPowerIds.set(powers.slice(0, this.picks()).map((p) => p.id));
    });
  }

  protected isSelected(powerId: number): boolean {
    return this.draft.godPowerIds().includes(powerId);
  }

  protected readonly isCapped = computed(() => this.draft.godPowerIds().length >= this.picks());

  protected toggle(powerId: number): void {
    const current = this.draft.godPowerIds();
    if (current.includes(powerId)) {
      this.draft.godPowerIds.set(current.filter((id) => id !== powerId));
    } else if (!this.isCapped()) {
      this.draft.godPowerIds.set([...current, powerId]);
    }
  }

  protected readonly canContinue = computed(
    () => this.draft.godId() !== null && this.draft.godPowerIds().length === this.picks(),
  );

  back(): void {
    this.router.navigate(['/character-creation-step-4']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-step-6']);
  }
}
