import { Component, computed, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, Spell } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { resolveSpellCasterInfo } from '../../helpers/resolve-spell-caster-info/resolve-spell-caster-info';
import { calculateSpellCd } from '../../helpers/calculators/calculate-spell-cd/calculate-spell-cd';
import { spendPm } from '../../helpers/spend-pm/spend-pm';
import { Checkbox } from '../../inputs/checkbox/checkbox';

// Base PM cost by círculo (spells-basics.md's own table) — before any
// enhancement picks. Only used here; move to a shared helper if a second
// consumer ever needs it.
const BASE_PM_COST_BY_CIRCLE: Record<number, number> = { 1: 1, 2: 3, 3: 6, 4: 10, 5: 15 };

interface EnhancementRow {
  key: string;
  enhancementIndex: number;
  rowIndex: number;
  label: string;
  checked: boolean;
  disabled: boolean;
}

/**
 * Self-contained spell-casting modal, opened from a spell card in the
 * character sheet's Magias section. Page 1 (the only page built so far) is
 * purely descriptive — same shape as item-details-modal's own page 1
 * (icon, name, an inner-card properties section, an inner-card
 * enhancements section, then the description). Casting itself (PM cost,
 * key attribute, enhancement picks) isn't wired up yet. Owns its own modal
 * chrome (copied from shared/modal/modal.scss, same as attack-modal)
 * instead of composing <app-modal>.
 */
