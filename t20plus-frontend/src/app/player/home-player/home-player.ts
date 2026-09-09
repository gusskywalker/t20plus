import { Component, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../shared/card-header/card-header';
import { Modal } from '../../shared/modals/modal/modal';
import { ApiService } from '../../api.service';
import { UseCharacter } from '../../shared/hooks/use-character';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-home-player',
  imports: [CardHeader, Modal],
  templateUrl: './home-player.html',
  styleUrl: './home-player.scss',
})
export class HomePlayer {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);
  private readonly router = inject(Router);

  protected get characters() {
    return this.useCharacter.characters;
  }

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

  createCharacter(): void {
    this.router.navigate(['/character-creation-step-1']);
  }

  protected readonly reviveCharacterId = signal<number | null>(null);

  openCharacter(id: number): void {
    const character = this.characters.find((c) => c.id === id);
    if (character?.is_dead) {
      this.reviveCharacterId.set(id);
      return;
    }
    this.router.navigate(['/character', id]);
  }

  protected cancelReviveModal(): void {
    this.reviveCharacterId.set(null);
  }

  protected confirmRevive(): void {
    const id = this.reviveCharacterId();
    if (id === null) {
      return;
    }
    this.apiService.updateCharacter(id, { is_dead: false }).subscribe(() => {
      this.useCharacter.patchCharacterCache(id, { is_dead: false });
      this.reviveCharacterId.set(null);
      this.router.navigate(['/character', id]);
    });
  }
}
