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
  'campanha_01.webp',
  'campanha_02.webp',
  'campanha_03.webp',
  'campanha_04.webp',
  'campanha_05.webp',
  'campanha_06.webp',
  'campanha_07.webp',
  'campanha1_01.webp',
  'campanha2_01.webp',
  'campanha3_01.webp',
  'campanha4_01.webp',
  'campanha4_02.webp',
  'campanha5_01.webp',
  'campanha6_01.webp',
  'campanha7_01.webp',
  'campanha8_01.webp',
  'campanha9_01.webp',
  'campanha10_01.webp',
  'campanha11_01.webp',
  'campanha12_01.webp',
  'campanha13_01.webp',
  'campanha14_01.webp',
  'campanha16_01.webp',
  'campanha17_01.webp',
  'campanha18_01.webp',
  'campanha19_01.webp',
  'campanha20_01.webp',
  'campanha21_01.webp',
  'campanha22_01.webp',
  'campanha23_01.webp',
  'campanha24_01.webp',
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
