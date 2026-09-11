import { Component, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { Modal } from '../../../shared/modals/modal/modal';
import { ApiService } from '../../../api.service';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { UseCharacter } from '../../../shared/hooks/use-character';
import { CharacterDraft } from '../character-draft';
import { buildCharacterPayload } from '../character-payload';

// The "Salvando..." modal stays up at least this long even if the request
// resolves faster, so it doesn't just flash on screen.
const MIN_SAVING_MS = 4000;

// Shared final-save step for character creation — whichever step is
// actually last (9 normally, 10 for Arcanista once spell selection needs
// its own step) just drops this in and calls save() from its own Salvar
// button, instead of each duplicating the payload/request/modal-timing
// logic. character-payload.ts only builds the data object; this owns
// actually sending it and showing the wait state.
@Component({
  selector: 'app-character-creation-saving',
  imports: [Modal],
  templateUrl: './character-creation-saving.html',
  styleUrl: './character-creation-saving.scss',
})
export class CharacterCreationSaving {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);
  private apiService = inject(ApiService);
  private useCharacter = inject(UseCharacter);

  protected readonly saving = signal(false);

  save(): void {
    const payload = buildCharacterPayload(this.draft, this.staticRegistry.origins, this.staticRegistry.races, this.staticRegistry.powers);

    const startedAt = Date.now();
    this.saving.set(true);

    // Waits out whatever's left of MIN_SAVING_MS before closing the modal,
    // so a fast response doesn't just flash it. success only redirects —
    // an error just closes the modal and leaves the player here to retry,
    // rather than navigating away from a save that didn't happen.
    const closeSavingModal = (onClosed: () => void) => {
      const remaining = Math.max(0, MIN_SAVING_MS - (Date.now() - startedAt));
      setTimeout(() => {
        this.saving.set(false);
        onClosed();
      }, remaining);
    };

    this.apiService.createCharacter(payload).subscribe({
      next: () => {
        this.useCharacter.invalidate();
        this.draft.reset();
        closeSavingModal(() => this.router.navigate(['/player']));
      },
      error: (err) => {
        console.error('Failed to create character', err);
        closeSavingModal(() => {});
      },
    });
  }
}
