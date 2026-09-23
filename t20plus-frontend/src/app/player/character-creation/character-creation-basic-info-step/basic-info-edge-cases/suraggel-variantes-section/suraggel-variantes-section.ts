import { Component, computed, inject, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

const NENHUMA = { id: null, name: 'Nenhuma' };

// Suraggel Variantes — an optional pick among 'specific' powers, each
// removing Luz Sagrada (id 16065) or Sombras Profanas (id 16066) via its
// own removes_power effects. Nenhuma keeps whichever of the two the
// character's own Suraggel race already grants. Hardcoded here since
// source: 'specific' is deliberately invisible to every generic
// power-picker dropdown. Filled in as each variant gets seeded.
const SURAGGEL_VARIANT_POWER_IDS: number[] = [16246, 16247, 16248, 16249, 16250, 16251, 16252, 16253, 16254, 16255, 16256, 16257, 16258, 16259, 16260, 16261, 16264];

@Component({
  selector: 'app-suraggel-variantes-section',
  imports: [SearchableDropdown],
  templateUrl: './suraggel-variantes-section.html',
  styleUrl: './suraggel-variantes-section.scss',
})
export class SuraggelVariantesSection {
  private staticRegistry = inject(StaticRegistry);

  variantePowerId = model<number | null>(null);

  protected readonly variantePowers = computed(() => [
    NENHUMA,
    ...SURAGGEL_VARIANT_POWER_IDS.map((id) => this.staticRegistry.powers.find((power) => power.id === id)).filter((power) => power !== undefined),
  ]);
}
