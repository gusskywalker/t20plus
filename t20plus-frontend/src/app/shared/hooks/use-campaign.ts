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
export class UseCampaign {
  private apiService = inject(ApiService);
  private queryClient = inject(QueryClient);
  private authService = inject(AuthService);

  campaignsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.CAMPAIGNS,
      queryFn: () => lastValueFrom(this.apiService.getCampaigns()),
      enabled: isAuthenticated(),
    };
  });

  get campaigns() {
    return this.campaignsQuery.data() ?? [];
  }

  invalidate() {
    return this.queryClient.refetchQueries({ queryKey: QUERY_KEYS.CAMPAIGNS });
  }
}
