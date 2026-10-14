<?php

namespace App\Crm;

use App\Models\DocumentCounter;
use Illuminate\Support\Facades\DB;

/**
 * Gapless yearly numbers: estimates TS-E-2026-0001, invoices TS-2026-0001.
 */
class DocumentNumbers
{
    public function next(string $type, int $year): string
    {
        return DB::transaction(function () use ($type, $year) {
            DocumentCounter::query()->insertOrIgnore(['type' => $type, 'year' => $year, 'last_number' => 0]);

            $counter = DocumentCounter::where(['type' => $type, 'year' => $year])->lockForUpdate()->firstOrFail();
            $counter->increment('last_number');

            $prefix = $type === 'estimate' ? 'TS-E' : 'TS';

            return sprintf('%s-%d-%04d', $prefix, $year, $counter->last_number);
        });
    }
}
