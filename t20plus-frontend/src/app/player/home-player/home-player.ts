import { Component } from '@angular/core';
import { DiceBadge } from '../../shared/dice-badge/dice-badge';

@Component({
  selector: 'app-home-player',
  imports: [DiceBadge],
  templateUrl: './home-player.html',
  styleUrl: './home-player.scss',
})
export class HomePlayer {}
