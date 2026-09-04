import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterGolpePessoalRow, Power } from '../../api.service';
import { StaticRegistry } from '../hooks/static-registry';
import { UseCharacter } from '../hooks/use-character';
import { Checkbox } from '../inputs/checkbox/checkbox';
import { TextInput } from '../inputs/text-input/text-input';
import { replaceTormenta0ToO } from '../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';

/**
 * Self-contained Golpe Pessoal build/view modal — same pattern as
 * attack-modal (own chrome, not composed via <app-modal>, multi-page via
 * currentPage).
 */
@Component({
  selector: 'app-golpe-pessoal-modal',
  imports: [Checkbox, TextInput],
  templateUrl: './golpe-pessoal-modal.html',
  styleUrl: './golpe-pessoal-modal.scss',
})
export class GolpePessoalModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  character = input.required<Character>();
  // Route-param string id — NOT character().id (a number). patchCharacterCache
  // keys its cache write by whatever id characterQuery() was built with
  // (character-main.ts's own `id = input.required<string>()`), and a
  // number vs string hashes to a different cache entry entirely, so the
  // write silently lands nowhere. Must be threaded down as its own input
  // rather than derived from character().id.
  id = input.required<string>();
  cancel = output<void>();

  // Explicit screen the modal is on, same convention as attack-modal's
  // currentStep — named currentPage since this isn't a linear roll
  // sequence, more like flipping between a golpe list and a build/view
  // screen. 1: list every golpe slot the character has earned. 2: build an
  // unbuilt slot. 3: view an already-built one (TODO — built next). Set to
  // 1 by default (modal opens on page 1), set to 2 or 3 in selectGolpe()
  // depending on whether the clicked slot is built yet.
  protected readonly currentPage = signal(1);

  // Every golpe slot the character has earned (one per Golpe Pessoal pick,
  // see CharacterController::store) — name/power_ids null means not built
  // yet.
  protected golpes() {
    return this.character().golpes_pessoais ?? [];
  }

  // Which slot page 2/3 is about to show — set alongside the page change,
  // same pairing as attack-modal's selectHand() setting selectedWeapon.
  protected readonly selectedGolpeId = signal<number | null>(null);

  // Golpe name being built — page 2 only, reset on entry/exit so it
  // doesn't carry over between different unbuilt slots.
  protected readonly golpeName = signal('');

  protected selectGolpe(golpe: CharacterGolpePessoalRow): void {
    this.selectedGolpeId.set(golpe.id);
    this.golpeName.set(golpe.name ?? '');
    this.selectedCounts.set(new Map());
    this.currentPage.set(golpe.power_ids === null ? 2 : 3);
  }

  // Voltar (page 2/3) undoes selectGolpe back to the list — same "same
  // button, different label/handler per step" pattern as attack-modal's
  // Rolar/Passou and Cancelar/Falhou.
  protected goBack(): void {
    this.selectedGolpeId.set(null);
    this.golpeName.set('');
    this.selectedCounts.set(new Map());
    this.currentPage.set(1);
  }

  // Golpe Pessoal's menu (ids 116-139, PowerSeeder.php) — hardcoded, same
  // convention as attack-modal's ataqueEspecialPowerIds. Deliberately not
  // filtered by source === 'specific': that source could house some other
  // bespoke build's own menu items later, and this screen must only ever
  // show Golpe Pessoal's own.
  private readonly golpePessoalOptionIds = Array.from({ length: 24 }, (_, i) => 116 + i);

  protected optionPowers(): Power[] {
    return this.golpePessoalOptionIds
      .map((id) => this.staticRegistry.powers.find((p) => p.id === id))
      .filter((power): power is Power => !!power);
  }

  // Grouped by pm_cost, highest first — matches the sourcebook's own "3
  // PM / 2 PM / 1 PM / ..." menu layout.
  protected optionsByCost(): { cost: number; options: Power[] }[] {
    const groups = new Map<number, Power[]>();
    for (const power of this.optionPowers()) {
      const list = groups.get(power.pm_cost) ?? [];
      list.push(power);
      groups.set(power.pm_cost, list);
    }
    return [...groups.entries()].sort((a, b) => b[0] - a[0]).map(([cost, options]) => ({ cost, options }));
  }

  // Repeatable menu items and their cap — Elemental has no stated limit
  // ("mais vezes"), Letal caps at two ("duas vezes"). Any id not listed
  // here is a plain single pick (cap 1). Hardcoded, same convention as
  // golpePessoalOptionIds above.
  private readonly repeatableCaps: Record<number, number> = {
    120: Infinity, // Elemental
    121: 2, // Letal
  };

  // How many times this power is currently picked — 0 or 1 for a plain
  // option, any count up to its cap for a repeatable one.
  protected readonly selectedCounts = signal<Map<number, number>>(new Map());

  protected countFor(powerId: number): number {
    return this.selectedCounts().get(powerId) ?? 0;
  }

  // Checkbox rows to render for this power — always one more than
  // currently checked (an empty "pick again" slot underneath), capped at
  // repeatableCaps (default 1). Elemental: checking the last one reveals
  // another; Letal: stops offering more once 2 are checked; every other
  // power only ever shows its single checkbox.
  protected checkboxSlots(powerId: number): number[] {
    const cap = this.repeatableCaps[powerId] ?? 1;
    const slots = Math.min(this.countFor(powerId) + 1, cap);
    return Array.from({ length: slots }, (_, i) => i);
  }

  protected isSlotChecked(powerId: number, index: number): boolean {
    return index < this.countFor(powerId);
  }

  // Checking the one always-unchecked slot (index === count) adds one;
  // unchecking any checked slot truncates back to it — in practice only
  // ever the last checked slot is clickable while checked, so this just
  // removes one.
  protected toggleSlot(powerId: number, index: number): void {
    const count = this.countFor(powerId);
    const next = new Map(this.selectedCounts());
    next.set(powerId, index < count ? index : index + 1);
    this.selectedCounts.set(next);
  }

  // Never disables an already-checked slot (unchecking to free budget back
  // up must always work) — only the one unchecked "pick again" slot, and
  // only when picking it would push currentCost past maxPm. A negative/0-
  // cost option is never blocked this way (it can't push the total up), so
  // once budget's tight those stay pickable while positive-cost ones lock
  // out — exactly the "só sobrou pra pegar os negativos/0/1" case.
  protected isSlotDisabled(power: Power, index: number): boolean {
    if (index < this.countFor(power.id)) {
      return false;
    }
    return power.pm_cost > this.maxPm() - this.currentCost();
  }

  // Guerreiro's own total level (sum of character_levels rows for that
  // class, not character level overall) — the rulebook's "não pode gastar
  // mais PM em golpes pessoais em uma mesma rodada do que seu limite de
  // PM" cap, shown here as a build-time ceiling reference.
  private readonly guerreiroClassId = 1;

  protected maxPm(): number {
    return (this.character().levels ?? []).filter((level) => level.class_id === this.guerreiroClassId).length;
  }

  protected currentCost(): number {
    return this.optionPowers().reduce((sum, power) => sum + this.countFor(power.id) * power.pm_cost, 0);
  }

  // Flattens selectedCounts into the repeated-id array power_ids actually
  // stores (e.g. Elemental picked twice -> [120, 120]) — id order doesn't
  // matter, only how many times each appears.
  private buildPowerIds(): number[] {
    const ids: number[] = [];
    for (const [powerId, count] of this.selectedCounts()) {
      for (let i = 0; i < count; i++) {
        ids.push(powerId);
      }
    }
    return ids;
  }

  protected canSave(): boolean {
    return this.golpeName().trim() !== '' && this.buildPowerIds().length > 0;
  }

  protected save(): void {
    const golpeId = this.selectedGolpeId();
    if (golpeId === null || !this.canSave()) {
      return;
    }
    this.apiService.updateCharacterGolpePessoal(this.character().id, golpeId, this.golpeName().trim(), this.buildPowerIds()).subscribe((golpes_pessoais) => {
      // this.id() — the route-param string, not character().id — see the
      // id input's own comment above for why that distinction matters.
      this.useCharacter.patchCharacterCache(this.id(), { golpes_pessoais });
      this.goBack();
    });
  }

  // Page 3 — view an already-built golpe.
  protected selectedGolpe(): CharacterGolpePessoalRow | undefined {
    const golpeId = this.selectedGolpeId();
    return this.golpes().find((golpe) => golpe.id === golpeId);
  }

  // One entry per power_ids array item, duplicates included (e.g. Elemental
  // picked 3x shows 3 separate cards) — power_ids is the literal built
  // list, not a deduped set of options.
  protected selectedGolpeEffects(): Power[] {
    return (this.selectedGolpe()?.power_ids ?? [])
      .map((id) => this.staticRegistry.powers.find((power) => power.id === id))
      .filter((power): power is Power => !!power);
  }

  // "Quando sobe de nível, você pode reconstruir seu Golpe Pessoal" — one
  // rebuild opportunity per level-up. guerreiro_level_picked is stamped at
  // save time (see CharacterGolpePessoalController::update); if it matches
  // the character's CURRENT Guerreiro level, this golpe was already
  // (re)built at this level, so no Reconstruir button until the next one.
  protected needsRebuild(): boolean {
    const golpe = this.selectedGolpe();
    return golpe !== undefined && golpe.guerreiro_level_picked !== this.maxPm();
  }

  // TODO: wire up — should re-open page 2 pre-filled with this golpe's
  // current power_ids so the player can edit and re-save.
  protected reconstruir(): void {}

  // Page 4 — read one effect's description, reached from page 3.
  protected readonly selectedEffectPower = signal<Power | null>(null);

  protected viewEffect(power: Power): void {
    this.selectedEffectPower.set(power);
    this.currentPage.set(4);
  }

  // Voltar (page 4) returns to page 3 specifically, not the golpe list —
  // unlike goBack(), this doesn't touch selectedGolpeId/golpeName/
  // selectedCounts, since we're still looking at the same golpe.
  protected closeEffectView(): void {
    this.selectedEffectPower.set(null);
    this.currentPage.set(3);
  }
}
