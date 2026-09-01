import { Injectable, inject } from '@angular/core';
import { injectQuery, QueryClient } from '@tanstack/angular-query-experimental';
import { lastValueFrom } from 'rxjs';
import { ApiService } from '../../api.service';
import { AuthService } from '../../auth.service';
import { createQueryKeys } from '../constants/query-keys';

const QUERY_KEYS = createQueryKeys();

@Injectable({
  providedIn: 'root',
})
export class UseCharacter {
  private apiService = inject(ApiService);
  private queryClient = inject(QueryClient);
  private authService = inject(AuthService);

  charactersQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.CHARACTERS,
      queryFn: () => lastValueFrom(this.apiService.getCharacters()),
      enabled: isAuthenticated(),
    };
  });

  get characters() {
    return this.charactersQuery.data() ?? [];
  }

  // A per-character detail query, one instance per call site — unlike the
  // list query above, this needs a fresh query per id, so it's a method
  // callers invoke from their own field initializer (still a valid
  // injection context) rather than a single shared field.
  characterQuery(id: () => string | number) {
    return injectQuery(() => {
      const isAuthenticated = this.authService.getIsAuthenticatedSignal();

      return {
        queryKey: [...QUERY_KEYS.CHARACTERS, 'detail', id()],
        queryFn: () => lastValueFrom(this.apiService.getCharacter(id())),
        enabled: isAuthenticated(),
      };
    });
  }

  invalidate() {
    return this.queryClient.refetchQueries({ queryKey: QUERY_KEYS.CHARACTERS });
  }
}
