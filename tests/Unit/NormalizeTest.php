<?php

namespace Tests\Unit;

use App\Support\Normalize;
use PHPUnit\Framework\TestCase;

class NormalizeTest extends TestCase
{
    public function test_us_phone_numbers_are_formatted(): void
    {
        foreach (['8055550100', '805-555-0100', '805.555.0100', '+1 805 555 0100', '1 (805) 555 0100', ' (805)5550100 '] as $input) {
            $this->assertSame('(805) 555-0100', Normalize::phone($input), $input);
        }
    }

    public function test_other_phone_numbers_are_kept_as_typed(): void
    {
        $this->assertSame('+44 20 7946 0958', Normalize::phone('+44 20 7946 0958'));
        $this->assertSame('805-555-0100 ext 12', Normalize::phone(' 805-555-0100 ext 12 '));
        $this->assertSame('555-0100', Normalize::phone('555-0100'));
        $this->assertNull(Normalize::phone('  '));
    }

    public function test_email_upper_and_text(): void
    {
        $this->assertSame('jane.doe@example.com', Normalize::email('  Jane.Doe@Example.COM '));
        $this->assertNull(Normalize::email(''));
        $this->assertSame('WTW4950XW2', Normalize::upper(' wtw4950xw2 '));
        $this->assertSame('123 Main St', Normalize::text("  123   Main\tSt "));
    }
}
