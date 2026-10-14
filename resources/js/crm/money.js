// Mirrors App\Crm\DocumentTotals so the builder shows the same totals the server stores.
export const toCents = value => {
    const clean = String(value ?? '').replace(/[^\d.]/g, '');
    return clean === '' ? 0 : Math.round(parseFloat(clean) * 100);
};

export const lineAmount = item => {
    if (item.is_warranty) return 0;
    const hundredths = Math.round(parseFloat(item.qty || 0) * 100);
    return Math.floor((hundredths * toCents(item.unit) + 50) / 100);
};

export const totals = (items, discount, taxRate = null) => {
    let labor = 0;
    let parts = 0;
    for (const item of items) {
        item.kind === 'part' ? (parts += lineAmount(item)) : (labor += lineAmount(item));
    }
    const tax = taxRate ? Math.floor(parts * taxRate + 0.5) : 0;
    const discountCents = Math.min(Math.max(toCents(discount), 0), labor + parts + tax);
    return { labor, parts, tax, discount: discountCents, total: labor + parts + tax - discountCents };
};

export const money = cents => (cents < 0 ? '-' : '') + '$' + (Math.abs(cents) / 100).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
