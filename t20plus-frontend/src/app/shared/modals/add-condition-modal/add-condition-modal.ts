import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Power } from '../../../api.service';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { Modal } from '../modal/modal';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';

@Component({
  selector: 'app-add-condition-modal',
  imports: [Modal, SearchableDropdown],
  templateUrl: './add-condition-modal.html',
  styleUrl: './add-condition-modal.scss',
})
export class AddConditionModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);
  protected readonly staticRegistry = inject(StaticRegistry);

  character = input.required<Character>();
  id = input.required<string>();
  cancel = output<void>();

  protected readonly conditionPowerId = signal<number | null>(null);

  protected availableConditionPowers(): Power[] {
    const alreadyHas = new Set((this.character().active_effects ?? []).map((effect) => effect.power_id));
    return this.staticRegistry.powers.filter((power) => power.source === 'condition_granted' && !alreadyHas.has(power.id));
  }

  protected confirm(): void {
    const powerId = this.conditionPowerId();
    if (powerId === null) {
      return;
    }
    this.apiService.addCharacterActiveEffect(this.character().id, powerId).subscribe((updated) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_effects: updated.active_effects });
    });
    this.cancel.emit();
  }
}
