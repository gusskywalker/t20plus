import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterActiveEffectRow, Power } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { resolveTag } from '../../helpers/tag-solver/tag-solver';
import { spendPm } from '../../helpers/spend-pm/spend-pm';
import { spendTibares } from '../../helpers/spend-tibares/spend-tibares';
import { UseCharacter } from '../../hooks/use-character';

export interface SelectedPower {
  effect: CharacterActiveEffectRow;
  power: Power;
  iconFileName: string | undefined;
}

/**
 * Power detail modal — click a power card, see its full description,
 * Ativar/Usar, Favoritar, Remover. Pulled out of character-main (was
 * previously inline there, composed via <app-modal [hideButtonRow]="true">
 * with its own button row placed inside the projected content, which meant
 * it scrolled with the rest of the content instead of staying pinned to
 * the bottom). Owns its own modal chrome (copied from shared/modal/
 * modal.scss, same as attack-modal/item-details-modal) instead, so its
 * button row sits outside .modal-content like every other own-chrome
 * modal's does.
 */
@Component({
  selector: 'app-power-details-modal',
  imports: [],
  templateUrl: './power-details-modal.html',
  styleUrl: './power-details-modal.scss',
})
export class PowerDetailsModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  power = input.required<SelectedPower>();
  cancel = output<void>();

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  // Ativar/Desativar — only shown for usability 'active' powers with a
  // real duration (persists until turned off, e.g. Percepção Temporal).
  // Flips is_active directly, same simple PATCH-and-close pattern as
  // toggleWorn. PM is only spent on the way to ON — turning a power off
  // doesn't refund or re-charge anything.
  protected toggleActivePower(): void {
    const character = this.character();
    const { effect, power } = this.power();
    if (!effect.is_active) {
      spendPm(this.apiService, this.useCharacter, this.id(), character, power.pm_cost);
      spendTibares(this.apiService, this.useCharacter, this.id(), character, resolveTag(power.effects ?? [], 'spend_tibares'));
    }
    this.apiService.updateCharacterActiveEffect(character.id, effect.id, !effect.is_active).subscribe((active_effects) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_effects });
    });
    this.cancel.emit();
  }

  // Usar — for usability 'active' powers with duration: null (resolves
  // instantly, e.g. Medicina). There's no ongoing state for is_active to
  // represent here (nothing persists a moment later), so this only spends
  // the PM cost and closes the modal — self-reported, same as everywhere
  // else without a combat/roll engine.
  protected useInstantPower(): void {
    const character = this.character();
    const { power } = this.power();
    spendPm(this.apiService, this.useCharacter, this.id(), character, power.pm_cost);
    spendTibares(this.apiService, this.useCharacter, this.id(), character, resolveTag(power.effects ?? [], 'spend_tibares'));
    this.cancel.emit();
  }

  protected toggleFavoritePower(): void {
    const character = this.character();
    const { effect } = this.power();
    this.apiService.updateCharacterActiveEffectFavorite(character.id, effect.id, !effect.is_favorite).subscribe((active_effects) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_effects });
    });
    this.cancel.emit();
  }

  // Remover — same deliberate second-click cooldown as item destroy, own
  // independent state — a fresh component instance every time the modal
  // opens (cancel.emit() has the parent remove it from the DOM), so no
  // explicit reset-on-open/reset-on-cancel is needed, unlike the old
  // inline version which had to reset shared character-main state by hand.
  protected readonly removeConfirming = signal(false);
  protected readonly removeReady = signal(false);
  private removeTimeoutId: ReturnType<typeof setTimeout> | null = null;

  protected onRemoveClick(): void {
    if (!this.removeConfirming()) {
      this.removeConfirming.set(true);
      this.removeTimeoutId = setTimeout(() => this.removeReady.set(true), 3000);
      return;
    }
    if (!this.removeReady()) {
      return;
    }
    const character = this.character();
    const { effect } = this.power();
    this.apiService.destroyCharacterActiveEffect(character.id, effect.id).subscribe((active_effects) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_effects });
    });
    this.cancel.emit();
  }
}
