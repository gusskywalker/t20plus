import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { TextInput } from '../../../shared/inputs/text-input/text-input';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { Race } from '../../../api.service';
import { SecondarySegment } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';

const ATTRIBUTE_LABELS: Record<string, string> = {
  mod_str: 'FOR',
  mod_dex: 'DEX',
  mod_con: 'CON',
  mod_int: 'INT',
  mod_knw: 'SAB',
  mod_car: 'CAR',
};

const SIZE_LABELS: Record<number, string> = {
  '-2': 'Minúsculo',
  '-1': 'Pequeno',
  0: 'Médio',
  1: 'Grande',
  2: 'Enorme',
  3: 'Colossal',
};

const SEPARATOR = "  •  ";

@Component({
  selector: 'app-character-creation-step-1',
  imports: [CardHeader, TextInput, NumberInput, SearchableDropdown],
  templateUrl: './character-creation-step-1.html',
  styleUrl: './character-creation-step-1.scss',
})
export class CharacterCreationStep1 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  constructor() {
    // Dev convenience: pre-fill so this screen doesn't need manual clicking
    // through every test run. Only applies to a fresh draft (guards check
    // each field individually so it never clobbers a real pick).
    // TODO: remove once this stops being useful during development.
    if (this.draft.name() === '') {
      this.draft.name.set('Testando');
    }
    if (this.draft.level() === null) {
      this.draft.level.set(1);
    }

    effect(() => {
      const races = this.staticRegistry.races;
      if (races.length > 0 && this.draft.raceId() === null) {
        this.draft.raceId.set(races[0].id);
      }
    });

  }

  protected get races() {
    return this.staticRegistry.races;
  }

  protected get draftName() {
    return this.draft.name;
  }

  protected get draftRaceId() {
    return this.draft.raceId;
  }

  protected get draftLevel() {
    return this.draft.level;
  }

  protected readonly canContinue = computed(
    () =>
      this.draft.name().trim() !== '' &&
      this.draft.raceId() !== null &&
      this.draft.level() !== null &&
      this.draft.level()! >= 1 &&
      this.draft.level()! <= 20,
  );

  protected raceMods = (race: Race): SecondarySegment[] => {
    const stats = Object.keys(ATTRIBUTE_LABELS)
      .map((key) => ({ label: ATTRIBUTE_LABELS[key], value: (race as any)[key] as number }))
      .filter(({ value }) => value !== 0);

    if (race.mod_other !== 0) {
      stats.push({ label: 'Livre', value: race.mod_other });
    }

    return stats.flatMap(({ label, value }, index) => {
      const segments: SecondarySegment[] = [
        { text: `${label} ` },
        {
          text: `${value > 0 ? '+' : ''}${value}`,
          color: value > 0 ? 'var(--color-tormenta-green)' : 'var(--color-tormenta-red)',
        },
      ];

      if (index < stats.length - 1) {
        segments.push({ text: SEPARATOR, color: 'var(--color-medium-black)' });
      }

      return segments;
    });
  };

  protected raceDetails = (race: Race) => {
    const sizeLabel = SIZE_LABELS[race.base_size] ?? race.base_size;
    return {
      left: `Deslocamento ${race.base_movement}m`,
      right: `Tamanho ${sizeLabel}`,
    };
  };

  continue(): void {
    this.router.navigate(['/character-creation-step-2']);
  }
}
