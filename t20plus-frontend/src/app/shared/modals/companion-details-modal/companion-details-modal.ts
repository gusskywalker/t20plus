import { Component, inject, input, OnInit, output, signal } from '@angular/core';
import { ApiService, Character, CharacterCompanionRow, Companion, Power } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { calculateCompanionStats, CompanionStats } from '../../helpers/calculators/calculate-companion-stats/calculate-companion-stats';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { resolveReplacedPowerIds } from '../../helpers/resolve-replaced-power-ids/resolve-replaced-power-ids';
import { StaticRegistry } from '../../hooks/static-registry';
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
  private readonly staticRegistry = inject(StaticRegistry);

  character = input.required<Character>();
  id = input.required<string>();
  companion = input.required<SelectedCompanion>();
  companionType = input.required<Companion['type']>();
  cancel = output<void>();

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  protected readonly currentPage = signal<1 | 2 | 3 | 4>(1);
  protected readonly selectedPower = signal<Power | null>(null);
  protected readonly pvMode = signal<'add' | 'remove'>('add');
  protected readonly pvDraft = signal<number | null>(null);
  protected readonly nameDraft = signal('');

  ngOnInit(): void {
    const { characterCompanion } = this.companion();
    if (characterCompanion.current_pv !== null || this.companionType() === 'familiar' || this.companionType() === 'parceiro') {
      return;
    }
    this.apiService.updateCharacterCompanion(this.character().id, characterCompanion.id, { current_pv: this.maxPv() }).subscribe((character_companions) => {
      this.useCharacter.patchCharacterCache(this.id(), { character_companions });
    });
  }

  protected grantedPowers(): Power[] {
    const activeEffects = this.character().active_effects ?? [];
    const replacedPowerIds = resolveReplacedPowerIds(new Set(activeEffects.map((effect) => effect.power_id)), this.staticRegistry.powers);
    return activeEffects
      .filter((effect) => effect.source_companion_id === this.companion().characterCompanion.id)
      .map((effect) => this.staticRegistry.powers.find((power) => power.id === effect.power_id))
      .filter((power): power is Power => power !== undefined && !replacedPowerIds.has(power.id));
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

  private resolvedStats(): CompanionStats {
    return calculateCompanionStats(this.character(), this.liveRow(), this.companion().companion);
  }

  protected maxPv(): number {
    return this.resolvedStats().maxPv;
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

  protected openPowerPage(power: Power): void {
    this.selectedPower.set(power);
    this.currentPage.set(4);
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
    this.apiService.destroyCharacterCompanion(this.character().id, this.companion().characterCompanion.id).subscribe((response) => {
      this.useCharacter.patchCharacterCache(this.id(), response);
    });
    this.cancel.emit();
  }

  private readonly vitalRowDefinitions: { label: string; key: string; immunity?: string }[] = [
    { label: 'Tamanho', key: 'size' },
    { label: 'Defesa', key: 'defense' },
    { label: 'Deslocamento', key: 'movement' },
    { label: 'Força', key: 'str' },
    { label: 'Destreza', key: 'dex' },
    { label: 'Constituição', key: 'con' },
    { label: 'Inteligência', key: 'int' },
    { label: 'Sabedoria', key: 'knw' },
    { label: 'Carisma', key: 'car' },
    { label: 'Fortitude', key: 'fortitude', immunity: 'fortitude' },
    { label: 'Reflexos', key: 'reflexes', immunity: 'reflexos' },
    { label: 'Vontade', key: 'vontade', immunity: 'vontade' },
  ];

  protected vitalRows(group: 'stats' | 'resistances'): { label: string; value: string }[] {
    const { numbers, immunities } = this.resolvedStats();
    return this.vitalRowDefinitions
      .filter((definition) => (definition.immunity !== undefined) === (group === 'resistances'))
      .filter((definition) => definition.immunity !== undefined || numbers[definition.key] !== undefined)
      .map((definition) => {
        if (definition.immunity && immunities.includes(definition.immunity)) {
          return { label: definition.label, value: 'I' };
        }
        const value = numbers[definition.key];
        return { label: definition.label, value: value === undefined ? '-' : String(value) };
      });
  }

  protected attackRows(): { label: string; value: string }[] {
    const { dmg, reach, damageTypes } = this.resolvedStats().attack;
    const rows: { label: string; value: string }[] = [];
    if (dmg !== null) {
      rows.push({ label: 'Ataque', value: dmg });
    }
    if (reach !== null) {
      rows.push({ label: 'Alcance', value: String(reach) });
    }
    if (damageTypes.length > 0) {
      rows.push({ label: 'Tipo', value: damageTypes.join(', ') });
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
    const vitalKeys = [...this.vitalRowDefinitions.map((definition) => `base_${definition.key}`),'base_max_pv', 'base_attack', 'base_immunities'];
    Object.entries(this.companion().companion.base_stats ?? {})
      .filter(([key]) => !vitalKeys.includes(key))
      .forEach(([key, value]) => addRow(key, value));
    return rows;
  }
}
