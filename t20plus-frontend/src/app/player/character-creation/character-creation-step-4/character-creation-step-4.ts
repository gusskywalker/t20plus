import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Checkbox } from '../../../shared/inputs/checkbox/checkbox';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { TormentaDivider } from '../../../shared/tormenta-divider/tormenta-divider';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { GrantGroup, GrantOption } from '../../../api.service';

@Component({
  selector: 'app-character-creation-step-4',
  imports: [CardHeader, Checkbox, SearchableDropdown, TormentaDivider],
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

  protected get origins() {
    return this.staticRegistry.origins;
  }

  protected get draftOriginId() {
    return this.draft.originId;
  }

  protected readonly groups = computed<GrantGroup[]>(() => this.origin()?.grants ?? []);

  protected readonly itemsGroup = computed(() => this.groups()[0] ?? null);
  protected readonly otherGroups = computed(() => this.groups().slice(1));

  constructor() {
    // Dev convenience: pre-fill so this screen doesn't need manual clicking
    // through every test run. TODO: remove once this stops being useful
    // during development.
    effect(() => {
      const origins = this.staticRegistry.origins;
      if (origins.length > 0 && this.draft.originId() === null) {
        this.draft.originId.set(origins[0].id);
      }
    });

    // Reset originChoices whenever the origin actually changes (not just
    // when the new origin's group count happens to differ) — otherwise
    // stale indices from a previous origin could silently point at the
    // wrong options for the new one. A group with no real choice
    // (picks === options.length) starts fully checked as a convenience,
    // but stays toggleable like any other — it's just a default, not a
    // lock.
    effect(() => {
      const originId = this.draft.originId();
      const groups = this.groups();
      if (groups.length === 0) {
        return;
      }
      if (this.draft.originChoicesOriginId() === originId) {
        return;
      }
      this.draft.originChoicesOriginId.set(originId);
      this.draft.originChoices.set(
        groups.map((group) =>
          group.picks === group.options.length ? group.options.map((_, i) => i) : [],
        ),
      );
    });
  }

  protected optionLabel(option: GrantOption): string {
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
    if (this.draft.originId() === null) {
      return false;
    }
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
    this.router.navigate(['/character-creation-step-5']);
  }
}
