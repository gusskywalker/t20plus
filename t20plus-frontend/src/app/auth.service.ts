import { Injectable, signal } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { ApiService, AuthResponse } from './api.service';

const TOKEN_KEY = 'auth_token';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private readonly isAuthenticatedSignal = signal(!!localStorage.getItem(TOKEN_KEY));

  constructor(private apiService: ApiService) {}

  getIsAuthenticatedSignal() {
    return this.isAuthenticatedSignal.asReadonly();
  }

  getToken(): string | null {
    return localStorage.getItem(TOKEN_KEY);
  }

  /**
   * Dev-only login: no real credentials, just issues a token for the
   * seeded user. Swap the call this makes when real Google login lands —
   * the token storage/interceptor plumbing stays the same either way.
   */
  login(): Observable<AuthResponse> {
    return this.apiService.devLogin().pipe(
      tap((response) => {
        localStorage.setItem(TOKEN_KEY, response.token);
        this.isAuthenticatedSignal.set(true);
      }),
    );
  }

  logout(): void {
    localStorage.removeItem(TOKEN_KEY);
    this.isAuthenticatedSignal.set(false);
  }
}
