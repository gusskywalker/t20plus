import { Routes } from '@angular/router';
import { Login } from './login/login';
import { ModeSelector } from './mode-selector/mode-selector';
import { HomeMaster } from './master/home-master/home-master';
import { HomePlayer } from './player/home-player/home-player';

export const routes: Routes = [
  { path: '', component: Login },
  { path: 'mode', component: ModeSelector },
  { path: 'master', component: HomeMaster },
  { path: 'player', component: HomePlayer },
];
