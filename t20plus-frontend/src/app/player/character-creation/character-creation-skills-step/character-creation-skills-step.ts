import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Checkbox } from '../../../shared/inputs/checkbox/checkbox';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { ClassSkillGroup } from '../../../api.service';
import { resolveTag } from '../../../shared/helpers/tag-solver/tag-solver';

@Component({
  selector: 'app-character-creation-skills-step',
  imports: [CardHeader, Checkbox],
  templateUrl: './character-creation-skills-step.html',
  styleUrl: './character-creation-skills-step.scss',
})
export class CharacterCreationSkillsStep {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  private readonly startingClass = computed(() => {
    const classId = this.draft.classIds()[0] ?? null;
    return this.staticRegistry.classes.find((c) => c.id === classId) ?? null;
  });

  private readonly effectiveInt = computed(() => {
    const race = this.staticRegistry.races.find((r) => r.id === this.draft.raceId());
    // finalBaseInt — includes Aumentar Atributo's own permanent mod_base_int
    // (see character-draft.ts), in case the player picked it in step 9 and
    // navigated back here.
    return this.draft.finalBaseInt() + (race?.mod_int ?? 0);
  });

  // Skills already trained via other sources: origin picks (inline
  // {tag:'skill', op:'trains'} choice options, not powers at all) plus any
  // GRANTED power carrying that same shape in its own effects — not just
  // chosen god powers, since any granted power (a Linhagem, a general
  // "Treinado em..." pick, a class power) can train a skill this way.
  protected readonly alreadyTrainedSkillIds = computed<Set<number>>(() => {
    const ids = new Set<number>();

    const origin = this.staticRegistry.origins.find((o) => o.id === this.draft.originId());
    const originGroups = origin?.grants ?? [];
    const originChoices = this.draft.originChoices();
    originGroups.forEach((group, gi) => {
      (originChoices[gi] ?? []).forEach((optionIndex) => {
        const option = group.options[optionIndex];
        if (option?.tag === 'skill' && option.op === 'trains' && option.skill_id) {
          ids.add(option.skill_id);
        }
      });
    });

    const powers = this.staticRegistry.powers;
    this.draft.grantedPowerIds().forEach((powerId) => {
      const power = powers.find((p) => p.id === powerId);
      (power?.effects ?? []).forEach((effect) => {
        if (effect.tag === 'skill' && effect.op === 'trains' && effect.skill_id) {
          ids.add(effect.skill_id);
        }
      });
    });

    return ids;
  });

  // Stage 1: pretraining-only filtering. This is a fixed, external fact
  // (already trained via origin/god), so it's what decides whether a group
  // is intrinsically forced (1 option, 1 pick) — never what the player has
  // picked elsewhere on this same screen, which is live and reversible.
  // `picks` itself never shrinks here, only `options` narrows.
  private readonly pretrainingFilteredGroups = computed<ClassSkillGroup[]>(() => {
    const rawGroups = this.startingClass()?.skills ?? [];
    const bonus = Math.max(0, this.effectiveInt());
    const pretrained = this.alreadyTrainedSkillIds();
    return rawGroups.map((group, i) => ({
      picks: i === rawGroups.length - 1 ? group.picks + bonus : group.picks,
      options: group.options.filter((id) => !pretrained.has(id)),
    }));
  });

  // Stage 2: mutual cross-group narrowing — the same skill can appear in
  // more than one group (e.g. Luta/Pontaria's own group and the broad
  // pool), and picking it in one group disables it (not removes it) as an
  // option everywhere else, so the group's own option list stays visually
  // stable instead of shrinking. This never promotes a group into the
  // forced/guaranteed list (that's stage 1's job) and never changes
  // `picks`. If a pick is undone, the option re-enables elsewhere.
  protected readonly groups = computed<{ picks: number; options: number[]; disabledIds: Set<number> }[]>(() => {
    const stage1 = this.pretrainingFilteredGroups();
    const selections = this.draft.classSkillChoices();
    return stage1.map((group, i) => {
      const selectedElsewhere = new Set<number>();
      selections.forEach((ids, gi) => {
        if (gi !== i) {
          ids.forEach((id) => selectedElsewhere.add(id));
        }
      });
      return {
        picks: group.picks,
        options: group.options,
        disabledIds: new Set(group.options.filter((id) => selectedElsewhere.has(id))),
      };
    });
  });

