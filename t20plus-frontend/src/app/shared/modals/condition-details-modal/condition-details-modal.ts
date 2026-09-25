import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterActiveEffectRow, Power } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { UseCharacter } from '../../hooks/use-character';

export interface SelectedCondition {
  effect: CharacterActiveEffectRow;
  power: Power;
  iconFileName: string | undefined;
}

@Component({
  selector: 'app-condition-details-modal',
  imports: [],
  templateUrl: './condition-details-modal.html',
  styleUrl: './condition-details-modal.scss',
})
export class ConditionDetailsModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  id = input.required<string>();
  condition = input.required<SelectedCondition>();
  cancel = output<void>();

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  protected effectLines(): string[] {
    return (this.condition().power.effects ?? []).map((effect) => {
      const value = Number(effect.value ?? 0);
      if (effect.op === 'multiply') {
        return `${effect.tag} ×${value}`;
      }
      if (effect.op === 'override') {
        return `${effect.tag} =${value}`;
      }
      return `${effect.tag} ${value >= 0 ? `+${value}` : `${value}`}`;
    });
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
    const character = this.character();
    const { effect } = this.condition();
    this.apiService.destroyCharacterActiveEffect(character.id, effect.id).subscribe((updated) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_effects: updated.active_effects });
    });
    this.cancel.emit();
  }
}
