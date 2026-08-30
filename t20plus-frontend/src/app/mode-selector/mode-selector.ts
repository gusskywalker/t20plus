import { Component, computed, inject } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';
import { CardHeader } from '../shared/card-header/card-header';
import { TormentaDivider } from '../shared/tormenta-divider/tormenta-divider';

@Component({
  selector: 'app-mode-selector',
  imports: [CardHeader, TormentaDivider],
  templateUrl: './mode-selector.html',
  styleUrl: './mode-selector.scss',
})
export class ModeSelector {
  private readonly router = inject(Router);
  private readonly authService = inject(AuthService);

  protected readonly title = computed(() => `Olá, ${this.authService.getUserNameSignal()()}`);

  chooseMestre(): void {
    this.router.navigate(['/master']);
  }

  chooseJogador(): void {
    this.router.navigate(['/player']);
  }
}
