export const SANGUE_MAGICO_POWER_ID = 17120;

export function sangueMagicoOptions(con: number): { id: number; name: string }[] {
  return Array.from({ length: Math.max(con, 0) }, (_, i) => ({ id: i + 1, name: String(i + 1) }));
}
