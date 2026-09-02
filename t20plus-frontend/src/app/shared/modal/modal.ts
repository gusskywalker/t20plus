import { Component, input, output } from '@angular/core';

@Component({
  selector: 'app-modal',
  imports: [],
  templateUrl: './modal.html',
  styleUrl: './modal.scss',
})
export class Modal {
  cancelLabel = input('Cancelar');
  selectLabel = input('Selecionar');
  // Hides just the one button — for a modal that only needs "Equipar" with
  // no "Cancelar", or vice versa. hideButtonRow hides the whole row (both
  // buttons at once), for content-only modals like step 9's saving spinner.
  hideSecondaryButton = input(false);
  hidePrimaryButton = input(false);
  hideButtonRow = input(false);
  maxHeight = input(600);
  // When true, .modal-box sizes to its actual content instead of the
  // fixed 90vh — for a light-content modal (e.g. one text-input) so it
  // doesn't leave a huge empty gap above the buttons.
  shrinksHeight = input(false);

  cancel = output<void>();
  // Named "confirmed", not "select" — "select" collides with the native
  // DOM event of the same name, which fires whenever text gets selected
  // in any descendant input (double-click, drag-select, Ctrl+A, "Select
  // All" from the right-click menu). That collision was firing this
  // output for free any time someone selected text in a modal's content.
  confirmed = output<void>();
}
