import { Component, input, model } from '@angular/core';

let nextId = 0;

@Component({
  selector: 'app-checkbox',
  imports: [],
  templateUrl: './checkbox.html',
  styleUrl: './checkbox.scss',
})
export class Checkbox {
  protected readonly inputId = `checkbox-${nextId++}`;

  labelText = input('');
  disabled = input(false);
  value = model(false);

  onChange(event: Event): void {
    this.value.set((event.target as HTMLInputElement).checked);
  }
}
