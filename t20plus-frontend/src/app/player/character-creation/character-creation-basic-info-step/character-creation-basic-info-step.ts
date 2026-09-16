import { Component, computed, effect, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { TextInput } from '../../../shared/inputs/text-input/text-input';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Modal } from '../../../shared/modals/modal/modal';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { Portrait, Race } from '../../../api.service';
import { SecondarySegment } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { environment } from '../../../../environments/environment';
import { ChoosingMechanicSection } from './basic-info-edge-cases/choosing-mechanic-section/choosing-mechanic-section';
import { MemoriaPostumaSection } from './basic-info-edge-cases/memoria-postuma-section/memoria-postuma-section';
import { QareenAncestrySection } from './basic-info-edge-cases/qareen-ancestry-section/qareen-ancestry-section';

// Humano and Lefou — RaceSeeder.php. Hardcoded, same convention as Ambição
// Herdada's own race id 22 check further down. Both share the exact same
// "2 perícias OR 1 perícia + a power" shape (Versátil / Deformidade) — only
// which power pool the second alternative draws from differs (general vs
// tormenta), handled in character-creation-powers-step.ts.
const HUMANO_RACE_ID = 15;
const LEFOU_RACE_ID = 20;
const OSTEON_RACE_ID = 42;
const QAREEN_RACE_ID = 44;

/* actual screen orders
step 1 -> character-creation-basic-info-step
step 2 -> character-creation-attributes-step
step 3 -> character-creation-age-step
step 4 -> character-creation-origin-step
step 5 -> character-creation-classes-step
step 6 -> character-creation-god-step
step 7 -> character-creation-skills-step
step 8 -> character-creation-items-step
step 9 -> character-creation-powers-step
step 10 -> character-creation-spells-step */

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
  selector: 'app-character-creation-basic-info-step',
  imports: [CardHeader, TextInput, NumberInput, SearchableDropdown, Modal, ChoosingMechanicSection, MemoriaPostumaSection, QareenAncestrySection],
  templateUrl: './character-creation-basic-info-step.html',
  styleUrl: './character-creation-basic-info-step.scss',
})
export class CharacterCreationBasicInfoStep {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  constructor() {
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

    // Clear Ambição Herdada's bonus power pick whenever race stops being
    // Meio-Elfo (id 22) — its own dropdown (step 9) only shows for that
    // race, same reasoning as the portraitId effect above.
    effect(() => {
      if (this.draft.raceId() !== 22) {
        this.draft.ambicaoHerdadaPowerId.set(null);
      }
    });

    // Clear Versátil/Deformidade's own toggle/picks whenever race stops
    // being Humano or Lefou — its own section only shows for those races,
    // same reasoning as the Ambição Herdada effect above.
    effect(() => {
      if (!this.hasChoosingMechanic) {
        this.draft.choosingMechanicChoice.set(null);
        this.draft.choosingMechanicSkillIds.set([]);
        this.draft.choosingMechanicPowerId.set(null);
      }
    });

    // Clear Memória Póstuma's own toggle whenever race stops being Osteon —
    // its own section only shows for that race, same reasoning as the
    // Ambição Herdada effect above.
    effect(() => {
      if (!this.isOsteon) {
        this.draft.memoriaPostumaChoice.set(null);
        this.draft.memoriaPostumaPowerId.set(null);
      }
    });

    // Clear Qareen's Resistência Elemental ancestry pick whenever race
    // stops being Qareen — its own section only shows for that race, same
    // reasoning as the Ambição Herdada effect above.
    effect(() => {
      if (!this.isQareen) {
        this.draft.qareenAncestryPowerId.set(null);
      }
    });

  }

  protected get isHumano(): boolean {
    return this.draft.raceId() === HUMANO_RACE_ID;
  }

  protected get isLefou(): boolean {
    return this.draft.raceId() === LEFOU_RACE_ID;
  }

  protected get hasChoosingMechanic(): boolean {
    return this.isHumano || this.isLefou;
  }

  // Deformidade's own alternative is a poder da Tormenta, not a poder
  // geral — ChoosingMechanicSection's second checkbox label reflects
  // whichever applies.
  protected get choosingMechanicPowerLabel(): string {
    return this.isLefou ? 'Poder da Tormenta' : 'Poder Geral';
  }

  protected get draftChoosingMechanicChoice() {
    return this.draft.choosingMechanicChoice;
  }

  protected get isOsteon(): boolean {
    return this.draft.raceId() === OSTEON_RACE_ID;
  }

  protected get draftMemoriaPostumaChoice() {
    return this.draft.memoriaPostumaChoice;
  }

  protected get isQareen(): boolean {
    return this.draft.raceId() === QAREEN_RACE_ID;
  }

  protected get draftQareenAncestryPowerId() {
    return this.draft.qareenAncestryPowerId;
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
      this.draft.baseLevel()! <= 20 &&
      (!this.hasChoosingMechanic || this.draft.choosingMechanicChoice() !== null) &&
      (!this.isOsteon || this.draft.memoriaPostumaChoice() !== null) &&
      (!this.isQareen || this.draft.qareenAncestryPowerId() !== null),
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
    // classIds only ever grows (step 3's setClassIdAt) — if its length no
    // longer matches baseLevel, the level was changed after picks already
    // existed for the old one (e.g. 5 reduced to 1), so every level-indexed
    // pick built against that stale length has to be cleared, or a stale
    // index would silently point at the wrong level (wrong class-power
    // dropdown options, stray spell slots on step 10). Already-matching
    // lengths (the common case) make this a no-op.
    if (this.draft.classIds().length !== (this.draft.baseLevel() ?? 0)) {
      this.draft.classIds.set([]);
      this.draft.classPowerIds.set([]);
      this.draft.classPowerIdsSourceKey.set(null);
      this.draft.chosenSpellIds.set([]);
      this.draft.arcanistaPathPowerId.set(null);
      this.draft.linhagemPowerId.set(null);
    }
    this.router.navigate(['/character-creation-attributes-step']);
  }

  // Same click-once-arms/click-again-confirms pattern as character-main's
  // own onDeclareDeadClick — a 1s window between the two clicks so a
  // careless double-click can't wipe the draft outright.
  protected readonly restartConfirming = signal(false);
  protected readonly restartReady = signal(false);
  private restartTimeoutId: ReturnType<typeof setTimeout> | null = null;

  protected onRestartClick(): void {
    if (!this.restartConfirming()) {
      this.restartConfirming.set(true);
      this.restartTimeoutId = setTimeout(() => this.restartReady.set(true), 1000);
      return;
    }
    if (!this.restartReady()) {
      return;
    }
    if (this.restartTimeoutId !== null) {
      clearTimeout(this.restartTimeoutId);
      this.restartTimeoutId = null;
    }
    this.draft.reset();
    this.restartConfirming.set(false);
    this.restartReady.set(false);
  }
}
