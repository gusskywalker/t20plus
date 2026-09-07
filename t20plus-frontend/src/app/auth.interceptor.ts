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

  return next(authedReq).pipe(
    catchError((error: HttpErrorResponse) => {
      // Only force a logout for a token that got rejected — the initial
      // google-login request has no token and its own 401 (invalid Google
      // token) means something else entirely.
      if (token && error.status === 401) {
        authService.logout();
        router.navigate(['/']);
      }

      return throwError(() => error);
    }),
  );
};
