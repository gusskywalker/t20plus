import { Component } from '@angular/core';
import { DiceBadge } from '../shared/dice-badge/dice-badge';

@Component({
  selector: 'app-mode-selector',
  imports: [DiceBadge],
  templateUrl: './mode-selector.html',
  styleUrl: './mode-selector.scss',
})
export class ModeSelector {}
