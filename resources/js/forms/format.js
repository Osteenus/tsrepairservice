// Input formatting shared by the public request form and the CRM.
// Formatting runs while typing (not while deleting, so backspace never fights the user)
// and fully on blur. The server normalizes the same fields again.

const digitsOf = value => String(value ?? '').replace(/\D/g, '');

/** US phone: "(805) 555-0100". International numbers and extensions are left as typed. */
export function formatPhone(value, partial = false) {
    const raw = String(value ?? '');
    if (/^\s*\+(?!1\b)/.test(raw) || /[a-z#]/i.test(raw)) return raw.trim();

    let digits = digitsOf(raw);
    if (digits.length === 11 && digits.startsWith('1')) digits = digits.slice(1);
    if (digits.length > 10) return raw.trim();
    if (!partial && digits.length !== 10) return raw.trim();
    if (!digits) return '';
    if (digits.length < 4) return `(${digits}`;
    if (digits.length < 7) return `(${digits.slice(0, 3)}) ${digits.slice(3)}`;
    return `(${digits.slice(0, 3)}) ${digits.slice(3, 6)}-${digits.slice(6)}`;
}

export const formatters = {
    phone: { live: value => formatPhone(value, true), blur: value => formatPhone(value) },
    email: { live: value => value.replace(/\s/g, ''), blur: value => value.trim().toLowerCase() },
    upper: { live: value => value.toUpperCase(), blur: value => value.trim() },
    state: { live: value => value.replace(/[^a-z]/gi, '').slice(0, 2).toUpperCase(), blur: value => value },
    zip: { live: value => value.replace(/[^\d-]/g, '').slice(0, 10), blur: value => value },
    money: {
        live: value => value.replace(/[^\d.]/g, ''),
        blur: value => (value === '' || Number.isNaN(Number(value)) ? value : Number(value).toFixed(2)),
    },
    trim: { live: value => value, blur: value => value.trim().replace(/\s{2,}/g, ' ') },
};

// Keyboard and browser hints that go with each format.
const hints = {
    phone: { type: 'tel', inputmode: 'tel', autocomplete: 'tel' },
    email: { inputmode: 'email', autocapitalize: 'none', autocorrect: 'off', spellcheck: 'false' },
    upper: { autocapitalize: 'characters', autocorrect: 'off', spellcheck: 'false' },
    state: { autocapitalize: 'characters', autocorrect: 'off', maxlength: '2' },
    zip: { inputmode: 'numeric', autocomplete: 'postal-code' },
    money: { inputmode: 'decimal' },
    words: { autocapitalize: 'words' },
};

const setValue = (el, value) => {
    if (el.value === value) return;
    // Keep the caret after the same number of significant characters.
    const caret = el.selectionStart ?? el.value.length;
    const before = el.value.slice(0, caret).replace(/[^\da-z]/gi, '').length;
    el.value = value;
    let position = 0;
    for (let seen = 0; position < value.length && seen < before; position++) {
        if (/[\da-z]/i.test(value[position])) seen++;
    }
    try {
        el.setSelectionRange(position, position);
    } catch {
        // Some input types (email, number) do not support selection.
    }
    el.__formatting = true;
    el.dispatchEvent(new Event('input', { bubbles: true }));
    el.__formatting = false;
};

/**
 * v-format="'phone'" on an <input> with v-model. Kinds: phone, email, upper, state, zip, money, trim, words.
 */
export const vFormat = {
    mounted(el, { value: kind }) {
        for (const [name, hint] of Object.entries(hints[kind] ?? {})) {
            if (!el.hasAttribute(name)) el.setAttribute(name, hint);
        }
        const formatter = formatters[kind];
        if (!formatter) return;

        el.addEventListener('input', event => {
            if (el.__formatting || event.isComposing || event.inputType?.startsWith('delete')) return;
            setValue(el, formatter.live(el.value));
        });
        el.addEventListener('blur', () => setValue(el, formatter.blur(el.value)));
    },
};
