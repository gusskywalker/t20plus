import { Component, computed, effect, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { TextInput } from '../../../shared/inputs/text-input/text-input';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Modal } from '../../../shared/modals/modal/modal';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { Portrait, Power, Race } from '../../../api.service';
import { SecondarySegment } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { environment } from '../../../../environments/environment';
import { ChoicePowerSection, ChoicePowerGroup } from './basic-info-edge-cases/choice-power-section/choice-power-section';
import { MemoriaPostumaSection, MemoriaPostumaChoice } from './basic-info-edge-cases/memoria-postuma-section/memoria-postuma-section';
import { QareenAncestrySection } from './basic-info-edge-cases/qareen-ancestry-section/qareen-ancestry-section';
import { DuendeSection } from './basic-info-edge-cases/duende-section/duende-section';
import { GolemSection } from './basic-info-edge-cases/golem-section/golem-section';
import { SuraggelVariantesSection } from './basic-info-edge-cases/suraggel-variantes-section/suraggel-variantes-section';
import { DUENDE_ANIMAL_POWER_ID } from '../../../shared/helpers/power-pick-constants/power-pick-constants';
import { ATTRIBUTE_ABBREVIATION_LABELS, CHARACTER_SIZE_LABELS } from '../../../shared/constants/translation-constants';

const MEMORIA_POSTUMA_RACE_IDS = [42, 55];
const QAREEN_RACE_ID = 44;
const DUENDE_RACE_ID = 60;
const GOLEM_RACE_ID = 61;
const SURAGGEL_RACE_IDS = [48, 49];

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


