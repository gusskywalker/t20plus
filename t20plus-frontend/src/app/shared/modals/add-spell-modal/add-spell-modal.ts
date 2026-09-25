import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterClass, Spell } from '../../../api.service';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { Modal } from '../modal/modal';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';
import { resolveAvailableSpellOptions } from '../../helpers/resolve-available-spell-options/resolve-available-spell-options';
import { calculateMaxSpellCircle } from '../../helpers/calculators/calculate-max-spell-circle/calculate-max-spell-circle';

/**
 * Adicionar Magia — manually learning a spell outside the normal level-up
 * slot flow (Conhecimento Mágico's own "adicione as magias manualmente"
 * note, but usable any time a GM allows an extra spell). Pulled into its
 * own component instead of inlined in character-main (same reasoning as
 * power-details-modal's own extraction) — unlike Adicionar Poder, this one
 * needs a second dependent dropdown (class, then spell), which would have
 * bloated character-main further.
 *
 * Gated by the chosen class's own type/circle cap — a spell needs a real
 * class+level home to be attributed to (resolve-spell-caster-info.ts reads
 * spell_ids per character_levels row for PM-limit/CD-attribute), so an
 * ungated pick would have nowhere sensible to land. Appends onto that
 * class's own highest level row (see CharacterLevelController::addSpell).
 */
@Component({
  selector: 'app-add-spell-modal',
  imports: [Modal, SearchableDropdown],
  templateUrl: './add-spell-modal.html',
  styleUrl: './add-spell-modal.scss',
})
export class AddSpellModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);
  protected readonly staticRegistry = inject(StaticRegistry);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  cancel = output<void>();

  protected readonly classId = signal<number | null>(null);
  protected readonly spellId = signal<number | null>(null);

  // Only classes the character actually has levels in — not every class in
  // the game, unlike Adicionar Poder's catalog-wide power list.
  protected ownClasses(): CharacterClass[] {
    const ownClassIds = new Set((this.character().levels ?? []).map((level) => level.class_id));
    return this.staticRegistry.classes.filter((characterClass) => ownClassIds.has(characterClass.id));
  }

  protected setClassId(value: number | string | null): void {
    this.classId.set(value as number | null);
    this.spellId.set(null);
  }

  protected availableSpells(): Spell[] {
    const classId = this.classId();
    if (classId === null) {
      return [];
    }
    const character = this.character();
    const classLevel = (character.levels ?? []).filter((level) => level.class_id === classId).length;
    const cap = calculateMaxSpellCircle(classId, classLevel);
    // Real known spells only (spell_ids) — other_source_spell_ids (e.g.
    // Pakk's Explosão de Chamas) is a synthetic grant, not a real pick, so
    // it must stay pickable here (that's exactly how it'd become genuinely
    // known and trigger its power's own PM discount).
    const alreadyKnown = new Set((character.levels ?? []).flatMap((level) => level.spell_ids ?? []));
    const granted = new Set((character.active_effects ?? []).filter((effect) => effect.source_inventory_id == null).map((effect) => effect.power_id));
    const options = resolveAvailableSpellOptions({ spells: this.staticRegistry.spells, classId, cap, granted, powers: this.staticRegistry.powers });
    return options.filter((spell) => !alreadyKnown.has(spell.id));
  }

  protected confirm(): void {
    const classId = this.classId();
    const spellId = this.spellId();
    if (classId === null || spellId === null) {
      return;
    }
    this.apiService.addCharacterLevelSpell(this.character().id, { class_id: classId, spell_id: spellId }).subscribe((levels) => {
      this.useCharacter.patchCharacterCache(this.id(), { levels });
    });
    this.cancel.emit();
  }
}
