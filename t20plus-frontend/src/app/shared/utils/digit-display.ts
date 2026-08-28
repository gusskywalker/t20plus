/**
 * Tormenta font renders 'O' more attractively than the digit '0', so
 * numeric displays swap it in purely for looks — the underlying value
 * stays numeric everywhere else.
 */
export function formatDigits(value: number): string {
  return String(value).replace(/0/g, 'O');
}
