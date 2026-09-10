import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Checkbox } from '../../../shared/inputs/checkbox/checkbox';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { ClassSkillGroup } from '../../../api.service';

@Component({
  selector: 'app-character-creation-step-6',
  imports: [CardHeader, Checkbox],
  templateUrl: './character-creation-step-6.html',
  styleUrl: './character-creation-step-6.scss',
})
export class CharacterCreationStep6 {
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

  // Skills already trained via other sources (origin picks, chosen god
  // powers — anything using the same {tag:'skill', op:'trains'} shape).
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
    this.draft.godPowerIds().forEach((powerId) => {
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

  protected isCapped(groupIndex: number): boolean {
    const group = this.groups()[groupIndex];
    if (!group) {
      return false;
    }
    const selected = this.draft.classSkillChoices()[groupIndex] ?? [];
    return selected.length >= this.effectivePicksNeeded(group);
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
    return groups.every(
      (group, i) => (selections[i]?.length ?? 0) === this.effectivePicksNeeded(group),
    );
  });

  back(): void {
    this.router.navigate(['/character-creation-step-5']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-step-7']);
  }
}
