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

  // Every character in one campaign (the whole party) — a fresh query per
  // campaign id, same "callers invoke from their own field initializer"
  // convention as characterQuery() above. campaignId is nullable since a
  // character isn't guaranteed to be in a campaign — the query simply
  // doesn't run until one is joined (see characters.campaign_id).
  campaignCharactersQuery(campaignId: () => number | null) {
    return injectQuery(() => {
      const isAuthenticated = this.authService.getIsAuthenticatedSignal();
      const id = campaignId();

      return {
        queryKey: [...QUERY_KEYS.CHARACTERS, 'by-campaign', id],
        queryFn: () => lastValueFrom(this.apiService.getCampaignCharacters(id as number)),
        enabled: isAuthenticated() && id !== null,
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

  // Inserts/updates one character in a campaign's own character-list cache
  // (campaignCharactersQuery above) — used right after joining a campaign,
  // since that character wasn't in this list at fetch time and nothing
  // else would otherwise tell this cache entry it exists now. No-op if
  // this campaign's list was never actually fetched (same "old ? ... :
  // old" guard as patchCharacterCache).
  patchCampaignCharactersCache(campaignId: number, character: Character): void {
    this.queryClient.setQueryData<Character[]>([...QUERY_KEYS.CHARACTERS, 'by-campaign', campaignId], (old) =>
      old ? (old.some((c) => c.id === character.id) ? old.map((c) => (c.id === character.id ? character : c)) : [...old, character]) : old,
    );
  }
}
