import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../shared/card-header/card-header';
import { UseCampaign } from '../../shared/hooks/use-campaign';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-home-master',
  imports: [CardHeader],
  templateUrl: './home-master.html',
  styleUrl: './home-master.scss',
})
export class HomeMaster {
  private readonly useCampaign = inject(UseCampaign);
  private readonly router = inject(Router);

  protected get campaigns() {
    return this.useCampaign.campaigns;
  }

  protected iconUrl(fileName: string): string {
    return `${environment.campaignIconsBaseUrl}/${fileName}`;
  }

  protected createCampaign(): void {
    this.router.navigate(['/campaign-creation']);
  }

  // No campaign-detail screen yet — wired up once it exists.
  protected openCampaign(id: number): void {}
}
