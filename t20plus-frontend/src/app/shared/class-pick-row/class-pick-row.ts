import { Component, input, model } from '@angular/core';
import { CharacterClass } from '../../api.service';
import { SearchableDropdown } from '../inputs/searchable-dropdown/searchable-dropdown';
import { ArcanistaPathSection } from '../arcanista-path-section/arcanista-path-section';

/**
 * One "pick a class for this level" row — a class dropdown, plus (only when
 * this row is Arcanista's own class-relative level 1) the Caminho picker
 * beneath it. Shared by character-creation-classes-step (looped, one call
 * per level) and level-change-modal (a single instance, for the one
 * pending level) — both used to render this same markup themselves, from
 * two genuinely different data sources (CharacterDraft's signals vs. a
 * real Character). That data-gathering stays split in each caller; only
 * the rendering — what to DO with whatever they derive — lives here.
 * isFirstArcanistaLevel is a plain boolean input rather than this
 * component figuring it out itself, since "is this row Arcanista's own
 * level 1" is computed differently by each caller (an index into
 * orderedClassIds for the draft, newClassLevel() === 1 for a real
 * character) — this component doesn't need to know which.
 */
@Component({
  selector: 'app-class-pick-row',
  imports: [SearchableDropdown, ArcanistaPathSection],
  templateUrl: './class-pick-row.html',
  styleUrl: './class-pick-row.scss',
})
export class ClassPickRow {
  classes = input.required<CharacterClass[]>();
  label = input.required<string>();
  openUpwards = input(false);
  isFirstArcanistaLevel = input(false);

  classId = model<number | null>(null);
  arcanistaPathPowerId = model<number | null>(null);
  linhagemPowerId = model<number | null>(null);

  // SearchableDropdown's own value/valueChange is typed number | string |
  // null generically — a class id is always numeric in practice, but
  // templates can't `as`-cast, so the narrowing happens here.
  protected setClassId(value: number | string | null): void {
    this.classId.set(value as number | null);
  }
}
