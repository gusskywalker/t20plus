const ATTRIBUTE_PREFIX = 'attribute_';

/** The attribute code (str/dex/con/int/knw/car) named by a tag value like 'attribute_knw' — the value form used where an effect says WHICH attribute, as opposed to a bare 'knw', which is the attribute's current number. */
export function attributeCode(value: string | number): string {
  const text = String(value);
  return text.startsWith(ATTRIBUTE_PREFIX) ? text.slice(ATTRIBUTE_PREFIX.length) : text;
}
