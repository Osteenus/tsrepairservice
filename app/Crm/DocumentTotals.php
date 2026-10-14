<?php

namespace App\Crm;

/**
 * Document money math in integer cents.
 *
 * Service call and labor lines form "labor"; parts form "parts". Warranty lines are free.
 * Sales tax, when charged, applies to parts only (California does not tax repair labor).
 */
final class DocumentTotals
{
    public function __construct(
        public readonly int $labor,
        public readonly int $parts,
        public readonly int $tax,
        public readonly int $discount,
        public readonly int $total,
    ) {}

    /**
     * @param  iterable<array{kind: string, qty: int|float|string, unit_cents: int, is_warranty?: bool}>  $items
     */
    public static function calculate(iterable $items, int $discountCents = 0, ?float $taxRate = null): self
    {
        $labor = 0;
        $parts = 0;

        foreach ($items as $item) {
            $amount = self::lineAmount($item['qty'], $item['unit_cents'], (bool) ($item['is_warranty'] ?? false));
            $item['kind'] === 'part' ? $parts += $amount : $labor += $amount;
        }

        $tax = $taxRate ? self::roundHalfUp($parts * $taxRate) : 0;
        $discount = min(max($discountCents, 0), $labor + $parts + $tax);

        return new self($labor, $parts, $tax, $discount, $labor + $parts + $tax - $discount);
    }

    public static function lineAmount(int|float|string $qty, int $unitCents, bool $isWarranty = false): int
    {
        if ($isWarranty) {
            return 0;
        }

        // qty has two decimals; multiply in hundredths to stay in integers.
        $hundredths = (int) round(((float) $qty) * 100);

        return intdiv($hundredths * $unitCents + 50, 100);
    }

    private static function roundHalfUp(float $cents): int
    {
        return (int) floor($cents + 0.5 + 1e-9);
    }
}
