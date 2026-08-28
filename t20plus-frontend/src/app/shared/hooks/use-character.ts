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

  invalidate() {
    return this.queryClient.refetchQueries({ queryKey: QUERY_KEYS.CHARACTERS });
  }
}