  // How many of a group's still-available (stage 2) options need picking.
  // Normally just `picks`, but capped at the available (non-disabled)
  // count for the over-satisfied case — mutual narrowing can disable
  // enough of a group's options to leave fewer than its original `picks`.
  private effectivePicksNeeded(group: { picks: number; options: number[]; disabledIds: Set<number> }): number {
    return Math.min(group.picks, group.options.length - group.disabledIds.size);
  }

  // Groups with exactly one possible option and exactly one pick, based on
  // stage 1 only — no real choice, so shown together with the
  // already-trained skills instead of a fake "Escolha 1". A group that
  // only becomes over-satisfied via stage 2's mutual narrowing does NOT
  // qualify — it stays visible with fewer clicks needed instead.
  protected readonly forcedSkillIds = computed<number[]>(() => {
    return this.pretrainingFilteredGroups()
      .filter((group) => group.picks === 1 && group.options.length === 1)
      .map((group) => group.options[0]);
  });

  protected readonly guaranteedSkillIds = computed<number[]>(() => {
    return [...this.alreadyTrainedSkillIds(), ...this.forcedSkillIds()];
  });

  // Free skill picks from anywhere. Two sources feed the same shared pool:
  // the choosing mechanic's own two-alternative choice (Humano's Versátil /
  // Lefou's Deformidade, resolved via draft.choosingMechanicChoice — can't
  // be read off the power's own effects since the value depends on which
  // alternative was picked) and any other granted power carrying a flat
  // free_skills_choice value (e.g. Kliren's Híbrido, always 1, no choice
  // needed). WHERE the budget gets spent is flexible: extra picks in an
  // already-visible class group (beyond that group's own base `picks`) and
  // picks in the hidden-skills section below both draw from this same pool.
  private readonly grantedFreeSkillsBudget = computed<number>(() => {
    const powers = this.staticRegistry.powers;
    let sum = 0;
    this.draft.grantedPowerIds().forEach((id) => {
      const power = powers.find((p) => p.id === id);
      sum += resolveTag(power?.effects ?? [], 'free_skills_choice');
    });
    return sum;
  });

  protected readonly choosingMechanicBudget = computed<number>(() => {
    const choice = this.draft.choosingMechanicChoice();
    const choiceBudget = choice === 'skills' ? 2 : choice === 'skill_and_power' ? 1 : 0;
    // Osteon's Memória Póstuma — its own separate 3-way choice (skill /
    // general power / trocar raça base), contributing to the same shared
    // pool as Humano/Lefou's own choice whenever 'skill' is picked.
    const memoriaPostumaBudget = this.draft.memoriaPostumaChoice() === 'skill' ? 1 : 0;
    return choiceBudget + memoriaPostumaBudget + this.grantedFreeSkillsBudget();
  });

  private extraSpentInClassGroups(): number {
    const stage1 = this.pretrainingFilteredGroups();
    const selections = this.draft.classSkillChoices();
    return stage1.reduce((sum, group, i) => sum + Math.max(0, (selections[i]?.length ?? 0) - group.picks), 0);
  }

  protected readonly choosingMechanicRemaining = computed<number>(() => {
    return this.choosingMechanicBudget() - this.extraSpentInClassGroups() - this.draft.choosingMechanicSkillIds().length;
  });

