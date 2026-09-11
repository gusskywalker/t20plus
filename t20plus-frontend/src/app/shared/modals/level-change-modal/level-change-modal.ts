import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Power, Prerequisite } from '../../../api.service';
import { resolveGrantedPowerIds } from '../../helpers/resolve-granted-power-ids/resolve-granted-power-ids';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';
import { ArcanistaPathSection } from '../../arcanista-path-section/arcanista-path-section';
import { matchesClassPower, resolveAvailablePowers } from '../../helpers/available-power-picks-solver/available-power-picks-solver';

const ARCANISTA_CLASS_ID = 3;

@Component({
  selector: 'app-level-change-modal',
  imports: [SearchableDropdown, ArcanistaPathSection],
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

  // Arcanista's own first level is the one exception — its mandatory
  // Caminho pick (Bruxo/Feiticeiro/Mago) shows in place of the normal
  // power dropdown, but still writes into the same selectedPowerId.
  protected isArcanistaFirstLevel(): boolean {
    return this.selectedClassId() === ARCANISTA_CLASS_ID && this.newClassLevel() === 1;
  }

  private checkPrerequisites(power: Power): boolean {
    const character = this.character();
    const granted = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
    return (power.prerequisites ?? []).every((prerequisite: Prerequisite) => {
      switch (prerequisite.type) {
        case 'attribute': {
          // Base score only, never calculateStatBonus — a temporary/active
          // power buff (mod_str etc.) must not let a character qualify for
          // a prerequisite they haven't actually permanently earned.
          const baseValues: Record<string, number> = {
            str: character.base_str,
            dex: character.base_dex,
            con: character.base_con,
            int: character.base_int,
            knw: character.base_knw,
            car: character.base_car,
          };
          return prerequisite.attribute !== undefined && (baseValues[prerequisite.attribute] ?? 0) >= (prerequisite.min ?? 0);
        }
        case 'character_level':
          return this.nextLevel() >= (prerequisite.min ?? 0);
        case 'power':
          return (
            (prerequisite.power_id !== undefined && granted.has(prerequisite.power_id)) ||
            (prerequisite.power_ids_any !== undefined && prerequisite.power_ids_any.some((id) => granted.has(id)))
          );
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
    const classLevel = this.newClassLevel();

    return resolveAvailablePowers({
      powers: this.staticRegistry.powers,
      granted,
      ownPickId: this.selectedPowerId(),
      matchesSource: (power) => matchesClassPower(power, classId, classLevel, character.race_id ?? null),
      checkPrerequisites: (power) => this.checkPrerequisites(power),
    });
  }

  // Same "Customize na página do personagem" hint character-creation-
  // step-9 shows under its own class-power dropdown for Golpe Pessoal (id
  // 115) — same hardcoded map, just the one entry so far.
  private readonly powerPickHints: Record<number, string> = {
    115: 'Customize na página do personagem',
  };

  protected powerPickHint(): string | null {
    const powerId = this.selectedPowerId();
    return powerId !== null ? (this.powerPickHints[powerId] ?? null) : null;
  }

  protected selecionarNivel(): void {
    const classId = this.selectedClassId();
    if (classId === null) {
      return;
    }
    const powerId = this.offersPowerPick() || this.isArcanistaFirstLevel() ? this.selectedPowerId() : null;

    this.apiService.createCharacterLevel(this.character().id, { class_id: classId, power_id: powerId }).subscribe((character) => {
      this.useCharacter.patchCharacterCache(this.id(), {
        level: character.level,
        levels: character.levels,
        active_effects: character.active_effects,
        golpes_pessoais: character.golpes_pessoais,
      });

      // The picked power can itself grant others (tag: 'power', op:
      // 'grant' — source: 'power_granted' on the granted side, e.g.
      // Espreitar's two children) — add those the same way any normal
      // power gets added, one at a time (not parallel) so each call's own
      // active_effects snapshot already includes the ones added just
      // before it, instead of racing and dropping one from the cache.
      const grantedChildIds =
        powerId === null ? [] : [...resolveGrantedPowerIds([powerId], this.staticRegistry.powers)].filter((id) => id !== powerId);
      this.grantChildPowers(character.id, grantedChildIds, () => this.cancel.emit());

      // Aumentar Atributo (mod_base_str/etc — a permanent increase, not a
      // live buff, see ClassPowerSeeder.php) — only one power is ever
      // picked here, so just add its value straight onto the character's
      // own base_* column. Independent of grantChildPowers above (patches
      // a different field), fired alongside it.
      this.applyBaseAttributeIncrease(character, powerId);
    });
  }

  private applyBaseAttributeIncrease(character: Character, powerId: number | null): void {
    if (powerId === null) {
      return;
    }
    const power = this.staticRegistry.powers.find((p) => p.id === powerId);
    const effect = (power?.effects ?? []).find((e) => e.tag.startsWith('mod_base_') && e.op === 'add');
    if (!effect) {
      return;
    }
    const attribute = effect.tag.replace('mod_base_', '');
    const field = `base_${attribute}` as 'base_str' | 'base_dex' | 'base_con' | 'base_int' | 'base_knw' | 'base_car';
    const newValue = character[field] + Number(effect.value ?? 0);
    this.apiService.updateCharacter(character.id, { [field]: newValue }).subscribe((updated) => {
      this.useCharacter.patchCharacterCache(this.id(), { [field]: updated[field] });
    });
  }

  private grantChildPowers(characterId: number, remainingIds: number[], onDone: () => void): void {
    const [nextId, ...rest] = remainingIds;
    if (nextId === undefined) {
      onDone();
      return;
    }
    this.apiService.addCharacterActiveEffect(characterId, nextId).subscribe((active_effects) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_effects });
      this.grantChildPowers(characterId, rest, onDone);
    });
  }

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
      this.useCharacter.patchCharacterCache(this.id(), {
        level: character.level,
        levels: character.levels,
        active_effects: character.active_effects,
        base_str: character.base_str,
        base_dex: character.base_dex,
        base_con: character.base_con,
        base_int: character.base_int,
        base_knw: character.base_knw,
        base_car: character.base_car,
      });
      this.cancel.emit();
    });
  }
}
