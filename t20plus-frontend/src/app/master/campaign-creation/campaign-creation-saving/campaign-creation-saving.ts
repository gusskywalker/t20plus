import { Component, inject, input, output, signal } from '@angular/core';
import { Modal } from '../../../shared/modals/modal/modal';
import { ApiService, Campaign } from '../../../api.service';
import { UseCampaign } from '../../../shared/hooks/use-campaign';

// The "Criando..." modal stays up at least this long even if the request
// resolves faster, so it doesn't just flash on screen.
const MIN_SAVING_MS = 4000;

// Same role as character-creation-saving — owns the actual create request
// and the wait-state modal, called from campaign-creation's own Criar
// button via ViewChild.
@Component({
  selector: 'app-campaign-creation-saving',
  imports: [Modal],
  templateUrl: './campaign-creation-saving.html',
  styleUrl: './campaign-creation-saving.scss',
})
export class CampaignCreationSaving {
  private readonly apiService = inject(ApiService);
  private readonly useCampaign = inject(UseCampaign);

  name = input.required<string>();
  password = input.required<string>();
  iconFileName = input.required<string | null>();

  created = output<Campaign>();

  protected readonly saving = signal(false);

  save(): void {
    const startedAt = Date.now();
    this.saving.set(true);

    // Waits out whatever's left of MIN_SAVING_MS before closing the modal,
    // so a fast response doesn't just flash it. success moves to page 2;
    // an error just closes the modal and leaves the master here to retry.
    const closeSavingModal = (onClosed: () => void) => {
      const remaining = Math.max(0, MIN_SAVING_MS - (Date.now() - startedAt));
      setTimeout(() => {
        this.saving.set(false);
        onClosed();
      }, remaining);
    };

    this.apiService.createCampaign({ name: this.name(), password: this.password(), icon_file_name: this.iconFileName() }).subscribe({
      next: (campaign) => {
        this.useCampaign.invalidate();
        closeSavingModal(() => this.created.emit(campaign));
      },
      error: (err) => {
        console.error('Failed to create campaign', err);
        closeSavingModal(() => {});
      },
    });
  }
}
