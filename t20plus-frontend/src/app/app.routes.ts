import { Routes } from '@angular/router';
import { Login } from './login/login';
import { ModeSelector } from './mode-selector/mode-selector';
import { HomeMaster } from './master/home-master/home-master';
import { HomePlayer } from './player/home-player/home-player';
import { CharacterCreationStep1 } from './player/character-creation/character-creation-step-1/character-creation-step-1';
import { CharacterCreationStep2 } from './player/character-creation/character-creation-step-2/character-creation-step-2';
import { CharacterCreationStep3 } from './player/character-creation/character-creation-step-3/character-creation-step-3';
import { CharacterCreationStep4 } from './player/character-creation/character-creation-step-4/character-creation-step-4';
import { CharacterCreationStep5 } from './player/character-creation/character-creation-step-5/character-creation-step-5';
import { CharacterCreationStep6 } from './player/character-creation/character-creation-step-6/character-creation-step-6';
import { CharacterCreationStep7 } from './player/character-creation/character-creation-step-7/character-creation-step-7';
import { CharacterDraft } from './player/character-creation/character-draft';

export const routes: Routes = [
  { path: '', component: Login },
  { path: 'mode', component: ModeSelector },
  { path: 'master', component: HomeMaster },
  { path: 'player', component: HomePlayer },
  {
    path: '',
    providers: [CharacterDraft],
    children: [
      { path: 'character-creation-step-1', component: CharacterCreationStep1 },
      { path: 'character-creation-step-2', component: CharacterCreationStep2 },
      { path: 'character-creation-step-3', component: CharacterCreationStep3 },
      { path: 'character-creation-step-4', component: CharacterCreationStep4 },
      { path: 'character-creation-step-5', component: CharacterCreationStep5 },
      { path: 'character-creation-step-6', component: CharacterCreationStep6 },
      { path: 'character-creation-step-7', component: CharacterCreationStep7 },
    ],
  },
];
