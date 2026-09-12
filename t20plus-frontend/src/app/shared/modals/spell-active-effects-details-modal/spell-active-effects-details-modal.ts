import { Component, computed, inject, input, output } from '@angular/core';
import { ApiService, Character, CharacterActiveSpellEffectRow, Spell } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { UseCharacter } from '../../hooks/use-character';

export interface SelectedActiveSpellEffect {
  effect: CharacterActiveSpellEffectRow;
  spell: Spell;
}

/**
 * Shows one character_active_spell_effects row — icon, name, and its own
 * effects summed per tag (e.g. "mod_def +6"). No tag-translator yet (see
 * known-todos.md), so a tag just prints as-is rather than a readable
 * label — good enough until most spells are seeded in and the real
 * vocabulary that needs translating is clear. Owns its own modal chrome
 * (copied from shared/modal/modal.scss, same as every other own-chrome
 * modal here) instead of composing <app-modal>.
 */
@Component({
  selector: 'app-spell-active-effects-details-modal',
  imports: [],
  templateUrl: './spell-active-effects-details-modal.html',
  styleUrl: './spell-active-effects-details-modal.scss',
})
export class SpellActiveEffectsDetailsModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  activeSpellEffect = input.required<SelectedActiveSpellEffect>();
  // The character viewing this modal (who the effect is ON, not
  // necessarily who cast it) — only needed for its campaign_id, to look up
  // the caster below.
  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  cancel = output<void>();

  // Whole party, same query/convention as spell-casting-modal's own ally
  // picker — needed here just to resolve caster_character_id to a
  // portrait/name.
  private readonly campaignCharactersQuery = this.useCharacter.campaignCharactersQuery(() => this.character().campaign_id);

  // Only set when someone ELSE cast this — a self-cast (caster_character_id
  // === character_id, e.g. Armadura Arcana) shows nothing extra, same as a
  // null caster_character_id (row seeded/created before this column
  // existed).
  protected readonly caster = computed(() => {
    const { effect } = this.activeSpellEffect();
    if (effect.caster_character_id === null || effect.caster_character_id === effect.character_id) {
      return null;
    }
    return (this.campaignCharactersQuery.data() ?? []).find((c) => c.id === effect.caster_character_id) ?? null;
  });

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

  // No confirm-wait here (unlike power-details-modal's Remover) — a single
  // click removes it.
  protected onRemoveClick(): void {
    const { effect } = this.activeSpellEffect();
    this.apiService.destroyCharacterActiveSpellEffect(effect.character_id, effect.id).subscribe((active_spell_effects) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_spell_effects });
      this.cancel.emit();
    });
  }

  // One line per distinct tag, values summed — a row's effects can carry
  // more than one entry for the same tag (e.g. the base buff plus a
  // checked repeatable enhancement's own addition).
  protected summedEffectLines(): string[] {
    const sums = new Map<string, number>();
    this.activeSpellEffect().effect.effects.forEach((effect) => {
      sums.set(effect.tag, (sums.get(effect.tag) ?? 0) + Number(effect.value ?? 0));
    });
    return [...sums.entries()].map(([tag, value]) => `${tag} ${this.signedValue(value)}`);
  }

  private signedValue(value: number): string {
    return value >= 0 ? `+${value}` : `${value}`;
  }
}
