import { ApplicationConfig, provideBrowserGlobalErrorListeners } from '@angular/core';
import { provideRouter, withComponentInputBinding } from '@angular/router';
import { QueryClient, provideTanStackQuery } from '@tanstack/angular-query-experimental';
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { authInterceptor } from './auth.interceptor';

import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [
    provideBrowserGlobalErrorListeners(),
    provideRouter(routes, withComponentInputBinding()),
    provideHttpClient(withInterceptors([authInterceptor])),
    // staleTime: Infinity — every mutation already patches the cache
    // directly (patchCharacterCache and friends), so a query only ever
    // needs to fetch once per QueryClient lifetime (a real page load, or
    // the first navigation to a page whose data isn't cached yet).
    // refetchOnMount/refetchOnReconnect/refetchOnWindowFocus all gate on
    // staleness, so this alone already makes every one of them a no-op —
    // no more surprise background refetches (and the reload-ish flash they
    // caused) when coming back to the tab. refetchOnWindowFocus: false is
    // kept explicit anyway, redundant under the current global staleTime
    // but still correct as a safety net if any query ever overrides
    // staleTime to something finite.
    provideTanStackQuery(
      new QueryClient({
        defaultOptions: {
          queries: {
            staleTime: Infinity,
            refetchOnWindowFocus: false,
          },
        },
      }),
    )
  ]
};
