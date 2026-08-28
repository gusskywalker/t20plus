import { Component, input, model } from '@angular/core';
import { formatDigits } from '../../utils/digit-display';

let nextId = 0;

@Component({
  selector: 'app-number-stepper',
  imports: [],
  templateUrl: './number-stepper.html',
  styleUrl: './number-stepper.scss',
})
export class NumberStepper {
  protected readonly inputId = `number-stepper-${nextId++}`;

  labelText = input('');
  min = input(-Infinity);
  max = input(Infinity);

  value = model(0);

  protected readonly formatDigits = formatDigits;

  increment(): void {
    if (this.value() < this.max()) {
      this.value.set(this.value() + 1);
    }
  }

  decrement(): void {
    if (this.value() > this.min()) {
      this.value.set(this.value() - 1);
    }
  }
}
