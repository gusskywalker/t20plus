import { Component, ElementRef, HostListener, computed, inject, input, model, signal } from '@angular/core';

let nextId = 0;

export interface SecondarySegment {
  text: string;
  color?: string;
}

@Component({
  selector: 'app-searchable-dropdown',
  imports: [],
  templateUrl: './searchable-dropdown.html',
  styleUrl: './searchable-dropdown.scss',
})
export class SearchableDropdown {
  private readonly elementRef = inject(ElementRef);

  protected readonly inputId = `searchable-dropdown-${nextId++}`;

  labelText = input('');
  items = input<any[]>([]);
  displayField = input('name');
  secondaryFn = input<((item: any) => SecondarySegment[]) | null>(null);
  detailFn = input<((item: any) => { left: string; right: string }) | null>(null);

  value = model<number | string | null>(null);

  protected readonly isOpen = signal(false);
  protected readonly searchTerm = signal('');

  protected readonly selectedLabel = computed(() => {
    const selected = this.items().find((item) => item.id === this.value());
    return selected ? selected[this.displayField()] : '';
  });

  protected readonly filteredItems = computed(() => {
    const term = this.searchTerm().toLowerCase().trim();
    if (!term) {
      return this.items();
    }
    return this.items().filter((item) =>
      String(item[this.displayField()]).toLowerCase().includes(term),
    );
  });

  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent): void {
    if (!this.elementRef.nativeElement.contains(event.target)) {
      this.isOpen.set(false);
    }
  }

  onFocus(): void {
    this.searchTerm.set('');
    this.isOpen.set(true);
  }

  onInput(event: Event): void {
    this.searchTerm.set((event.target as HTMLInputElement).value);
    this.isOpen.set(true);
  }

  selectItem(item: any): void {
    this.value.set(item.id);
    this.searchTerm.set('');
    this.isOpen.set(false);
  }
}
