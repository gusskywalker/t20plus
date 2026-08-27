import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { DiceBadge } from '../shared/dice-badge/dice-badge';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-login',
  imports: [DiceBadge],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  private readonly router = inject(Router);
  private readonly authService = inject(AuthService);

  login(): void {
    this.authService.login().subscribe(() => {
      this.router.navigate(['/mode']);
    });
  }
}
