import { Component, input, model } from '@angular/core';

let nextId = 0;

@Component({
  selector: 'app-text-input',
  imports: [],
  templateUrl: './text-input.html',
  styleUrl: './text-input.scss',
})
export class TextInput {
  protected readonly inputId = `text-input-${nextId++}`;

  labelText = input('');
  readOnly = input(false);
  invalid = input(false);
  value = model('');
}
