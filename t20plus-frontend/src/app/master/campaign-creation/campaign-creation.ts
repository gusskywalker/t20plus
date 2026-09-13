import { Component, ViewChild, computed, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../shared/card-header/card-header';
import { TextInput } from '../../shared/inputs/text-input/text-input';
import { Modal } from '../../shared/modals/modal/modal';
import { Campaign } from '../../api.service';
import { CampaignCreationSaving } from './campaign-creation-saving/campaign-creation-saving';
import { environment } from '../../../environments/environment';

// Static list of the cropped campaign-icon files in public/images/campaign_icons
// — there's no catalog table for these (unlike Portrait, which needs
// race_ids filtering), so the picker just enumerates the filenames
// directly, same as any other fixed frontend constant.
const CAMPAIGN_ICON_FILE_NAMES = [
  'camp01.webp',
  'camp02.webp',
  'camp03.webp',
  'camp04.webp',
  'camp05.webp',
  'camp06.webp',
  'camp06_01.webp',
  'camp07.webp',
  'camp08.webp',
  'camp08_01.webp',
  'camp08_02.webp',
  'camp08_03.webp',
  'camp08_04.webp',
  'camp08_05.webp',
  'camp08_06.webp',
  'camp09.webp',
  'camp10.webp',
  'camp11.webp',
  'camp12.webp',
  'camp13.webp',
  'camp14.webp',
  'camp15.webp',
  'camp16.webp',
  'camp17.webp',
  'camp18.webp',
  'camp19.webp',
  'camp19_01.webp',
  'camp21.webp',
  'camp21_01.webp',
  'camp22.webp',
  'camp23.webp',
  'camp24.webp',
  'camp25.webp',
  'camp26.webp',
  'camp27.webp',
  'camp28.webp',
  'camp29.webp',
  'camp30.webp',
  'camp31.webp',
  'camp32.webp',
  'camp33.webp',
  'camp33_01.webp',
  'camp33_02.webp',
  'camp33_03.webp',
  'camp33_04.webp',
  'camp34.webp',
  'camp35.webp',
  'camp36.webp',
  'camp37.webp',
  'camp38.webp',
  'camp39.webp',
  'camp40.webp',
  'camp41.webp',
  'camp42.webp',
  'camp43.webp',
  'camp44.webp',
];

@Component({
  selector: 'app-campaign-creation',
  imports: [CardHeader, TextInput, Modal, CampaignCreationSaving],
  templateUrl: './campaign-creation.html',
  styleUrl: './campaign-creation.scss',
})
export class CampaignCreation {
  private readonly router = inject(Router);

  @ViewChild(CampaignCreationSaving) private saving!: CampaignCreationSaving;

  // 1: the form. 2: the created campaign's secret code, shown once and
  // never again from here — same one-way page flow as spell-casting-modal.
  protected readonly currentPage = signal<1 | 2>(1);
  protected readonly createdCampaign = signal<Campaign | null>(null);

  protected readonly name = signal('');
  protected readonly password = signal('');
  protected readonly iconFileName = signal<string | null>(null);

  protected readonly availableIcons = CAMPAIGN_ICON_FILE_NAMES;

  protected readonly showIconModal = signal(false);
  // Tentative pick while the modal is open — only committed on
  // "Selecionar", discarded on "Cancelar" or backdrop dismissal, same
  // convention as character-creation-step-1's own portrait picker.
  protected readonly tentativeIconFileName = signal<string | null>(null);

  protected iconUrl(fileName: string): string {
    return `${environment.campaignIconsBaseUrl}/${fileName}`;
  }

  protected openIconModal(): void {
    this.tentativeIconFileName.set(this.iconFileName());
    this.showIconModal.set(true);
  }

  protected pickTentativeIcon(fileName: string): void {
    this.tentativeIconFileName.set(fileName);
  }

  protected confirmIcon(): void {
    this.iconFileName.set(this.tentativeIconFileName());
    this.showIconModal.set(false);
  }

  protected cancelIconModal(): void {
    this.showIconModal.set(false);
  }

  protected readonly canCreate = computed(() => this.name().trim() !== '' && this.password().trim() !== '' && this.iconFileName() !== null);

  protected create(): void {
    this.saving.save();
  }

  protected onCampaignCreated(campaign: Campaign): void {
    this.createdCampaign.set(campaign);
    this.currentPage.set(2);
  }

  protected confirm(): void {
    this.router.navigate(['/campaigns']);
  }
}