@Component({
  selector: 'app-character-creation-basic-info-step',
  imports: [CardHeader, TextInput, NumberInput, SearchableDropdown, Modal, ChoicePowerSection, MemoriaPostumaSection, QareenAncestrySection, DuendeSection, GolemSection, SuraggelVariantesSection],
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

    // Drops a choice_power pick once its holder power is no longer on the
    // draft (race or Chassi changed), along with the free skill picks the
    // dropped option's budget had funded.
    effect(() => {
      const granted = this.draft.grantedPowerIds();
      const picks = this.draft.choicePowerPicks();
      const staleKeys = Object.keys(picks).filter((key) => !granted.has(Number(key)));
      if (staleKeys.length === 0) {
        return;
      }
      const next = { ...picks };
      staleKeys.forEach((key) => delete next[Number(key)]);
      this.draft.choicePowerPicks.set(next);
      this.draft.classSkillChoices.set([]);
      this.draft.choosingMechanicSkillIds.set([]);
    });

    // Clear Memória Póstuma's own toggle whenever race stops being Osteon or
    // Yidishan — its own section only shows for those races, same reasoning
    // as the Ambição Herdada effect above.
    effect(() => {
      if (!this.hasMemoriaPostuma) {
        this.draft.memoriaPostumaChoice.set(null);
        this.draft.memoriaPostumaPowerId.set(null);
        this.draft.memoriaPostumaRaceAbilityPowerId.set(null);
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

    // Clear Duende's Natureza pick whenever race stops being Duende — its
    // own section only shows for that race, same reasoning as the Qareen
    // effect above.
    effect(() => {
      if (!this.isDuende) {
        this.draft.duendeNaturePowerId.set(null);
        this.draft.duendeSizePowerId.set(null);
        this.draft.duendeGiftPowerIds.set([null, null, null]);
        this.draft.duendeTabooPowerId.set(null);
        this.draft.duendeRandomlyCreated.set(false);
      }
    });

    // Duende (Animal)'s attribute pick only exists while Animal is the
    // chosen Natureza.
    effect(() => {
      if (this.draft.duendeNaturePowerId() !== DUENDE_ANIMAL_POWER_ID) {
        this.draft.duendeAnimalAttribute.set(null);
      }
    });

    // Clear Golem's Chassi/Fonte de Energia picks whenever race stops being
    // Golem, same reasoning as the Duende effect above.
    effect(() => {
      if (!this.isGolem) {
        this.draft.golemChassiPowerId.set(null);
        this.draft.golemFonteEnergiaPowerId.set(null);
        this.draft.golemSizePowerId.set(null);
      }
    });

    // Golem has no origin — clear the pick and whatever origin-step choices
    // came with it the moment the race becomes Golem, instead of waiting for
    // the player to reach step 4.
    effect(() => {
      if (this.isGolem && this.draft.originId() !== null) {
        this.draft.originId.set(null);
        this.draft.originChoices.set([]);
        this.draft.originChoicesOriginId.set(null);
      }
    });

    // Clear Suraggel's Variante pick whenever race stops being Suraggel,
    // same reasoning as the Duende/Golem effects above.
    effect(() => {
      if (!this.isSuraggel) {
        this.draft.suraggelVariantePowerId.set(null);
      }
    });
  }

  // One group of option checkboxes per granted power carrying choice_power
  // effects (Versátil, Deformidade, Chassi Mashin).
  protected readonly choicePowerGroups = computed<ChoicePowerGroup[]>(() => {
    const granted = this.draft.grantedPowerIds();
    const picks = this.draft.choicePowerPicks();
    const powers = this.staticRegistry.powers;
    return powers
      .filter((power) => granted.has(power.id))
      .flatMap((holder) => {
        const options = (holder.effects ?? [])
          .filter((effect) => effect.tag === 'choice_power' && effect.op === 'grant')
          .map((effect) => powers.find((power) => power.id === effect.power_id))
          .filter((power): power is Power => power !== undefined);
        return options.length > 0 ? [{ holder, options, pick: picks[holder.id] ?? null }] : [];
      });
  });

  // The free skill budget changes size with the picked option, and that
  // budget can be spent both inside a class skill group (classSkillChoices,
  // beyond its own base picks) and in Perícias Adicionais
  // (choosingMechanicSkillIds) — so both get wiped outright on any change,
  // no matter what was already picked.
  protected onChoicePowerPick(event: { holderId: number; optionId: number | null }): void {
    const next = { ...this.draft.choicePowerPicks() };
    if (event.optionId === null) {
      delete next[event.holderId];
    } else {
      next[event.holderId] = event.optionId;
    }
    this.draft.choicePowerPicks.set(next);
    this.draft.classSkillChoices.set([]);
    this.draft.choosingMechanicSkillIds.set([]);
  }

  protected get hasMemoriaPostuma(): boolean {
    return MEMORIA_POSTUMA_RACE_IDS.includes(this.draft.raceId() ?? -1);
  }

  protected get draftMemoriaPostumaChoice() {
    return this.draft.memoriaPostumaChoice;
  }

  protected get draftMemoriaPostumaRaceAbilityPowerId() {
    return this.draft.memoriaPostumaRaceAbilityPowerId;
  }

  // Same reasoning as onChoicePowerPick — Memória Póstuma's
  // 'skill' branch spends from the same shared budget, so switching away
  // from (or between) its three alternatives could leave stale picks in
  // either place exceeding the new budget. Always clear all three on
  // change, including Trocar Raça Base's own pick.
  protected onMemoriaPostumaChoiceChange(value: MemoriaPostumaChoice): void {
    this.draft.memoriaPostumaChoice.set(value);
    this.draft.classSkillChoices.set([]);
    this.draft.choosingMechanicSkillIds.set([]);
    this.draft.memoriaPostumaRaceAbilityPowerId.set(null);
  }

  protected get isQareen(): boolean {
    return this.draft.raceId() === QAREEN_RACE_ID;
  }

  protected get draftQareenAncestryPowerId() {
    return this.draft.qareenAncestryPowerId;
  }

  protected get isDuende(): boolean {
    return this.draft.raceId() === DUENDE_RACE_ID;
  }

  protected get isGolem(): boolean {
    return this.draft.raceId() === GOLEM_RACE_ID;
  }

  protected get draftDuendeNaturePowerId() {
    return this.draft.duendeNaturePowerId;
  }

  protected get draftDuendeSizePowerId() {
    return this.draft.duendeSizePowerId;
  }

  protected get draftDuendeGiftPowerIds() {
    return this.draft.duendeGiftPowerIds;
  }

  protected get draftDuendeTabooPowerId() {
    return this.draft.duendeTabooPowerId;
  }

  protected get draftDuendeRandomlyCreated() {
    return this.draft.duendeRandomlyCreated;
  }

  protected get draftGolemChassiPowerId() {
    return this.draft.golemChassiPowerId;
  }

  protected get draftGolemFonteEnergiaPowerId() {
    return this.draft.golemFonteEnergiaPowerId;
  }

  protected get draftGolemSizePowerId() {
    return this.draft.golemSizePowerId;
  }

  protected get isSuraggel(): boolean {
    return SURAGGEL_RACE_IDS.includes(this.draft.raceId() ?? -1);
  }

  protected get draftSuraggelVariantePowerId() {
    return this.draft.suraggelVariantePowerId;
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

  // Free-point attribute picks (otherAttributes) belong to the race that
  // granted those points — a different race's own points/exclusions make
  // the old picks meaningless, so any real change wipes them.
  protected onRaceChange(value: number | string | null): void {
    const raceId = (value as number | null) ?? null;
    if (raceId === this.draft.raceId()) {
      return;
    }
    this.draft.resetPicks();
    this.draft.portraitId.set(null);
    this.draft.raceId.set(raceId);
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
      this.choicePowerGroups().every((group) => group.pick !== null) &&
      (!this.hasMemoriaPostuma || this.draft.memoriaPostumaChoice() !== null) &&
      (this.draft.memoriaPostumaChoice() !== 'change_base_race' || this.draft.memoriaPostumaRaceAbilityPowerId() !== null) &&
      (!this.isQareen || this.draft.qareenAncestryPowerId() !== null) &&
      (!this.isDuende ||
        (this.draft.duendeNaturePowerId() !== null && this.draft.duendeSizePowerId() !== null && this.draft.duendeGiftPowerIds().every((id) => id !== null) && this.draft.duendeTabooPowerId() !== null)),
  );

  protected raceMods = (race: Race): SecondarySegment[] => {
    const stats = Object.keys(ATTRIBUTE_ABBREVIATION_LABELS)
      .map((key) => ({ label: ATTRIBUTE_ABBREVIATION_LABELS[key], value: (race as any)[key] as number }))
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
    const sizeLabel = CHARACTER_SIZE_LABELS[race.base_size] ?? race.base_size;
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
