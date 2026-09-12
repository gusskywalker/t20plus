import { Component, computed, inject, input, output, signal } from '@angular/core';
import { ApiService, Campaign, Character } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { TextInput } from '../../inputs/text-input/text-input';
import { TormentaDivider } from '../../tormenta-divider/tormenta-divider';
import { UseCharacter } from '../../hooks/use-character';
import { classSummary } from '../../helpers/class-summary/class-summary';

/**
 * Entrar em Campanha — page 1 takes the secret_code/password pair, page 2
 * shows the result (which campaign was joined and its master, or a plain
 * "wrong data" dead end back to page 1). Owns its own modal chrome, same
 * reasoning as every other own-chrome modal here.
 */
@Component({
  selector: 'app-join-campaign-modal',
  imports: [TextInput, TormentaDivider],
  templateUrl: './join-campaign-modal.html',
  styleUrl: './join-campaign-modal.scss',
})
export class JoinCampaignModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  cancel = output<void>();

  protected readonly currentPage = signal<1 | 2>(1);
  protected readonly secretCode = signal('');
  protected readonly password = signal('');
  protected readonly canConfirm = computed(() => this.secretCode().trim() !== '' && this.password().trim() !== '');

  // Set once the request resolves — true only for a real match, never left
  // ambiguous between "wrong code" and "wrong password" (same reasoning as
  // any login form not saying which one was wrong).
  protected readonly succeeded = signal(false);
  protected readonly resultCampaign = signal<Campaign | null>(null);
  protected readonly masterName = signal('');

  // The whole party, including the character that just joined — fetched
  // only once resultCampaign is set (see UseCharacter.campaignCharactersQuery's
  // own null-campaignId guard).
  private readonly campaignCharactersQuery = this.useCharacter.campaignCharactersQuery(() => this.resultCampaign()?.id ?? null);
  protected readonly campaignCharacters = computed(() => this.campaignCharactersQuery.data() ?? []);

  protected iconUrl(fileName: string): string {
    return `${environment.campaignIconsBaseUrl}/${fileName}`;
  }

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

  protected readonly classSummary = classSummary;

  protected confirm(): void {
    this.apiService.joinCampaign(this.character().id, this.secretCode(), this.password()).subscribe({
      next: ({ campaign, master_name }) => {
        this.useCharacter.patchCharacterCache(this.id(), { campaign_id: campaign.id, campaign });
        // patchCharacterCache only touches this character's own cache entry
        // — the campaign's own character-list cache (campaignCharactersQuery
        // below) doesn't know this character exists until it's written in
        // directly too.
        this.useCharacter.patchCampaignCharactersCache(campaign.id, { ...this.character(), campaign_id: campaign.id, campaign });
        this.resultCampaign.set(campaign);
        this.masterName.set(master_name);
        this.succeeded.set(true);
        this.currentPage.set(2);
      },
      error: () => {
        this.succeeded.set(false);
        this.currentPage.set(2);
      },
    });
  }

  protected back(): void {
    this.currentPage.set(1);
  }

  protected confirmResult(): void {
    this.cancel.emit();
  }
}
