import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { DiceBadge } from '../shared/dice-badge/dice-badge';

@Component({
  selector: 'app-login',
  imports: [DiceBadge],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  private readonly router = inject(Router);

  login(): void {
    this.router.navigate(['/mode']);
  }
}