  // Every skill NOT offered by any of the starting class's own groups —
  // Versátil/Deformidade's "não precisam ser da sua classe" clause needs
  // the full catalog, not just this class's own lists. Already-trained
  // skills are excluded same as everywhere else on this screen, and so is
  // anything currently checked in the restricted section below (same skill
  // shouldn't be simultaneously pickable from both a wide-open and a
  // narrow pool at once).
  protected readonly hiddenSkillOptions = computed<number[]>(() => {
    if (this.choosingMechanicBudget() === 0) {
      return [];
    }
    const classGroupSkillIds = new Set((this.startingClass()?.skills ?? []).flatMap((group) => group.options));
    const pretrained = this.alreadyTrainedSkillIds();
    const restrictedPicked = new Set(this.draft.restrictedSkillChoiceIds());
    return this.staticRegistry.skills.map((s) => s.id).filter((id) => !classGroupSkillIds.has(id) && !pretrained.has(id) && !restrictedPicked.has(id));
  });

  // free_skills_choice effects that carry their own skill_ids (e.g. Papel
  // Tribal) — kept as a SEPARATE budget/section from choosingMechanicBudget
  // above rather than merged into that one shared open pool, since mixing
  // an unrestricted source with a restricted one into a single counter is
  // a real constraint problem (which pick "spends" which source) that
  // doesn't otherwise come up. Grouped by their exact skill_ids set (sorted,
  // joined) so two different powers granting the same restricted choice
  // correctly stack into one bigger pick count instead of two separate
  // sections.
  protected readonly restrictedSkillGroups = computed<{ key: string; options: number[]; budget: number }[]>(() => {
    const pretrained = this.alreadyTrainedSkillIds();
    const powers = this.staticRegistry.powers;
    const groups = new Map<string, { skillIds: number[]; budget: number }>();
    this.draft.grantedPowerIds().forEach((id) => {
      const power = powers.find((p) => p.id === id);
      (power?.effects ?? []).forEach((effect) => {
        if (effect.tag !== 'free_skills_choice' || effect.op !== 'grant' || !effect.skill_ids || effect.skill_ids.length === 0) {
          return;
        }
        const key = [...effect.skill_ids].sort((a, b) => a - b).join(',');
        const existing = groups.get(key);
        if (existing) {
          existing.budget += Number(effect.value ?? 0);
        } else {
          groups.set(key, { skillIds: [...effect.skill_ids], budget: Number(effect.value ?? 0) });
        }
      });
    });
    return [...groups.entries()].map(([key, group]) => ({ key, options: group.skillIds.filter((id) => !pretrained.has(id)), budget: group.budget }));
  });

  // A skill option shown but unclickable — checked/picked in a class group
  // or the open pool this session, same "disabled, not removed" convention
  // as isDisabledElsewhere below (a live/reversible pick, unlike pretrained
  // which is a fixed external fact and gets removed from options instead).
  protected isRestrictedSkillDisabledElsewhere(skillId: number): boolean {
    return this.draft.classSkillChoices().flat().includes(skillId) || this.draft.choosingMechanicSkillIds().includes(skillId);
  }

  protected isRestrictedSkillSelected(skillId: number): boolean {
    return this.draft.restrictedSkillChoiceIds().includes(skillId);
  }

  protected restrictedGroupRemaining(group: { options: number[]; budget: number }): number {
    const selectedInGroup = this.draft.restrictedSkillChoiceIds().filter((id) => group.options.includes(id)).length;
    return group.budget - selectedInGroup;
  }

  protected isRestrictedSkillCapped(group: { options: number[]; budget: number }, skillId: number): boolean {
    return !this.isRestrictedSkillSelected(skillId) && this.restrictedGroupRemaining(group) <= 0;
  }

  protected toggleRestrictedSkill(group: { options: number[]; budget: number }, skillId: number): void {
    if (this.isRestrictedSkillDisabledElsewhere(skillId)) {
      return;
    }
    const current = this.draft.restrictedSkillChoiceIds();
    if (current.includes(skillId)) {
      this.draft.restrictedSkillChoiceIds.set(current.filter((id) => id !== skillId));
    } else if (!this.isRestrictedSkillCapped(group, skillId)) {
      this.draft.restrictedSkillChoiceIds.set([...current, skillId]);
    }
  }

