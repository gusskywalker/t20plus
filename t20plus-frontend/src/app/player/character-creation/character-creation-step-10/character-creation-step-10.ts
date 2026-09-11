import { Component, ViewChild, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { CharacterCreationSaving } from '../character-creation-saving/character-creation-saving';

// Arcanista-only step — spell selection isn't built yet, so this is just
// the card shell for now. Step 9's own button routes here instead of
// saving directly whenever the character's first class is Arcanista.
@Component({
  selector: 'app-character-creation-step-10',
  imports: [CardHeader, CharacterCreationSaving],
  templateUrl: './character-creation-step-10.html',
  styleUrl: './character-creation-step-10.scss',
})
export class CharacterCreationStep10 {
  private router = inject(Router);

  @ViewChild(CharacterCreationSaving) private saving!: CharacterCreationSaving;

  back(): void {
    this.router.navigate(['/character-creation-step-9']);
  }

  continue(): void {
    this.saving.save();
  }
}
