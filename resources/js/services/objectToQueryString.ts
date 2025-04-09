/**
 * Converts a nested object into a URL query string with bracket notation
 * Example: {filter: {get_to_ping: 1}} becomes "filter[get_to_ping]=1"
 *
 * @param obj - The object to convert
 * @param prefix - Optional prefix for nested properties
 * @returns The formatted query string
 */
export function objectToQueryString(obj: Record<string, any>, prefix: string = ''): string {
    const str: string[] = [];

    for (const p in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, p)) {
            const k = prefix ? `${prefix}[${p}]` : p;
            const v = obj[p];

            if (v !== null && typeof v === 'object' && !Array.isArray(v)) {
                str.push(objectToQueryString(v, k));
            } else if (Array.isArray(v)) {
                // Handle arrays
                v.forEach((item, index) => {
                    if (item !== null && typeof item === 'object') {
                        str.push(objectToQueryString(item, `${k}[${index}]`));
                    } else {
                        str.push(`${encodeURIComponent(`${k}[${index}]`)}=${encodeURIComponent(item)}`);
                    }
                });
            } else {
                str.push(`${encodeURIComponent(k)}=${encodeURIComponent(v)}`);
            }
        }
    }

    return str.join('&');
}
