import { Component, inject } from '@angular/core';
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
export class Login {
  private readonly router = inject(Router);
  private readonly authService = inject(AuthService);

  login(): void {
    const client = google.accounts.oauth2.initTokenClient({
      client_id: environment.googleClientId,
      scope: 'email profile openid',
      callback: (response) => {
        if (!response.access_token) return;

        this.authService.loginWithGoogle(response.access_token).subscribe(() => {
          this.router.navigate(['/mode']);
        });
      },
    });

    client.requestAccessToken();
  }
}
