import { Component, computed, inject } from '@angular/core';
import { Router } from '@angular/router';
import { DiceBadge } from '../../../shared/dice-badge/dice-badge';
import { TextInput } from '../../../shared/inputs/text-input/text-input';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { Race, God } from '../../../api.service';
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

const ENERGY_LABELS: Record<number, { text: string; color: string }> = {
  1: { text: 'Positiva', color: 'var(--color-tormenta-green)' },
  0: { text: 'Qualquer', color: 'var(--color-dark-cream)' },
  [-1]: { text: 'Negativa', color: 'var(--color-tormenta-red)' },
};

const SEPARATOR = "  •  ";

@Component({
  selector: 'app-character-creation-step-1',
  imports: [DiceBadge, TextInput, SearchableDropdown],
  templateUrl: './character-creation-step-1.html',
  styleUrl: './character-creation-step-1.scss',
})
export class CharacterCreationStep1 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  protected get races() {
    return this.staticRegistry.races;
  }

  protected get origins() {
    return this.staticRegistry.origins;
  }

  protected get gods() {
    return this.staticRegistry.gods;
  }

  protected get draftName() {
    return this.draft.name;
  }

  protected get draftRaceId() {
    return this.draft.raceId;
  }

  protected get draftOriginId() {
    return this.draft.originId;
  }

  protected get draftGodId() {
    return this.draft.godId;
  }

  protected readonly canContinue = computed(
    () =>
      this.draft.name().trim() !== '' &&
      this.draft.raceId() !== null &&
      this.draft.originId() !== null &&
      this.draft.godId() !== null,
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

  protected godEnergy = (god: God): SecondarySegment[] => {
    const energy = ENERGY_LABELS[god.energy_type ?? 0] ?? ENERGY_LABELS[0];
    return [{ text: energy.text, color: energy.color }];
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
