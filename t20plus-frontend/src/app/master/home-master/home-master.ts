import { Component } from '@angular/core';
import { DiceBadge } from '../../shared/dice-badge/dice-badge';

@Component({
  selector: 'app-home-master',
  imports: [DiceBadge],
  templateUrl: './home-master.html',
  styleUrl: './home-master.scss',
})
export class HomeMaster {}
