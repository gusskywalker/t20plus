import { Routes } from '@angular/router';
import { Login } from './login/login';
import { ModeSelector } from './mode-selector/mode-selector';
import { HomeMaster } from './master/home-master/home-master';
import { CampaignCreation } from './master/campaign-creation/campaign-creation';
import { HomePlayer } from './player/home-player/home-player';
import { CharacterMain } from './player/character-page/character-main/character-main';
import { CharacterCreationBasicInfoStep } from './player/character-creation/character-creation-basic-info-step/character-creation-basic-info-step';
import { CharacterCreationAttributesStep } from './player/character-creation/character-creation-attributes-step/character-creation-attributes-step';
import { CharacterCreationAgeStep } from './player/character-creation/character-creation-age-step/character-creation-age-step';
import { CharacterCreationClassesStep } from './player/character-creation/character-creation-classes-step/character-creation-classes-step';
import { CharacterCreationOriginStep } from './player/character-creation/character-creation-origin-step/character-creation-origin-step';
import { CharacterCreationGodStep } from './player/character-creation/character-creation-god-step/character-creation-god-step';
import { CharacterCreationSkillsStep } from './player/character-creation/character-creation-skills-step/character-creation-skills-step';
import { CharacterCreationItemsStep } from './player/character-creation/character-creation-items-step/character-creation-items-step';
import { CharacterCreationPowersStep } from './player/character-creation/character-creation-powers-step/character-creation-powers-step';
import { CharacterCreationSpellsStep } from './player/character-creation/character-creation-spells-step/character-creation-spells-step';
import { CharacterDraft } from './player/character-creation/character-draft';

export const routes: Routes = [
  { path: '', component: Login },
  { path: 'mode', component: ModeSelector },
  { path: 'campaigns', component: HomeMaster },
  { path: 'campaign-creation', component: CampaignCreation },
  { path: 'player', component: HomePlayer },
  { path: 'character/:id', component: CharacterMain },
  {
    path: '',
    providers: [CharacterDraft],
    children: [
      { path: 'character-creation-basic-info-step', component: CharacterCreationBasicInfoStep },
      { path: 'character-creation-attributes-step', component: CharacterCreationAttributesStep },
      { path: 'character-creation-age-step', component: CharacterCreationAgeStep },
      { path: 'character-creation-classes-step', component: CharacterCreationClassesStep },
      { path: 'character-creation-origin-step', component: CharacterCreationOriginStep },
      { path: 'character-creation-god-step', component: CharacterCreationGodStep },
      { path: 'character-creation-skills-step', component: CharacterCreationSkillsStep },
      { path: 'character-creation-items-step', component: CharacterCreationItemsStep },
      { path: 'character-creation-powers-step', component: CharacterCreationPowersStep },
      { path: 'character-creation-spells-step', component: CharacterCreationSpellsStep },
    ],
  },
];