  protected isChoosingMechanicSkillSelected(skillId: number): boolean {
    return this.draft.choosingMechanicSkillIds().includes(skillId);
  }

  protected isChoosingMechanicSkillCapped(skillId: number): boolean {
    return !this.isChoosingMechanicSkillSelected(skillId) && this.choosingMechanicRemaining() <= 0;
  }

  protected toggleChoosingMechanicSkill(skillId: number, checked: boolean): void {
    const current = this.draft.choosingMechanicSkillIds();
    if (checked) {
      if (current.includes(skillId) || this.choosingMechanicRemaining() <= 0) {
        return;
      }
      this.draft.choosingMechanicSkillIds.set([...current, skillId]);
    } else {
      this.draft.choosingMechanicSkillIds.set(current.filter((id) => id !== skillId));
    }
  }

  // Visibility depends only on stage 1 (mutual narrowing never removes a
  // group from view) — what's rendered inside a visible group still
  // reflects live stage 2 narrowing.
  protected readonly visibleGroups = computed(() => {
    const stage1 = this.pretrainingFilteredGroups();
    const stage2 = this.groups();
    return stage1
      .map((group, index) => ({
        visible: !(group.picks === 1 && group.options.length === 1),
        index,
      }))
      .filter((x) => x.visible)
      .map(({ index }) => ({ group: stage2[index], index }));
  });

  constructor() {
    // Reset classSkillChoices whenever race, class, origin, or god actually
    // changes — each feeds this screen (class shapes the groups; race,
    // origin, and god all feed alreadyTrainedSkillIds), so stale choices
    // made under a previous combo could silently point at the wrong
    // skills for the new one.
    effect(() => {
      const classId = this.startingClass()?.id ?? null;
      if (classId === null) {
        return;
      }
      const key = [this.draft.raceId(), classId, this.draft.originId(), this.draft.godId()].join(
        ':',
      );
      if (this.draft.classSkillChoicesSourceKey() === key) {
        return;
      }
      this.draft.classSkillChoicesSourceKey.set(key);
      this.draft.classSkillChoices.set([]);
      // hiddenSkillOptions depends on the same class/race/origin/god combo
      // (which skills count as "hidden" shifts with the starting class) —
      // stale choosing-mechanic picks could point at a skill that's no
      // longer hidden (or vice versa).
      this.draft.choosingMechanicSkillIds.set([]);
    });

    // Forced groups (1 option, 1 pick, stage 1) always resolve to that
    // option — they're not rendered as togglable, so this is what applies
    // the pick.
    effect(() => {
      const stage1 = this.pretrainingFilteredGroups();
      const current = this.draft.classSkillChoices();
      const next = stage1.map((group, i) =>
        group.picks === 1 && group.options.length === 1 ? group.options : current[i] ?? [],
      );
      if (JSON.stringify(next) !== JSON.stringify(current)) {
        this.draft.classSkillChoices.set(next);
      }
    });
  }

  protected skillName(skillId: number): string {
    return this.staticRegistry.skills.find((s) => s.id === skillId)?.name ?? 'Perícia desconhecida';
  }

  protected isSelected(groupIndex: number, skillId: number): boolean {
    return this.draft.classSkillChoices()[groupIndex]?.includes(skillId) ?? false;
  }

  // Remaining base picks still needed IN THIS group specifically — the
  // "Escolha N" label counts down as the player checks boxes here,
  // independent of the shared budget shown alongside it (checking a box
  // in a DIFFERENT group, or in Perícias Adicionais, never changes this).
  protected remainingGroupPicks(groupIndex: number): number {
    const group = this.groups()[groupIndex];
    if (!group) {
      return 0;
    }
    const selected = this.draft.classSkillChoices()[groupIndex] ?? [];
    return Math.max(0, this.effectivePicksNeeded(group) - selected.length);
  }

