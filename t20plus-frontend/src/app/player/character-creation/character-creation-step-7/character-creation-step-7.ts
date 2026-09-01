import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { TormentaDivider } from '../../../shared/tormenta-divider/tormenta-divider';
import { Checkbox } from '../../../shared/inputs/checkbox/checkbox';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { SecondarySegment } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { GrantOption } from '../../../api.service';

const NENHUMA = { id: null, name: 'Nenhuma' };

const ATTRIBUTE_LABELS: Record<string, string> = {
  mod_str: 'FOR',
  mod_dex: 'DEX',
  mod_con: 'CON',
  mod_int: 'INT',
  mod_knw: 'SAB',
  mod_car: 'CAR',
};

interface AgeBracketItem {
  id: string | null;
  name: string;
  mod_str?: number;
  mod_dex?: number;
  mod_con?: number;
  mod_int?: number;
  mod_knw?: number;
  mod_car?: number;
  // Names of the bracket's other age_granted powers (not attribute mods) —
  // e.g. Criança's Tamanho Menor/Protegido dos Deuses/Sem Origem.
  extraPowers?: string[];
}

// Fixed list, not DB-backed — T20's faixas etárias. Stat mods/extra powers
// are filled in per bracket as its own age_granted powers get seeded;
// still-bare entries just haven't been done yet.
const AGE_BRACKETS: AgeBracketItem[] = [
  { id: null, name: 'Nenhuma' },
  {
    id: 'criança',
    name: 'Criança',
    mod_str: -2,
    mod_con: -1,
    mod_knw: -1,
    extraPowers: ['Tamanho Menor', 'Protegido dos Deuses', 'Sem Origem'],
  },
  {
    id: 'adolescente',
    name: 'Adolescente',
    mod_knw: -1,
    extraPowers: ['Ímpeto Juvenil', 'Origem em Construção'],
  },
  { id: 'jovem', name: 'Jovem' },
  {
    id: 'adulto',
    name: 'Adulto',
    extraPowers: ['Poder Geral', 'Complicação (idade)'],
  },
  {
    id: 'maduro',
    name: 'Maduro',
    extraPowers: ['Nível Extra', 'Duas Complicações (Idade)'],
  },
  {
    id: 'velho',
    name: 'Velho',
    mod_str: -1,
    mod_dex: -1,
    mod_con: -1,
    extraPowers: [
      'Dois Níveis Extras',
      'Três Complicações (Idade)',
      'Aumento de Atributo bloqueado para atributos físicos',
    ],
  },
  {
    id: 'anciao',
    name: 'Ancião',
    mod_str: -2,
    mod_dex: -2,
    mod_con: -2,
    extraPowers: [
      'Três Níveis Extras',
      'Quatro Complicações (Idade)',
      'Aumento de Atributo bloqueado para atributos físicos',
    ],
  },
];

@Component({
  selector: 'app-character-creation-step-7',
  imports: [CardHeader, SearchableDropdown, NumberInput, TormentaDivider, Checkbox],
  templateUrl: './character-creation-step-7.html',
  styleUrl: './character-creation-step-7.scss',
})
export class CharacterCreationStep7 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  constructor() {
    // Dev convenience: pre-fill so this screen doesn't need manual typing
    // through every test run. Only applies to a fresh draft.
    // TODO: remove once this stops being useful during development.
    if (this.draft.age() === null) {
      this.draft.age.set(30);
    }

    // Clear the bonus Poder Geral whenever Nenhuma is (re-)picked, and keep
    // its dropdown disabled in that state (see the [disabled] binding in
    // the template) — the power only means anything alongside a real
    // complication.
    effect(() => {
      if (this.draft.generalComplicationId() === null) {
        this.draft.generalComplicationPowerId.set(null);
      }
    });

    // Clear the picked bonus power if it stops being a valid option — e.g.
    // the player goes back and picks it from the origin/god screens
    // instead, after already having it selected here.
    effect(() => {
      const powerId = this.draft.generalComplicationPowerId();
      if (powerId !== null && this.alreadyPickedPowerIds().has(powerId)) {
        this.draft.generalComplicationPowerId.set(null);
      }
    });

    // Clear the Origem em Construção override once it's no longer valid —
    // Adolescente stopped being the picked bracket, or the recorded id
    // isn't among the current pick items anymore (origin/class changed
    // beneath it).
    effect(() => {
      const current = this.draft.adolescenteOverride();
      if (current.length === 0) {
        return;
      }
      const validIds = new Set(this.adolescentePickItems().map((item) => item.id));
      if (!current.every((id) => validIds.has(id))) {
        this.draft.adolescenteOverride.set([]);
      }
    });

