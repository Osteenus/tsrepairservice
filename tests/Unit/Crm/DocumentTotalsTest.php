<?php

namespace Tests\Unit\Crm;

use App\Crm\BusinessDays;
use App\Crm\DocumentTotals;
use App\Crm\Money;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class DocumentTotalsTest extends TestCase
{
    private function items(): array
    {
        return [
            ['kind' => 'service_call', 'qty' => 1, 'unit_cents' => 9500],
            ['kind' => 'labor', 'qty' => 1, 'unit_cents' => 12000],
            ['kind' => 'part', 'qty' => 1, 'unit_cents' => 5200],
        ];
    }

    public function test_sample_estimate_totals_match_the_reference(): void
    {
        $totals = DocumentTotals::calculate($this->items());

        $this->assertSame(21500, $totals->labor);
        $this->assertSame(5200, $totals->parts);
        $this->assertSame(0, $totals->tax);
        $this->assertSame(26700, $totals->total);
    }

    public function test_fractional_quantities_stay_in_whole_cents(): void
    {
        $this->assertSame(13350, DocumentTotals::lineAmount(1.5, 8900));
        $this->assertSame(1, DocumentTotals::lineAmount(0.33, 3)); // 0.99¢ rounds half-up to 1¢
        $this->assertSame(30, DocumentTotals::lineAmount('3', 10));
    }

    public function test_warranty_lines_are_free(): void
    {
        $items = [...$this->items(), ['kind' => 'part', 'qty' => 2, 'unit_cents' => 4000, 'is_warranty' => true]];

        $this->assertSame(5200, DocumentTotals::calculate($items)->parts);
    }

    public function test_discount_reduces_the_total_but_never_below_zero(): void
    {
        $this->assertSame(24700, DocumentTotals::calculate($this->items(), 2000)->total);
        $totals = DocumentTotals::calculate($this->items(), 999999);
        $this->assertSame(26700, $totals->discount);
        $this->assertSame(0, $totals->total);
    }

    public function test_tax_applies_to_parts_only_and_rounds_half_up(): void
    {
        $totals = DocumentTotals::calculate($this->items(), 0, 0.0725);

        // 7.25% of $52.00 = $3.77; labor is not taxed.
        $this->assertSame(377, $totals->tax);
        $this->assertSame(27077, $totals->total);
        $this->assertSame(73, DocumentTotals::calculate([['kind' => 'part', 'qty' => 1, 'unit_cents' => 1000]], 0, 0.0725)->tax); // 72.5 → 73
    }

    public function test_money_parsing_and_formatting(): void
    {
        $this->assertSame(123450, Money::parse('$1,234.50'));
        $this->assertSame(9500, Money::parse('95'));
        $this->assertSame(1999, Money::parse(19.99));
        $this->assertSame(0, Money::parse(''));
        $this->assertSame('$1,234.50', Money::format(123450));
        $this->assertSame('-$5.00', Money::format(-500));
    }

    public function test_estimates_are_valid_for_five_business_days(): void
    {
        // Wednesday Oct 14, 2026 → Wednesday Oct 21 (weekend skipped).
        $this->assertSame('2026-10-21', BusinessDays::add(CarbonImmutable::parse('2026-10-14'), 5)->toDateString());
        // Friday → next Friday.
        $this->assertSame('2026-10-23', BusinessDays::add(CarbonImmutable::parse('2026-10-16'), 5)->toDateString());
    }
}
