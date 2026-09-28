import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';
import { Modal } from '../modal/modal';
import { NumberInput } from '../../inputs/number-input/number-input';
import { addTempPv } from '../../helpers/add-temp-pv/add-temp-pv';
import { addTempPm } from '../../helpers/add-temp-pm/add-temp-pm';

@Component({
  selector: 'app-restore-remove-pv-pm-modal',
  imports: [Modal, NumberInput],
  templateUrl: './restore-remove-pv-pm-modal.html',
  styleUrl: './restore-remove-pv-pm-modal.scss',
})
export class RestoreRemovePvPmModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  id = input.required<string>();
  pvOrPm = input.required<'pv' | 'pm'>();
  cancel = output<void>();

  protected readonly mode = signal<'add' | 'remove'>('add');
  protected readonly targetsTemp = signal(false);
  protected readonly draft = signal<number | null>(null);

  protected toggleMode(): void {
    this.mode.set(this.mode() === 'add' ? 'remove' : 'add');
  }

  protected toggleTargetsTemp(): void {
    this.targetsTemp.set(!this.targetsTemp());
  }

  protected confirm(): void {
    const delta = this.draft();
    if (delta === null) {
      return;
    }
    const signedDelta = this.mode() === 'add' ? delta : -delta;
    const character = this.character();
    if (this.pvOrPm() === 'pv') {
      if (this.targetsTemp() && this.mode() === 'add') {
        addTempPv(this.apiService, this.useCharacter, this.id(), character, delta);
      } else if (this.targetsTemp()) {
        const temp_pv = character.temp_pv + signedDelta;
        this.apiService.updateCharacter(character.id, { temp_pv }).subscribe(() => {
          this.useCharacter.patchCharacterCache(this.id(), { temp_pv });
        });
      } else {
        const current_pv = (character.current_pv ?? 0) + signedDelta;
        this.apiService.updateCharacter(character.id, { current_pv }).subscribe(() => {
          this.useCharacter.patchCharacterCache(this.id(), { current_pv });
        });
      }
    } else {
      if (this.targetsTemp() && this.mode() === 'add') {
        addTempPm(this.apiService, this.useCharacter, this.id(), character, delta);
      } else if (this.targetsTemp()) {
        const temp_pm = character.temp_pm + signedDelta;
        this.apiService.updateCharacter(character.id, { temp_pm }).subscribe(() => {
          this.useCharacter.patchCharacterCache(this.id(), { temp_pm });
        });
      } else {
        const current_pm = (character.current_pm ?? 0) + signedDelta;
        this.apiService.updateCharacter(character.id, { current_pm }).subscribe(() => {
          this.useCharacter.patchCharacterCache(this.id(), { current_pm });
        });
      }
    }
    this.cancel.emit();
  }
}
