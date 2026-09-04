import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Power, Prerequisite } from '../../api.service';
import { calculateStatBonus } from '../helpers/calculate-stat-bonus/calculate-stat-bonus';
import { StaticRegistry } from '../hooks/static-registry';
import { UseCharacter } from '../hooks/use-character';
import { SearchableDropdown } from '../inputs/searchable-dropdown/searchable-dropdown';

@Component({
  selector: 'app-level-change-modal',
  imports: [SearchableDropdown],
  templateUrl: './level-change-modal.html',
  styleUrl: './level-change-modal.scss',
})
export class LevelChangeModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  cancel = output<void>();

  // Explicit screen the modal is on, same convention as
  // golpe-pessoal-modal's currentPage. 1: Subir Nível/Reduzir Nível
  // choice. 2: pick the class the new level belongs to (TODO — Selecionar
  // not wired to a save yet).
  protected readonly currentPage = signal(1);

  protected goBack(): void {
    this.currentPage.set(1);
    this.selectedClassId.set(null);
    this.selectedPowerId.set(null);
  }

  protected handleCancel(): void {
    if (this.currentPage() === 2) {
      this.goBack();
      return;
    }
    this.cancel.emit();
  }

  protected subirNivel(): void {
    this.currentPage.set(2);
  }

  // Character's current level + 1 — page 2's dropdown label ("Level 2" for
  // a level-1 character).
  protected nextLevel(): number {
    return this.character().level + 1;
  }

  protected classItems() {
    return this.staticRegistry.classes;
  }

  protected readonly selectedClassId = signal<number | null>(null);
  protected readonly selectedPowerId = signal<number | null>(null);

  // A power pick is only reset when the class actually changes — picking
  // the same class again keeps whatever was already selected.
  protected setSelectedClassId(value: number | string | null): void {
    this.selectedClassId.set(value as number | null);
    this.selectedPowerId.set(null);
  }

  // This new level's class-relative level — existing character_levels rows
  // for the picked class, plus one for the level being added now. A class
  // never picked before comes out to 1 (a fresh multiclass), same as
  // picking it for the very first time normally would.
  protected newClassLevel(): number {
    const classId = this.selectedClassId();
    if (classId === null) {
      return 0;
    }
    return (this.character().levels ?? []).filter((level) => level.class_id === classId).length + 1;
  }

  // A class's own first level never offers a power pick (baseline features
  // only) — same rule as character-creation-step-9's levelPowerRows.
  protected offersPowerPick(): boolean {
    return this.newClassLevel() >= 2;
  }

  private checkPrerequisites(power: Power): boolean {
    const character = this.character();
    const granted = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
    return (power.prerequisites ?? []).every((prerequisite: Prerequisite) => {
      switch (prerequisite.type) {
        case 'attribute':
          return (
            prerequisite.attribute !== undefined &&
            calculateStatBonus(character, prerequisite.attribute, this.staticRegistry.powers) >= (prerequisite.min ?? 0)
          );
        case 'character_level':
          return this.nextLevel() >= (prerequisite.min ?? 0);
        case 'power':
          return prerequisite.power_id !== undefined && granted.has(prerequisite.power_id);
        default:
          return true;
      }
    });
  }

  // Same shape as character-creation-step-9's availablePowerItems, simulating
  // one hypothetical new character_levels row (this.selectedClassId(),
  // newClassLevel(), nextLevel()) instead of a real draft row.
  protected availablePowerItems(): Power[] {
    const classId = this.selectedClassId();
    if (classId === null || !this.offersPowerPick()) {
      return [];
    }
    const character = this.character();
    const granted = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
    const ownPick = this.selectedPowerId();
    const classLevel = this.newClassLevel();
    const raceId = character.race_id;

    return this.staticRegistry.powers
      .filter((power) => {
        if (granted.has(power.id) && power.id !== ownPick) {
          return false;
        }

        const typeMatches =
          power.source === 'general' || power.source === 'tormenta' || power.source === 'group'
            ? true
            : power.source === 'class'
              ? (power.prerequisites ?? []).some(
                  (prerequisite) =>
                    prerequisite.type === 'class' && (prerequisite.class_ids ?? []).includes(classId) && classLevel >= (prerequisite.min_level ?? 0),
                )
              : power.source === 'races'
                ? raceId !== null && (power.prerequisites ?? []).some((prerequisite) => prerequisite.type === 'race' && (prerequisite.race_ids ?? []).includes(raceId))
                : false;
        if (!typeMatches) {
          return false;
        }

        return this.checkPrerequisites(power);
      })
      .sort((a, b) => a.name.localeCompare(b.name, 'pt-BR'));
  }

  // Stub — no save wired yet, just the class/power pick for now.
  protected selecionarNivel(): void {}

  // Same deliberate second-click cooldown as character-main.ts's
  // onRemovePowerClick, own independent state since this is its own
  // component now.
  protected readonly reduzirNivelConfirming = signal(false);
  protected readonly reduzirNivelReady = signal(false);
  private reduzirNivelTimeoutId: ReturnType<typeof setTimeout> | null = null;

  protected reduzirNivel(): void {
    if (!this.reduzirNivelConfirming()) {
      this.reduzirNivelConfirming.set(true);
      this.reduzirNivelTimeoutId = setTimeout(() => this.reduzirNivelReady.set(true), 3000);
      return;
    }
    if (!this.reduzirNivelReady()) {
      return;
    }
    this.apiService.destroyHighestCharacterLevel(this.character().id).subscribe((character) => {
      this.useCharacter.patchCharacterCache(this.id(), { level: character.level, levels: character.levels });
      this.cancel.emit();
    });
  }
}
