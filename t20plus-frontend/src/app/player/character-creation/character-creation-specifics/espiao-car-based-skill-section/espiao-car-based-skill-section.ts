import { Component, computed, input, model } from '@angular/core';
import { SearchableDropdown } from '../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Skill } from '../../../../api.service';

const LUTA_SKILL_ID = 19;
const PONTARIA_SKILL_ID = 25;
const NENHUMA = { id: null, name: 'Nenhuma' };

// Espião's "escolha uma perícia (exceto Luta ou Pontaria)... use Carisma
// em vez do atributo original" — an open pick from the character's full
// skill list, so it can't be one of the fixed choice options origins
// otherwise use (see choose_skill_not_combat in tag-library.md). The
// picked skill_id is written into a custom_effect on this origin's
// granted active_effect row at character-creation time.
@Component({
  selector: 'app-espiao-car-based-skill-section',
  imports: [SearchableDropdown],
  templateUrl: './espiao-car-based-skill-section.html',
  styleUrl: './espiao-car-based-skill-section.scss',
})
export class EspiaoCarBasedSkillSection {
  skills = input.required<Skill[]>();
  selectedSkillId = model<number | null>(null);

  protected readonly options = computed(() => [
    NENHUMA,
    ...this.skills().filter((skill) => skill.key_attribute !== 'car' && skill.id !== LUTA_SKILL_ID && skill.id !== PONTARIA_SKILL_ID),
  ]);
}