  // A group is capped at its own base `picks` UNLESS the choosing
  // mechanic's shared budget still has room (and this group's own option
  // list has more to give) — picking beyond base here spends from that
  // shared pool, same as picking in the hidden-skills section below does.
  // EXCEPT a `picks: 1` group (Guerreiro/Caçador's own "Luta ou Pontaria"
  // shape, options.length > 1) — every such group across every class is a
  // fixed either/or, never a "pick 1, extend with more if you can" pool,
  // so it stays capped at exactly 1 regardless of leftover budget. Only a
  // `picks > 1` group (a genuine pool) can ever extend past base.
  protected isCapped(groupIndex: number): boolean {
    const group = this.groups()[groupIndex];
    if (!group) {
      return false;
    }
    const selected = this.draft.classSkillChoices()[groupIndex] ?? [];
    const baseNeeded = this.effectivePicksNeeded(group);
    if (selected.length < baseNeeded) {
      return false;
    }
    if (group.picks === 1) {
      return true;
    }
    const availableOptions = group.options.length - group.disabledIds.size;
    return selected.length >= availableOptions || this.choosingMechanicRemaining() <= 0;
  }

  // Fixed section title per group shape — the either/or (picks: 1, e.g.
  // Luta ou Pontaria) vs. the class's own broader pool (picks > 1).
  protected groupHeaderLabel(groupIndex: number): string {
    return this.groups()[groupIndex]?.picks === 1 ? 'Perícias Iniciais' : 'Perícias da Classe';
  }

  // The dynamic " - Escolha N (+X)" suffix — vanishes entirely once
  // nothing is left to choose in this group at all (isCapped), rather
  // than lingering as a confusing "Escolha 0". While something's still
  // choosable: shows the remaining base count, the remaining shared
  // budget (only for a picks > 1 group — see isCapped), or both.
  protected groupChoiceSuffix(groupIndex: number): string {
    const group = this.groups()[groupIndex];
    if (!group || this.isCapped(groupIndex)) {
      return '';
    }
    const remaining = this.remainingGroupPicks(groupIndex);
    const budget = this.choosingMechanicRemaining();
    const showBudget = group.picks > 1 && budget > 0;
    if (remaining > 0) {
      return showBudget ? ` - Escolha ${remaining} (+${budget})` : ` - Escolha ${remaining}`;
    }
    return showBudget ? ` - (+${budget})` : '';
  }

  // A skill already picked in a different group — shown here disabled
  // rather than removed, so the group's option list stays visually stable.
  protected isDisabledElsewhere(groupIndex: number, skillId: number): boolean {
    return this.groups()[groupIndex]?.disabledIds.has(skillId) ?? false;
  }

  protected toggle(groupIndex: number, skillId: number): void {
    if (this.isDisabledElsewhere(groupIndex, skillId)) {
      return;
    }

    const all = [...this.draft.classSkillChoices()];
    const current = all[groupIndex] ?? [];

    if (current.includes(skillId)) {
      all[groupIndex] = current.filter((id) => id !== skillId);
    } else if (!this.isCapped(groupIndex)) {
      all[groupIndex] = [...current, skillId];
    } else {
      return;
    }

    this.draft.classSkillChoices.set(all);
  }

  protected readonly canContinue = computed(() => {
    const groups = this.groups();
    if (groups.length === 0) {
      return false;
    }
    const selections = this.draft.classSkillChoices();
    const baseGroupsSatisfied = groups.every(
      (group, i) => (selections[i]?.length ?? 0) >= this.effectivePicksNeeded(group),
    );
    const restrictedGroupsSatisfied = this.restrictedSkillGroups().every((group) => this.restrictedGroupRemaining(group) <= 0);
    return baseGroupsSatisfied && this.choosingMechanicRemaining() <= 0 && restrictedGroupsSatisfied;
  });

  back(): void {
    this.router.navigate(['/character-creation-god-step']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-items-step']);
  }
}
