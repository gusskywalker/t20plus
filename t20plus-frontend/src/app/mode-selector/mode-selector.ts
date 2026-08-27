import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { DiceBadge } from '../shared/dice-badge/dice-badge';

@Component({
  selector: 'app-mode-selector',
  imports: [DiceBadge],
  templateUrl: './mode-selector.html',
  styleUrl: './mode-selector.scss',
})
export class ModeSelector {
  private readonly router = inject(Router);

  chooseMestre(): void {
    this.router.navigate(['/master']);
  }

  chooseJogador(): void {
    this.router.navigate(['/player']);
  }
}
