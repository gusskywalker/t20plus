import { Routes } from '@angular/router';
import { Login } from './login/login';
import { ModeSelector } from './mode-selector/mode-selector';

export const routes: Routes = [
  { path: '', component: Login },
  { path: 'mode', component: ModeSelector },
];