@Component({
  selector: 'app-spell-casting-modal',
  imports: [Checkbox],
  templateUrl: './spell-casting-modal.html',
  styleUrl: './spell-casting-modal.scss',
})
export class SpellCastingModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason attack-modal/golpe-pessoal-modal
  // need their own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  spell = input.required<Spell>();
  cancel = output<void>();

  // 1: descriptive page (icon/properties/enhancements/description). 2: the
  // actual casting flow, not built yet. "Cancelar" on page 1 closes the
  // modal; on page 2 it becomes "Voltar" and just returns to page 1
  // instead, same convention as item-details-modal's own page 2/3/4.
  protected readonly currentPage = signal<1 | 2>(1);

  protected handleCancel(): void {
    if (this.currentPage() === 2) {
      this.currentPage.set(1);
      return;
    }
    this.cancel.emit();
  }

  // Top row's button — "Lançar Magia" on both pages, same position, but
  // page 1's press just moves to page 2 (the enhancement-picking view)
  // while page 2's press is the real cast action.
  protected handleLancarMagia(): void {
    if (this.currentPage() === 1) {
      this.currentPage.set(2);
      return;
    }
    this.castSpell();
  }

  // Flips once Lançar Magia is actually pressed on page 2 — swaps the
  // button row to Passou/Falhou. Nothing beyond that is wired up yet (no
  // character_active_spell_effects row, no resist handling).
  protected readonly hasCast = signal(false);

  private castSpell(): void {
    spendPm(this.apiService, this.useCharacter, this.id(), this.character(), this.pmCost());
    this.hasCast.set(true);
  }

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  protected circleLabel(circle: number): string {
    return `${circle}º Círculo`;
  }

  protected typeLabel(type: string): string {
    const labels: Record<string, string> = {
      arcana: 'Arcana',
      divina: 'Divina',
      universal: 'Universal',
    };
    return labels[type] ?? type;
  }

  protected schoolLabel(school: string): string {
    const labels: Record<string, string> = {
      abjuracao: 'Abjuração',
      adivinhacao: 'Adivinhação',
      convocacao: 'Convocação',
      encantamento: 'Encantamento',
      evocacao: 'Evocação',
      ilusao: 'Ilusão',
      necromancia: 'Necromancia',
      transmutacao: 'Transmutação',
    };
    return labels[school] ?? school;
  }

  protected actionCostLabel(actionCost: string): string {
    const labels: Record<string, string> = {
      standard: 'Ação Padrão',
      movement: 'Ação de Movimento',
      complete: 'Ação Completa',
      extra: 'Ação Extra',
      free: 'Ação Livre',
      none: 'Nenhuma',
      reaction: 'Reação',
    };
    return labels[actionCost] ?? actionCost;
  }

  protected resistanceLabel(resistance: string): string {
    const labels: Record<string, string> = {
      fortitude: 'Fortitude',
      reflexos: 'Reflexos',
      vontade: 'Vontade',
    };
    return labels[resistance] ?? resistance;
  }

  // Which class taught this spell, that class's current level (the PM
  // limit), and its key attribute (the CD attribute) — see
  // resolve-spell-caster-info.ts. Null would mean the spell somehow isn't
  // actually known, which shouldn't happen from how this modal is opened.
  private readonly casterInfo = computed(() => resolveSpellCasterInfo(this.character(), this.spell().id, this.staticRegistry.powers));

  protected readonly cd = computed(() => {
    const info = this.casterInfo();
    return info ? calculateSpellCd(this.character(), info.keyAttribute, this.staticRegistry.powers) : null;
  });

  // The class-level rule caps how much you're ALLOWED to spend, but you
  // still can't spend PM you don't have — whichever is lower actually
  // applies.
  protected readonly pmLimit = computed(() => Math.min(this.casterInfo()?.classLevel ?? 0, this.character().current_pm ?? 0));

  // How many times each enhancement (by its index in spell().enhancements)
  // is currently checked — 0 or 1 for a normal/unique_change entry, 0..N
  // for a repeatable one (each unit stacks its own PM cost/effect).
  private readonly enhancementCounts = signal<Record<number, number>>({});

  protected readonly pmCost = computed(() => {
    const base = BASE_PM_COST_BY_CIRCLE[this.spell().circle] ?? 0;
    const counts = this.enhancementCounts();
    const enhancementsTotal = (this.spell().enhancements ?? []).reduce((sum, enhancement, i) => sum + (counts[i] ?? 0) * enhancement.pm_cost, 0);
    return base + enhancementsTotal;
  });

  // The one unique_change entry currently checked, if any — checking a
  // different one disables every other unique_change checkbox (see
  // enhancementRows below), matching "muda" enhancements never stacking.
  private readonly checkedUniqueChangeIndex = computed(() => {
    const counts = this.enhancementCounts();
    return (this.spell().enhancements ?? []).findIndex((enhancement, i) => enhancement.unique_change && (counts[i] ?? 0) > 0);
  });

  // Flattened list of every checkbox row to render — a repeatable
  // enhancement expands into (checked count + 1) rows (all checked plus
  // one empty "next" one to grow the stack); everything else is exactly
  // one row. A row is disabled (but never hidden) when checking it would
  // either exceed the PM limit or conflict with an already-checked
  // unique_change entry — already-checked rows stay clickable so they can
  // always be unchecked.
  protected readonly enhancementRows = computed<EnhancementRow[]>(() => {
    const enhancements = this.spell().enhancements ?? [];
    const counts = this.enhancementCounts();
    const cost = this.pmCost();
    const limit = this.pmLimit();
    const uniqueCheckedIndex = this.checkedUniqueChangeIndex();
    const rows: EnhancementRow[] = [];

    enhancements.forEach((enhancement, enhancementIndex) => {
      const count = counts[enhancementIndex] ?? 0;
      const label = `[${enhancement.pm_cost}PM] ${enhancement.description}`;
      const rowCount = enhancement.repeatable ? count + 1 : 1;

      for (let rowIndex = 0; rowIndex < rowCount; rowIndex++) {
        const checked = rowIndex < count;
        const wouldExceedLimit = !checked && cost + enhancement.pm_cost > limit;
        const conflictsUniqueChange = !checked && !!enhancement.unique_change && uniqueCheckedIndex !== -1 && uniqueCheckedIndex !== enhancementIndex;
        rows.push({
          key: `${enhancementIndex}-${rowIndex}`,
          enhancementIndex,
          rowIndex,
          label,
          checked,
          // Once actually cast, every pick is locked in — even an already-
          // checked row (normally always clickable to uncheck) stops being
          // interactive.
          disabled: this.hasCast() || wouldExceedLimit || conflictsUniqueChange,
        });
      }
    });

    return rows;
  });

  // Clicking a repeatable enhancement's row sets the stack to that row's
  // position (checking row 2 means "I want 3 of these," unchecking row 1
  // means "back down to 1") — a normal/unique_change row just toggles 0/1.
  protected toggleEnhancementRow(row: EnhancementRow, checked: boolean): void {
    const enhancement = (this.spell().enhancements ?? [])[row.enhancementIndex];
    if (!enhancement) {
      return;
    }
    const counts = { ...this.enhancementCounts() };
    counts[row.enhancementIndex] = enhancement.repeatable ? (checked ? row.rowIndex + 1 : row.rowIndex) : checked ? 1 : 0;
    this.enhancementCounts.set(counts);
  }

  // Self-reported resist outcome — not wired up to anything yet.
  protected markPassed(): void {}

  protected markFailed(): void {}
}
