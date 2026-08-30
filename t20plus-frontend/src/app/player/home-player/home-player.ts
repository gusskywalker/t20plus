import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../shared/card-header/card-header';
import { UseCharacter } from '../../shared/hooks/use-character';

@Component({
  selector: 'app-home-player',
  imports: [CardHeader],
  templateUrl: './home-player.html',
  styleUrl: './home-player.scss',
})
export class HomePlayer {
  private readonly useCharacter = inject(UseCharacter);
  private readonly router = inject(Router);

  protected get characters() {
    return this.useCharacter.characters;
  }

  createCharacter(): void {
    this.router.navigate(['/character-creation-step-1']);
  }
}
