import { Component, input, model } from '@angular/core';

let nextId = 0;

@Component({
  selector: 'app-number-input',
  imports: [],
  templateUrl: './number-input.html',
  styleUrl: './number-input.scss',
})
export class NumberInput {
  protected readonly inputId = `number-input-${nextId++}`;

  labelText = input('');
  value = model<number | null>(null);

  onInput(event: Event): void {
    const input = event.target as HTMLInputElement;
    const raw = input.value.replace(/[^0-9]/g, '');
    input.value = raw;
    this.value.set(raw === '' ? null : Number(raw));
  }

  onKeyDown(event: KeyboardEvent): void {
    if (event.ctrlKey || event.metaKey || event.altKey) {
      return;
    }

    const allowed = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
    if (allowed.includes(event.key)) {
      return;
    }

    if (!/^[0-9]$/.test(event.key)) {
      event.preventDefault();
    }
  }
}
