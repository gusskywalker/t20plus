import { Component, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { ATTRIBUTE_NAME_LABELS } from '../../../../../shared/constants/translation-constants';

@Component({
  selector: 'app-duende-animal-section',
  imports: [SearchableDropdown],
  templateUrl: './duende-animal-section.html',
  styleUrl: './duende-animal-section.scss',
})
export class DuendeAnimalSection {
  attribute = model<string | null>(null);

  protected readonly attributeItems = Object.entries(ATTRIBUTE_NAME_LABELS).map(([id, name]) => ({ id, name }));
}
