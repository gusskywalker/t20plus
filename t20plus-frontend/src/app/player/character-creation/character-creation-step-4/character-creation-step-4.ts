import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Checkbox } from '../../../shared/inputs/checkbox/checkbox';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { OriginChoiceGroup, OriginChoiceOption } from '../../../api.service';

@Component({
  selector: 'app-character-creation-step-4',
  imports: [CardHeader, Checkbox],
  templateUrl: './character-creation-step-4.html',
  styleUrl: './character-creation-step-4.scss',
})
export class CharacterCreationStep4 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  private readonly origin = computed(() => {
    const originId = this.draft.originId();
    return this.staticRegistry.origins.find((o) => o.id === originId) ?? null;
  });

  protected readonly groups = computed<OriginChoiceGroup[]>(() => this.origin()?.effects ?? []);

  protected readonly itemsGroup = computed(() => this.groups()[0] ?? null);
  protected readonly otherGroups = computed(() => this.groups().slice(1));

  constructor() {
    // Initialize a default selection per group once the origin's groups load
    // (async via TanStack Query) — only when the group count doesn't match
    // what's already stored, so it doesn't clobber selections the player
    // already made while navigating back and forth. A group with no real
    // choice (picks === options.length) starts fully checked as a
    // convenience, but stays toggleable like any other — it's just a
    // default, not a lock.
    effect(() => {
      const groups = this.groups();
      if (groups.length === 0) {
        return;
      }
      if (this.draft.originChoices().length === groups.length) {
        return;
      }
      this.draft.originChoices.set(
        groups.map((group) =>
          group.picks === group.options.length ? group.options.map((_, i) => i) : [],
        ),
      );
    });
  }

  protected optionLabel(option: OriginChoiceOption): string {
    switch (option.tag) {
      case 'skill': {
        const skill = this.staticRegistry.skills.find((s) => s.id === option.skill_id);
        return skill ? `Treinamento em ${skill.name}` : 'Perícia desconhecida';
      }
      case 'power': {
        const power = this.staticRegistry.powers.find((p) => p.id === option.power_id);
        return power?.name ?? 'Poder desconhecido';
      }
      case 'accessory': {
        const accessory = this.staticRegistry.accessories.find((a) => a.id === option.accessory_id);
        return accessory?.name ?? 'Acessório desconhecido';
      }
      case 'armor': {
        const armor = this.staticRegistry.armors.find((a) => a.id === option.armor_id);
        return armor?.name ?? 'Armadura desconhecida';
      }
      default:
        return option.tag;
    }
  }

  protected isSelected(groupIndex: number, optionIndex: number): boolean {
    return this.draft.originChoices()[groupIndex]?.includes(optionIndex) ?? false;
  }

  protected isCapped(groupIndex: number): boolean {
    const group = this.groups()[groupIndex];
    const selected = this.draft.originChoices()[groupIndex] ?? [];
    return selected.length >= group.picks;
  }

  protected toggle(groupIndex: number, optionIndex: number): void {
    const all = [...this.draft.originChoices()];
    const current = all[groupIndex] ?? [];

    if (current.includes(optionIndex)) {
      all[groupIndex] = current.filter((i) => i !== optionIndex);
    } else if (!this.isCapped(groupIndex)) {
      all[groupIndex] = [...current, optionIndex];
    } else {
      return;
    }

    this.draft.originChoices.set(all);
  }

  protected readonly canContinue = computed(() => {
    const groups = this.groups();
    if (groups.length === 0) {
      return false;
    }
    const selections = this.draft.originChoices();
    return groups.every((group, i) => (selections[i]?.length ?? 0) === group.picks);
  });

  back(): void {
    this.router.navigate(['/character-creation-step-3']);
  }

  continue(): void {
    // Step 5 doesn't exist yet.
  }
}
