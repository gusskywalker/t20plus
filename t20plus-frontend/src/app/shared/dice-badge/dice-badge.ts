import { Component, inject, input } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-dice-badge',
  imports: [],
  templateUrl: './dice-badge.html',
  styleUrl: './dice-badge.scss',
})
export class DiceBadge {
  private readonly router = inject(Router);

  clickable = input(true);

  onClick(): void {
    if (this.clickable()) {
      this.router.navigate(['/mode']);
    }
  }
}
