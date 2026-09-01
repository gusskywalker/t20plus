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
  showCancel = input(true);
  hideButtons = input(false);
  maxHeight = input(600);

  cancel = output<void>();
  select = output<void>();
}
