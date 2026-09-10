import { Component, OnDestroy, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';
import { environment } from '../../environments/environment';

// Minimal shape of the Google Identity Services global loaded via the
// <script> tag in index.html — no @types package for it, so declared here.
declare const google: {
  accounts: {
    oauth2: {
      initTokenClient(config: {
        client_id: string;
        scope: string;
        callback: (response: { access_token?: string }) => void;
      }): { requestAccessToken(): void };
    };
  };
};

@Component({
  selector: 'app-login',
  imports: [],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login implements OnDestroy {
  private readonly router = inject(Router);
  private readonly authService = inject(AuthService);

  // "Carregando." / "Carregando.." / "Carregando..." — same cycling-dots
  // idea as attack-modal's own rollingText, shown in place of the button
  // once the user has actually picked a Google account (the callback
  // firing means Google's own popup is done) and we're waiting on our
  // backend's token exchange, not during the popup itself.
  protected readonly loading = signal(false);
  private readonly loadingDots = signal(1);
  private loadingInterval: ReturnType<typeof setInterval> | null = null;

  protected loadingText(): string {
    return 'Carregando' + '.'.repeat(this.loadingDots());
  }

  login(): void {
    const client = google.accounts.oauth2.initTokenClient({
      client_id: environment.googleClientId,
      scope: 'email profile openid',
      callback: (response) => {
        if (!response.access_token) return;

        this.loading.set(true);
        this.loadingDots.set(1);
        this.loadingInterval = setInterval(() => {
          this.loadingDots.set((this.loadingDots() % 3) + 1);
        }, 500);

        this.authService.loginWithGoogle(response.access_token).subscribe(() => {
          this.router.navigate(['/mode']);
        });
      },
    });

    client.requestAccessToken();
  }

  ngOnDestroy(): void {
    if (this.loadingInterval !== null) {
      clearInterval(this.loadingInterval);
    }
  }
}
