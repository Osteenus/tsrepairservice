<?php

namespace App\Console\Commands;

use App\Crm\PdfRenderer;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Throwable;

class CheckCrmPdf extends Command
{
    protected $signature = 'crm:pdf-check';

    protected $description = 'Render a test PDF with headless Chromium to verify the CRM PDF setup';

    public function handle(PdfRenderer $renderer): int
    {
        $config = config('crm.pdf');
        $this->line('Node:   '.($config['node_binary'] ?: 'node (from PATH)'));
        $this->line('Chrome: '.($renderer->chromePath() ?: 'Puppeteer default'));

        try {
            $pdf = $renderer->configure(Browsershot::html('<html><body style="font-family:sans-serif"><h1>TS Repair CRM PDF check</h1></body></html>'))
                ->format('Letter')
                ->pdf();
        } catch (Throwable $exception) {
            $this->error('PDF rendering failed: '.$exception->getMessage());

            return self::FAILURE;
        }

        if (! str_starts_with($pdf, '%PDF')) {
            $this->error('Chromium returned something that is not a PDF.');

            return self::FAILURE;
        }

        $this->info('OK: rendered a '.number_format(strlen($pdf)).' byte PDF.');

        return self::SUCCESS;
    }
}
