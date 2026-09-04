import { Injectable, inject } from '@angular/core';
import { injectQuery } from '@tanstack/angular-query-experimental';
import { lastValueFrom } from 'rxjs';
import { ApiService } from '../../api.service';
import { AuthService } from '../../auth.service';
import { createQueryKeys } from '../constants/query-keys';

const QUERY_KEYS = createQueryKeys();

/**
 * Reference/lookup game data that basically never changes at runtime
 * (races, origins, and later items/spells/etc.) — as opposed to
 * live user-owned data like characters/campaigns.
 */
@Injectable({
  providedIn: 'root',
})
export class StaticRegistry {
  private apiService = inject(ApiService);
  private authService = inject(AuthService);

  racesQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.RACES,
      queryFn: () => lastValueFrom(this.apiService.getRaces()),
      enabled: isAuthenticated(),
    };
  });

  originsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.ORIGINS,
      queryFn: () => lastValueFrom(this.apiService.getOrigins()),
      enabled: isAuthenticated(),
    };
  });

  godsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.GODS,
      queryFn: () => lastValueFrom(this.apiService.getGods()),
      enabled: isAuthenticated(),
    };
  });

  classesQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.CLASSES,
      queryFn: () => lastValueFrom(this.apiService.getClasses()),
      enabled: isAuthenticated(),
    };
  });

  skillsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.SKILLS,
      queryFn: () => lastValueFrom(this.apiService.getSkills()),
      enabled: isAuthenticated(),
    };
  });

  powersQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.POWERS,
      queryFn: () => lastValueFrom(this.apiService.getPowers()),
      enabled: isAuthenticated(),
    };
  });

  accessoriesQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.ACCESSORIES,
      queryFn: () => lastValueFrom(this.apiService.getAccessories()),
      enabled: isAuthenticated(),
    };
  });

  armorsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.ARMORS,
      queryFn: () => lastValueFrom(this.apiService.getArmors()),
      enabled: isAuthenticated(),
    };
  });

  portraitsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.PORTRAITS,
      queryFn: () => lastValueFrom(this.apiService.getPortraits()),
      enabled: isAuthenticated(),
    };
  });

  complicationsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.COMPLICATIONS,
      queryFn: () => lastValueFrom(this.apiService.getComplications()),
      enabled: isAuthenticated(),
    };
  });

  weaponsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.WEAPONS,
      queryFn: () => lastValueFrom(this.apiService.getWeapons()),
      enabled: isAuthenticated(),
    };
  });

  shieldsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.SHIELDS,
      queryFn: () => lastValueFrom(this.apiService.getShields()),
      enabled: isAuthenticated(),
    };
  });

  generalItemsQuery = injectQuery(() => {
    const isAuthenticated = this.authService.getIsAuthenticatedSignal();

    return {
      queryKey: QUERY_KEYS.GENERAL_ITEMS,
      queryFn: () => lastValueFrom(this.apiService.getGeneralItems()),
      enabled: isAuthenticated(),
    };
  });

  get races() {
    return this.racesQuery.data() ?? [];
  }

  get origins() {
    return this.originsQuery.data() ?? [];
  }

  get gods() {
    return this.godsQuery.data() ?? [];
  }

  get classes() {
    return this.classesQuery.data() ?? [];
  }

  get skills() {
    return this.skillsQuery.data() ?? [];
  }

  get powers() {
    return this.powersQuery.data() ?? [];
  }

  get accessories() {
    return this.accessoriesQuery.data() ?? [];
  }

  get armors() {
    return this.armorsQuery.data() ?? [];
  }

  get portraits() {
    return this.portraitsQuery.data() ?? [];
  }

  get complications() {
    return this.complicationsQuery.data() ?? [];
  }

  get weapons() {
    return this.weaponsQuery.data() ?? [];
  }

  get shields() {
    return this.shieldsQuery.data() ?? [];
  }

  get generalItems() {
    return this.generalItemsQuery.data() ?? [];
  }
}
