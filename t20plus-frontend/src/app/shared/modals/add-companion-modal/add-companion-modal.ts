import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character } from '../../../api.service';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { Modal } from '../modal/modal';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';

@Component({
  selector: 'app-add-companion-modal',
  imports: [Modal, SearchableDropdown],
  templateUrl: './add-companion-modal.html',
  styleUrl: './add-companion-modal.scss',
})
export class AddCompanionModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);
  protected readonly staticRegistry = inject(StaticRegistry);

  character = input.required<Character>();
  id = input.required<string>();
  cancel = output<void>();

  protected readonly companionId = signal<number | null>(null);

  protected confirm(): void {
    const companionId = this.companionId();
    if (companionId === null) {
      return;
    }
    this.apiService.addCharacterCompanion(this.character().id, companionId).subscribe((response) => {
      this.useCharacter.patchCharacterCache(this.id(), response);
    });
    this.cancel.emit();
  }
}
