import { Injectable, inject } from '@angular/core';
import { injectQuery, QueryClient } from '@tanstack/angular-query-experimental';
import { lastValueFrom } from 'rxjs';
import { ApiService, Character } from '../../api.service';
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

  // Writes a known field change straight into the cache — both the detail
  // query (id must match exactly what characterQuery() was called with,
  // e.g. the route's string id) and any entry for it in the list query —
  // instead of invalidate()'s full network refetch. Used right after a
  // PATCH whose new value we already know locally, so the sheet updates
  // the instant the request resolves instead of waiting on a second
  // round-trip just to read back what we just sent.
  patchCharacterCache(id: string | number, partial: Partial<Character>): void {
    this.queryClient.setQueryData<Character>([...QUERY_KEYS.CHARACTERS, 'detail', id], (old) => (old ? { ...old, ...partial } : old));
    this.queryClient.setQueryData<Character[]>(QUERY_KEYS.CHARACTERS, (old) =>
      old?.map((character) => (String(character.id) === String(id) ? { ...character, ...partial } : character)),
    );
  }
}
