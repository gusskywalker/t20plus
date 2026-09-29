import { Component, inject, input, OnInit, output, signal } from '@angular/core';
import { ApiService, Character, CharacterCompanionRow, Companion } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { UseCharacter } from '../../hooks/use-character';
import { NumberInput } from '../../inputs/number-input/number-input';
import { TextInput } from '../../inputs/text-input/text-input';
import { TormentaDivider } from '../../tormenta-divider/tormenta-divider';

export interface SelectedCompanion {
  characterCompanion: CharacterCompanionRow;
  companion: Companion;
  iconFileName: string | undefined;
}

@Component({
  selector: 'app-companion-details-modal',
  imports: [NumberInput, TextInput, TormentaDivider],
  templateUrl: './companion-details-modal.html',
  styleUrl: './companion-details-modal.scss',
})
export class CompanionDetailsModal implements OnInit {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  id = input.required<string>();
  companion = input.required<SelectedCompanion>();
  companionType = input.required<Companion['type']>();
  cancel = output<void>();

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  protected readonly currentPage = signal<1 | 2 | 3>(1);
  protected readonly pvMode = signal<'add' | 'remove'>('add');
  protected readonly pvDraft = signal<number | null>(null);
  protected readonly nameDraft = signal('');

  ngOnInit(): void {
    const { characterCompanion } = this.companion();
    if (characterCompanion.current_pv !== null) {
      return;
    }
    this.apiService.updateCharacterCompanion(this.character().id, characterCompanion.id, { current_pv: this.maxPv() }).subscribe((character_companions) => {
      this.useCharacter.patchCharacterCache(this.id(), { character_companions });
    });
  }

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  private liveRow(): CharacterCompanionRow {
    const { characterCompanion } = this.companion();
    return this.character().character_companions?.find((row) => row.id === characterCompanion.id) ?? characterCompanion;
  }

  protected displayName(): string {
    return this.liveRow().name || this.companion().companion.name;
  }

  protected openNamePage(): void {
    this.nameDraft.set(this.liveRow().name ?? '');
    this.currentPage.set(3);
  }

  protected confirmName(): void {
    const name = this.nameDraft().trim() || null;
    this.apiService.updateCharacterCompanion(this.character().id, this.companion().characterCompanion.id, { name }).subscribe((character_companions) => {
      this.useCharacter.patchCharacterCache(this.id(), { character_companions });
    });
    this.currentPage.set(1);
  }

  protected maxPv(): number {
    return Number(this.companion().companion.base_stats?.['base_max_pv'] ?? 0);
  }

  protected currentPv(): number {
    const { characterCompanion } = this.companion();
    const liveRow = this.character().character_companions?.find((row) => row.id === characterCompanion.id);
    return liveRow?.current_pv ?? characterCompanion.current_pv ?? this.maxPv();
  }

  protected openPvPage(): void {
    this.pvMode.set('add');
    this.pvDraft.set(null);
    this.currentPage.set(2);
  }

  protected backToDetails(): void {
    this.currentPage.set(1);
  }

  protected togglePvMode(): void {
    this.pvMode.set(this.pvMode() === 'add' ? 'remove' : 'add');
  }

  protected confirmPv(): void {
    const delta = this.pvDraft();
    if (delta === null) {
      return;
    }
    const current_pv = this.currentPv() + (this.pvMode() === 'add' ? delta : -delta);
    this.apiService.updateCharacterCompanion(this.character().id, this.companion().characterCompanion.id, { current_pv }).subscribe((character_companions) => {
      this.useCharacter.patchCharacterCache(this.id(), { character_companions });
    });
    this.backToDetails();
  }

  protected readonly removeConfirming = signal(false);
  protected readonly removeReady = signal(false);

  protected onRemoveClick(): void {
    if (!this.removeConfirming()) {
      this.removeConfirming.set(true);
      setTimeout(() => this.removeReady.set(true), 1000);
      return;
    }
    if (!this.removeReady()) {
      return;
    }
    this.apiService.destroyCharacterCompanion(this.character().id, this.companion().characterCompanion.id).subscribe((character_companions) => {
      this.useCharacter.patchCharacterCache(this.id(), { character_companions });
    });
    this.cancel.emit();
  }

  private readonly vitalRowDefinitions: { label: string; key: string; immunity?: string }[] = [
    { label: 'Tamanho', key: 'base_size' },
    { label: 'Defesa', key: 'base_defense' },
    { label: 'Deslocamento', key: 'base_movement' },
    { label: 'Força', key: 'base_str' },
    { label: 'Destreza', key: 'base_dex' },
    { label: 'Constituição', key: 'base_con' },
    { label: 'Inteligência', key: 'base_int' },
    { label: 'Sabedoria', key: 'base_knw' },
    { label: 'Carisma', key: 'base_car' },
    { label: 'Fortitude', key: 'base_fortitude', immunity: 'fortitude' },
    { label: 'Reflexos', key: 'base_reflexes', immunity: 'reflexos' },
    { label: 'Vontade', key: 'base_vontade', immunity: 'vontade' },
  ];

  protected vitalRows(group: 'stats' | 'resistances'): { label: string; value: string }[] {
    const stats = this.companion().companion.base_stats ?? {};
    const immunities = Array.isArray(stats['base_immunities']) ? (stats['base_immunities'] as string[]) : [];
    return this.vitalRowDefinitions
      .filter((definition) => (definition.immunity !== undefined) === (group === 'resistances'))
      .filter((definition) => definition.immunity !== undefined || stats[definition.key] !== undefined)
      .map((definition) => {
        if (definition.immunity && immunities.includes(definition.immunity)) {
          return { label: definition.label, value: 'I' };
        }
        const value = stats[definition.key];
        return { label: definition.label, value: value === undefined ? '-' : String(value) };
      });
  }

  protected attackRows(): { label: string; value: string }[] {
    const attack = this.companion().companion.base_stats?.['base_attack'];
    if (attack === null || typeof attack !== 'object') {
      return [];
    }
    const { dmg, reach, damage_types } = attack as { dmg?: unknown; reach?: unknown; damage_types?: unknown };
    const rows: { label: string; value: string }[] = [];
    if (dmg !== undefined) {
      rows.push({ label: 'Ataque', value: String(dmg) });
    }
    if (reach !== undefined) {
      rows.push({ label: 'Alcance', value: String(reach) });
    }
    if (damage_types !== undefined) {
      rows.push({ label: 'Tipo', value: Array.isArray(damage_types) ? damage_types.join(', ') : String(damage_types) });
    }
    return rows;
  }

  protected statRows(): { label: string; value: string }[] {
    const rows: { label: string; value: string }[] = [];
    const addRow = (label: string, value: unknown): void => {
      if (Array.isArray(value)) {
        rows.push({ label, value: value.join(', ') });
        return;
      }
      if (value !== null && typeof value === 'object') {
        Object.entries(value).forEach(([key, nested]) => addRow(`${label}.${key}`, nested));
        return;
      }
      rows.push({ label, value: String(value) });
    };
    const vitalKeys = [...this.vitalRowDefinitions.map((definition) => definition.key), 'base_max_pv', 'base_attack', 'base_immunities'];
    Object.entries(this.companion().companion.base_stats ?? {})
      .filter(([key]) => !vitalKeys.includes(key))
      .forEach(([key, value]) => addRow(key, value));
    return rows;
  }
}
