import { Component, computed, effect, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { TextInput } from '../../../shared/inputs/text-input/text-input';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Modal } from '../../../shared/modal/modal';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { Portrait, Race } from '../../../api.service';
import { SecondarySegment } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { environment } from '../../../../environments/environment';

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

@Component({
  selector: 'app-character-creation-step-1',
  imports: [CardHeader, TextInput, NumberInput, SearchableDropdown, Modal],
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
    if (this.draft.baseLevel() === null) {
      this.draft.baseLevel.set(1);
    }

    effect(() => {
      const races = this.staticRegistry.races;
      if (races.length > 0 && this.draft.raceId() === null) {
        this.draft.raceId.set(races[0].id);
      }
    });

    // Clear portraitId whenever the race actually changes — a previously
    // selected portrait may not even be in the new race's available set.
    effect(() => {
      const raceId = this.draft.raceId();
      if (raceId === null) {
        return;
      }
      if (this.draft.portraitIdRaceId() === raceId) {
        return;
      }
      this.draft.portraitIdRaceId.set(raceId);
      this.draft.portraitId.set(null);
    });

    // Dev convenience: pre-select the first available portrait for the
    // current race, same reasoning as the name/level/race pre-fills above.
    // Only fires while portraitId is still unset, so it never fights the
    // clear-on-race-change effect above or overwrites a real pick.
    // TODO: remove once this stops being useful during development.
    effect(() => {
      const portraits = this.availablePortraits();
      if (portraits.length > 0 && this.draft.portraitId() === null) {
        this.draft.portraitId.set(portraits[0].id);
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
    return this.draft.baseLevel;
  }

  protected readonly showPortraitModal = signal(false);

  // Tentative pick while the modal is open — only committed to the draft
  // on "Selecionar", discarded on "Cancelar" or backdrop dismissal.
  protected readonly tentativePortraitId = signal<number | null>(null);

  protected readonly availablePortraits = computed<Portrait[]>(() => {
    const raceId = this.draft.raceId();
    if (raceId === null) {
      return [];
    }
    return this.staticRegistry.portraits.filter((p) => p.race_ids?.includes(raceId));
  });

  protected readonly selectedPortrait = computed<Portrait | null>(() => {
    const portraitId = this.draft.portraitId();
    if (portraitId === null) {
      return null;
    }
    return this.staticRegistry.portraits.find((p) => p.id === portraitId) ?? null;
  });

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

  protected openPortraitModal(): void {
    this.tentativePortraitId.set(this.draft.portraitId());
    this.showPortraitModal.set(true);
  }

  protected pickTentativePortrait(portraitId: number): void {
    this.tentativePortraitId.set(portraitId);
  }

  protected confirmPortrait(): void {
    this.draft.portraitId.set(this.tentativePortraitId());
    this.showPortraitModal.set(false);
  }

  protected cancelPortraitModal(): void {
    this.showPortraitModal.set(false);
  }

  protected readonly canContinue = computed(
    () =>
      this.draft.name().trim() !== '' &&
      this.draft.raceId() !== null &&
      this.draft.portraitId() !== null &&
      this.draft.baseLevel() !== null &&
      this.draft.baseLevel()! >= 1 &&
      this.draft.baseLevel()! <= 20,
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
        // Two underscores colored to match the dropdown row's own
        // background — an invisible gap, since real spaces collapse.
        segments.push({ text: '__', color: 'var(--color-light-black)' });
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
