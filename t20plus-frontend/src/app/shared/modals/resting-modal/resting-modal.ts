import { Component, computed, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Effect, Power } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { Checkbox } from '../../inputs/checkbox/checkbox';
import { resolveNumericSentinels } from '../../helpers/resolve-numeric-sentinels/resolve-numeric-sentinels';
import { restorePm } from '../../helpers/restore-pm/restore-pm';
import { restorePv } from '../../helpers/restore-pv/restore-pv';
import { scaleLevelEffect } from '../../helpers/scale-level-effect/scale-level-effect';

interface RestType {
  key: string;
  label: string;
  description: string;
  iconFileName: string;
}

const REST_TYPES: RestType[] = [
  { key: 'bad', label: 'Descanso Ruim', description: 'Recuperação de PV/PM igual à metade do nível. <br>(Arredondado para baixo)', iconFileName: 'resting_-1_01.webp' },
  { key: 'normal', label: 'Descanso Normal', description: 'Recuperação de PV/PM igual ao nível.', iconFileName: 'resting_0_01.webp' },
  { key: 'comfortable', label: 'Descanso Confortável', description: 'Recuperação de PV/PM igual ao dobro do seu nível.', iconFileName: 'resting_1_01.webp' },
  { key: 'luxurious', label: 'Descanso Luxuoso', description: 'Recuperação de PV/PM igual ao triplo do seu nível.', iconFileName: 'resting_2_01.webp' },
];

const RESTING_TAGS = ['resting_floor_pv', 'resting_floor_pm', 'resting_bonus_pv', 'resting_pm_recovery', 'resting_pv_recovery'];

@Component({
  selector: 'app-resting-modal',
  imports: [Checkbox],
  templateUrl: './resting-modal.html',
  styleUrl: './resting-modal.scss',
})
export class RestingModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);
  private readonly staticRegistry = inject(StaticRegistry);

  character = input.required<Character>();
  id = input.required<string>();
  cancel = output<void>();

  protected readonly restTypes = REST_TYPES;
  protected readonly restType = signal<string | null>('normal');
  private readonly checkedPowerIds = signal<Set<number>>(new Set());

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  protected readonly shownRestType = computed(() => REST_TYPES.find((type) => type.key === this.restType()) ?? REST_TYPES[1]);

  protected setRestType(key: string, checked: boolean): void {
    if (checked) {
      this.restType.set(key);
    } else if (this.restType() === key) {
      this.restType.set(null);
    }
  }

  protected readonly restingPowers = computed(() => {
    const owned = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
    return this.staticRegistry.powers.filter(
      (power) => owned.has(power.id) && power.usability === 'resting' && (power.effects ?? []).some((effect) => RESTING_TAGS.includes(effect.tag)),
    );
  });

  protected isPowerChecked(power: Power): boolean {
    return this.checkedPowerIds().has(power.id);
  }

  protected setPowerChecked(power: Power, checked: boolean): void {
    const next = new Set(this.checkedPowerIds());
    if (checked) {
      next.add(power.id);
    } else {
      next.delete(power.id);
    }
    this.checkedPowerIds.set(next);
  }

  private readonly checkedEffects = computed<Effect[]>(() => {
    const level = this.character().level;
    const checked = this.checkedPowerIds();
    return this.restingPowers()
      .filter((power) => checked.has(power.id))
      .flatMap((power) => power.effects ?? [])
      .filter((effect) => RESTING_TAGS.includes(effect.tag))
      .map((effect) => resolveNumericSentinels(scaleLevelEffect(effect, level), level, () => 0));
  });

  private categoryAmounts(level: number): number[] {
    return [Math.floor(level / 2), level, level * 2, level * 3];
  }

  private valuesOf(tag: string): number[] {
    return this.checkedEffects()
      .filter((effect) => effect.tag === tag)
      .map((effect) => Number(effect.value ?? 0));
  }

  protected readonly recovery = computed(() => {
    const level = this.character().level;
    const typeIndex = REST_TYPES.findIndex((type) => type.key === this.restType());
    if (typeIndex === -1) {
      return null;
    }
    const amounts = this.categoryAmounts(level);
    const pvSteps = this.valuesOf('resting_pv_recovery').reduce((sum, value) => sum + value, 0);
    const pvIndex = Math.min(Math.max(typeIndex + pvSteps, 0), REST_TYPES.length - 1);
    const pvBonus = this.valuesOf('resting_bonus_pv').reduce((sum, value) => sum + value, 0);
    const pvFloors = this.valuesOf('resting_floor_pv');
    const pv = Math.max(amounts[pvIndex] + pvBonus, ...pvFloors);

    const pmOverrides = this.valuesOf('resting_pm_recovery');
    const pmFloors = this.valuesOf('resting_floor_pm');
    const pm = pmOverrides.length > 0 ? pmOverrides[pmOverrides.length - 1] : Math.max(amounts[typeIndex], ...pmFloors);
    return { pv, pm };
  });

  protected rest(): void {
    const recovery = this.recovery();
    if (!recovery) {
      return;
    }
    const character = this.character();
    restorePv(this.apiService, this.useCharacter, this.id(), character, recovery.pv, this.staticRegistry.powers);
    restorePm(this.apiService, this.useCharacter, this.id(), character, recovery.pm, this.staticRegistry.powers);
    this.cancel.emit();
  }
}
