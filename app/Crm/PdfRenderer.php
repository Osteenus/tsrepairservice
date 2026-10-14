<?php

namespace App\Crm;

use App\Models\Document;
use Spatie\Browsershot\Browsershot;
use Throwable;

/**
 * Renders estimate and invoice PDFs with headless Chromium (the templates use CSS grid/flex).
 */
class PdfRenderer
{
    public function html(Document $document): string
    {
        $view = $document->isEstimate() ? 'crm.pdf.estimate' : 'crm.pdf.invoice';

        return view($view, DocumentPresenter::pdfContext($document))->render();
    }

    /**
     * @throws PdfRenderingFailed
     */
    public function pdf(Document $document): string
    {
        $html = $this->html($document);

        try {
            $browser = Browsershot::html($html)
                ->format('Letter')
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->timeout(60);

            $config = config('crm.pdf');
            if ($config['node_binary']) {
                $browser->setNodeBinary($config['node_binary']);
            }
            if ($config['npm_binary']) {
                $browser->setNpmBinary($config['npm_binary']);
            }
            if ($config['chrome_path']) {
                $browser->setChromePath($config['chrome_path']);
            }
            if ($config['no_sandbox']) {
                $browser->noSandbox();
            }

            return $browser->setNodeModulePath(base_path('node_modules'))->pdf();
        } catch (Throwable $exception) {
            report($exception);

            throw new PdfRenderingFailed('The PDF could not be created. Try again in a minute; if it keeps failing, check the PDF setup in docs/crm/deploy.md.', previous: $exception);
        }
    }

    public function filename(Document $document): string
    {
        return ($document->isEstimate() ? 'Estimate-' : 'Invoice-').($document->number ?? 'draft-'.$document->id).'.pdf';
    }
}
