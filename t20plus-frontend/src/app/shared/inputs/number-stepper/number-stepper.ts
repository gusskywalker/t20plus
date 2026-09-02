import { Component, input, model } from '@angular/core';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';

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

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

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
