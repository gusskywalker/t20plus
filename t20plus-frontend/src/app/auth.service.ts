import { Injectable, signal } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { ApiService, AuthResponse } from './api.service';

const TOKEN_KEY = 'auth_token';
const USER_NAME_KEY = 'auth_user_name';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private readonly isAuthenticatedSignal = signal(!!localStorage.getItem(TOKEN_KEY));
  private readonly userNameSignal = signal<string | null>(localStorage.getItem(USER_NAME_KEY));

  constructor(private apiService: ApiService) {}

  getIsAuthenticatedSignal() {
    return this.isAuthenticatedSignal.asReadonly();
  }

  getUserNameSignal() {
    return this.userNameSignal.asReadonly();
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
        localStorage.setItem(USER_NAME_KEY, response.user.name);
        this.isAuthenticatedSignal.set(true);
        this.userNameSignal.set(response.user.name);
      }),
    );
  }

  logout(): void {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_NAME_KEY);
    this.isAuthenticatedSignal.set(false);
    this.userNameSignal.set(null);
  }
}