    // Clear Adulto's two mandatory picks once Adulto stops being the
    // picked bracket.
    effect(() => {
      if (this.draft.ageBracket() !== 'adulto') {
        this.draft.adultoPowerId.set(null);
        this.draft.adultoAgeComplicationId.set(null);
      }
    });

    // Clear Maduro's mandatory picks once Maduro stops being the picked
    // bracket.
    effect(() => {
      if (this.draft.ageBracket() !== 'maduro') {
        this.draft.maduroClassId.set(null);
        this.draft.maduroAgeComplicationIds.set([null, null]);
      }
    });

    // Clear Velho's mandatory picks once Velho stops being the picked
    // bracket.
    effect(() => {
      if (this.draft.ageBracket() !== 'velho') {
        this.draft.velhoClassIds.set([null, null]);
        this.draft.velhoAgeComplicationIds.set([null, null, null]);
      }
    });

    // Clear Ancião's mandatory picks once Ancião stops being the picked
    // bracket.
    effect(() => {
      if (this.draft.ageBracket() !== 'anciao') {
        this.draft.anciaoClassIds.set([null, null, null]);
        this.draft.anciaoAgeComplicationIds.set([null, null, null, null]);
      }
    });
  }

  // generalComplicationId defaults to null, which this list itself defines
  // as "Nenhuma" — that's the real default, not a dev-only pre-fill.
  // Includes both general and class complications — no class complications
  // are seeded yet, but the type already belongs here once they exist.
  protected readonly generalComplicationItems = computed(() => [
    NENHUMA,
    ...this.staticRegistry.complications.filter(
      (c) => c.type === 'general' || c.type === 'class',
    ),
  ]);

  // Power ids already granted from any other source on the draft — an
  // origin choice-group option ({tag:'power', op:'grant', power_id}) or a
  // chosen god power — so the bonus Poder Geral list doesn't offer a power
  // the character already has. Classes/races grant no powers of their own
  // yet, so those aren't sources here.
  private readonly alreadyPickedPowerIds = computed<Set<number>>(() => {
    const ids = new Set<number>();

    const origin = this.staticRegistry.origins.find((o) => o.id === this.draft.originId());
    const originGroups = origin?.grants ?? [];
    const originChoices = this.draft.originChoices();
    originGroups.forEach((group, gi) => {
      (originChoices[gi] ?? []).forEach((optionIndex) => {
        const option = group.options[optionIndex];
        if (option?.tag === 'power' && option.op === 'grant' && option.power_id) {
          ids.add(option.power_id);
        }
      });
    });

    this.draft.godPowerIds().forEach((id) => ids.add(id));

    return ids;
  });

  protected readonly generalPowerItems = computed(() => {
    const alreadyPicked = this.alreadyPickedPowerIds();
    return this.staticRegistry.powers.filter(
      (p) => p.type === 'general' && !alreadyPicked.has(p.id),
    );
  });

  protected get draftGeneralComplicationId() {
    return this.draft.generalComplicationId;
  }

  protected get draftGeneralComplicationPowerId() {
    return this.draft.generalComplicationPowerId;
  }

  protected get draftAge() {
    return this.draft.age;
  }

  protected readonly ageBracketItems = AGE_BRACKETS;

  protected get draftAgeBracket() {
    return this.draft.ageBracket;
  }

  protected ageBracketMods = (bracket: AgeBracketItem): SecondarySegment[] => {
    const stats = Object.keys(ATTRIBUTE_LABELS)
      .map((key) => ({ label: ATTRIBUTE_LABELS[key], value: (bracket as any)[key] as number ?? 0 }))
      .filter(({ value }) => value !== 0);

    return stats.flatMap(({ label, value }, index) => {
      const segments: SecondarySegment[] = [
        { text: `${label} ` },
        {
          text: `${value > 0 ? '+' : ''}${value}`,
          color: value > 0 ? 'var(--color-tormenta-green)' : 'var(--color-tormenta-red)',
        },
      ];

      if (index < stats.length - 1) {
        // Two underscores colored to match the dropdown row's own
        // background — an invisible gap, since real spaces collapse.
        segments.push({ text: '__', color: 'var(--color-light-black)' });
      }

      return segments;
    });
  };

  protected ageBracketExtras = (bracket: AgeBracketItem): string[] => bracket.extraPowers ?? [];

  // Adulto's mandatory picks — no "Nenhuma," both are required whenever
  // ageBracket is 'adulto' (see canContinue). Reuses generalPowerItems
  // (already excludes origin/god picks) rather than duplicating that pool.
  protected readonly adultoPowerItems = this.generalPowerItems;

  protected readonly adultoAgeComplicationItems = computed(() =>
    this.staticRegistry.complications.filter((c) => c.type === 'age'),
  );

  protected get draftAdultoPowerId() {
    return this.draft.adultoPowerId;
  }

  protected get draftAdultoAgeComplicationId() {
    return this.draft.adultoAgeComplicationId;
  }

  // Label for an age-bracket bonus class dropdown, matching step 3's
  // "Nível N" convention — offset is 1-based position within that
  // bracket's own extra-class array, so the actual level shown is the
  // character's base level plus however many bonus levels come before it.
  protected extraLevelLabel(offset: number): string {
    return `Nível ${(this.draft.level() ?? 0) + offset}`;
  }

  // Maduro's mandatory picks — a class for the extra level (separate from
  // step 3's classIds, sized to draft.level() not level+1), and two
  // age-typed Complicação picks that exclude each other so the same one
  // can't be picked twice.
  protected get maduroClasses() {
    return this.staticRegistry.classes;
  }

  protected get draftMaduroClassId() {
    return this.draft.maduroClassId;
  }

  protected maduroAgeComplicationItemsAt(index: number) {
    const other = this.draft.maduroAgeComplicationIds()[index === 0 ? 1 : 0];
    return this.staticRegistry.complications.filter((c) => c.type === 'age' && c.id !== other);
  }

  protected maduroAgeComplicationIdAt(index: number): number | null {
    return this.draft.maduroAgeComplicationIds()[index] ?? null;
  }

  protected setMaduroAgeComplicationIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.maduroAgeComplicationIds()];
    current[index] = value as number | null;
    this.draft.maduroAgeComplicationIds.set(current);
  }

  // Velho's mandatory picks — same reasoning as Maduro's, just two class
  // picks (independent, not mutually exclusive — commonly the same class
  // twice) and three age-typed Complicação picks (mutually exclusive, so
  // filtering checks every *other* index rather than just one).
  protected get velhoClasses() {
    return this.staticRegistry.classes;
  }

  protected velhoClassIdAt(index: number): number | null {
    return this.draft.velhoClassIds()[index] ?? null;
  }

  protected setVelhoClassIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.velhoClassIds()];
    current[index] = value as number | null;
    this.draft.velhoClassIds.set(current);
  }

  protected velhoAgeComplicationItemsAt(index: number) {
    const others = this.draft.velhoAgeComplicationIds().filter((_, i) => i !== index);
    return this.staticRegistry.complications.filter(
      (c) => c.type === 'age' && !others.includes(c.id),
    );
  }

  protected velhoAgeComplicationIdAt(index: number): number | null {
    return this.draft.velhoAgeComplicationIds()[index] ?? null;
  }

  protected setVelhoAgeComplicationIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.velhoAgeComplicationIds()];
    current[index] = value as number | null;
    this.draft.velhoAgeComplicationIds.set(current);
  }

  // Ancião's mandatory picks — same reasoning as Velho's, just three class
  // picks and four age-typed Complicação picks.
  protected get anciaoClasses() {
    return this.staticRegistry.classes;
  }

  protected anciaoClassIdAt(index: number): number | null {
    return this.draft.anciaoClassIds()[index] ?? null;
  }

  protected setAnciaoClassIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.anciaoClassIds()];
    current[index] = value as number | null;
    this.draft.anciaoClassIds.set(current);
  }

  protected anciaoAgeComplicationItemsAt(index: number) {
    const others = this.draft.anciaoAgeComplicationIds().filter((_, i) => i !== index);
    return this.staticRegistry.complications.filter(
      (c) => c.type === 'age' && !others.includes(c.id),
    );
  }

  protected anciaoAgeComplicationIdAt(index: number): number | null {
    return this.draft.anciaoAgeComplicationIds()[index] ?? null;
  }

  protected setAnciaoAgeComplicationIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.anciaoAgeComplicationIds()];
    current[index] = value as number | null;
    this.draft.anciaoAgeComplicationIds.set(current);
  }

  protected readonly generalComplicationPowerDisabled = computed(
    () => this.draft.generalComplicationId() === null,
  );

  // Origem em Construção (Adolescente): the origin's Perícias e Poderes
  // group — same index-1 position as step 4's otherGroups()[0]/groups()[1].
  private readonly originSkillPowerGroup = computed(() => {
    const origin = this.staticRegistry.origins.find((o) => o.id === this.draft.originId());
    return origin?.grants?.[1] ?? null;
  });

  // 'origin' when that group actually grants 2+ (something to unmark
  // there); 'class' when it only grants 1 (per Origem em Construção's own
  // fallback — remove a class-trained skill instead); null when Adolescente
  // isn't the picked bracket, or there's nothing to resolve yet.
  protected readonly adolescenteCase = computed<'origin' | 'class' | null>(() => {
    if (this.draft.ageBracket() !== 'adolescente') {
      return null;
    }
    const group = this.originSkillPowerGroup();
    if (!group) {
      return null;
    }
    return group.picks >= 2 ? 'origin' : 'class';
  });

  private optionLabel(option: GrantOption): string {
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

  // Skill ids the starting class's own skill groups can grant at all —
  // used to guess which of the character's currently-picked skills came
  // from the class (we don't track exact provenance per skill, and that
  // gap is fine here — self-reported precision elsewhere already covers
  // it).
  private readonly classProvidedSkillIds = computed<Set<number>>(() => {
    const classId = this.draft.classIds()[0] ?? null;
    const cls = this.staticRegistry.classes.find((c) => c.id === classId);
    const ids = new Set<number>();
    (cls?.skills ?? []).forEach((group) => group.options.forEach((id) => ids.add(id)));
    return ids;
  });

  // The checkbox list for whichever case applies — {id, label} either way,
  // so the template/toggle logic stays uniform regardless of what the id
  // actually means underneath (origin option index vs skill id).
  protected readonly adolescentePickItems = computed<{ id: number; label: string }[]>(() => {
    const kase = this.adolescenteCase();

    if (kase === 'origin') {
      const group = this.originSkillPowerGroup();
      const selected = this.draft.originChoices()[1] ?? [];
      if (!group) {
        return [];
      }
      return selected.map((optionIndex) => ({
        id: optionIndex,
        label: this.optionLabel(group.options[optionIndex]),
      }));
    }

    if (kase === 'class') {
      const providable = this.classProvidedSkillIds();
      const picked = new Set<number>();
      this.draft.classSkillChoices().forEach((ids) => ids.forEach((id) => picked.add(id)));
      return [...picked]
        .filter((id) => providable.has(id))
        .map((id) => ({
          id,
          label: this.staticRegistry.skills.find((s) => s.id === id)?.name ?? 'Perícia desconhecida',
        }));
    }

    return [];
  });

  // Flipped from the earlier "start all checked, uncheck 1" design — all
  // start unchecked, checking one marks it as the pick to lose. Same
  // picks=1 cap pattern as everywhere else (step 4/5/6): checked = in the
  // override, capped = a different one is already picked.
  protected isAdolescentePickChecked(id: number): boolean {
    return this.draft.adolescenteOverride().includes(id);
  }

  protected isAdolescentePickCapped(): boolean {
    return this.draft.adolescenteOverride().length >= 1;
  }

  protected toggleAdolescentePick(id: number, checked: boolean): void {
    if (checked) {
      if (!this.isAdolescentePickCapped()) {
        this.draft.adolescenteOverride.set([id]);
      }
    } else {
      this.draft.adolescenteOverride.set(this.draft.adolescenteOverride().filter((x) => x !== id));
    }
  }

  // "Desmarque 1" only actually blocks continuing once it's showing
  // something to unmark — selected (available - 1) covers both cases
  // (origin picks or class skills) the same way, since it's just "exactly
  // one unchecked."
  protected readonly canContinue = computed(() => {
    const pickItems = this.adolescentePickItems();
    const adolescenteSatisfied = pickItems.length === 0 || this.draft.adolescenteOverride().length === 1;

    const adultoSatisfied =
      this.draft.ageBracket() !== 'adulto' ||
      (this.draft.adultoPowerId() !== null && this.draft.adultoAgeComplicationId() !== null);

    const maduroSatisfied =
      this.draft.ageBracket() !== 'maduro' ||
      (this.draft.maduroClassId() !== null &&
        this.draft.maduroAgeComplicationIds().every((id) => id !== null));

    const velhoSatisfied =
      this.draft.ageBracket() !== 'velho' ||
      (this.draft.velhoClassIds().every((id) => id !== null) &&
        this.draft.velhoAgeComplicationIds().every((id) => id !== null));

    const anciaoSatisfied =
      this.draft.ageBracket() !== 'anciao' ||
      (this.draft.anciaoClassIds().every((id) => id !== null) &&
        this.draft.anciaoAgeComplicationIds().every((id) => id !== null));

    return (
      this.draft.age() !== null &&
      adolescenteSatisfied &&
      adultoSatisfied &&
      maduroSatisfied &&
      velhoSatisfied &&
      anciaoSatisfied
    );
  });

  back(): void {
    this.router.navigate(['/character-creation-step-6']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-step-8']);
  }
}
