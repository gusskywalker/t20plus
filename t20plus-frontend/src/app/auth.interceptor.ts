import { HttpErrorResponse, HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { catchError, throwError } from 'rxjs';
import { AuthService } from './auth.service';

const TOKEN_KEY = 'auth_token';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const token = localStorage.getItem(TOKEN_KEY);
  const router = inject(Router);
  const authService = inject(AuthService);

  const authedReq = token
    ? req.clone({ setHeaders: { Authorization: `Bearer ${token}` } })
    : req;

  // The google-login exchange's own 401 (invalid Google token) means
  // something else entirely — never a missing/rejected session token — so
  // it's excluded here rather than gating on whether a token was sent.
  // Gating on `token` truthiness missed the no-token-at-all case entirely
  // (a never-logged-in visitor hitting a protected endpoint got a silent
  // 401 with no redirect).
  const isGoogleLogin = req.url.endsWith('/auth/google-login');

  return next(authedReq).pipe(
    catchError((error: HttpErrorResponse) => {
      if (!isGoogleLogin && error.status === 401) {
        authService.logout();
        router.navigate(['/']);
      }

      return throwError(() => error);
    }),
  );
};
