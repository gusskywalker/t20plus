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
    id: 'crianca',
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
  { id: 'adulto', name: 'Adulto' },
  { id: 'maduro', name: 'Maduro' },
  { id: 'velho', name: 'Velho' },
  { id: 'anciao', name: 'Ancião' },
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
    return this.draft.age() !== null && adolescenteSatisfied;
  });

  back(): void {
    this.router.navigate(['/character-creation-step-6']);
  }

  continue(): void {
    // Step 8 doesn't exist yet.
  }
}
