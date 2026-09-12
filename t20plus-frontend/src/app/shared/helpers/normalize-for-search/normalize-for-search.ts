// Lowercases and strips diacritics (NFD decompose, then drop every combining
// mark codepoint) so a search dropdown matches "be" against "Bênção" - call
// it on both the typed term and each item's label.
const COMBINING_MARKS_START = 0x0300;
const COMBINING_MARKS_END = 0x036f;

export function normalizeForSearch(value: string): string {
  const decomposed = value.toLowerCase().trim().normalize('NFD');
  let result = '';
  for (const char of decomposed) {
    const code = char.codePointAt(0) ?? 0;
    if (code < COMBINING_MARKS_START || code > COMBINING_MARKS_END) {
      result += char;
    }
  }
  return result;
}
