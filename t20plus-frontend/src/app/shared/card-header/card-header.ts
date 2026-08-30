import { Component, inject, input } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-card-header',
  imports: [],
  templateUrl: './card-header.html',
  styleUrl: './card-header.scss',
})
export class CardHeader {
  private readonly router = inject(Router);

  title = input('');
  hideHome = input(false);

  goHome(): void {
    this.router.navigate(['/mode']);
  }
}
