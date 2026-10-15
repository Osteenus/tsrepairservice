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
            return $this->configure(Browsershot::html($html))
                ->format('Letter')
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->pdf();
        } catch (Throwable $exception) {
            report($exception);

            throw new PdfRenderingFailed('The PDF could not be created. Try again in a minute; if it keeps failing, check the PDF setup in docs/crm/deploy.md.', previous: $exception);
        }
    }

    /**
     * Node, Chrome and process settings shared by PDF rendering and `crm:pdf-check`.
     */
    public function configure(Browsershot $browser): Browsershot
    {
        $config = config('crm.pdf');

        if ($config['node_binary']) {
            $browser->setNodeBinary($config['node_binary']);
        }
        if ($config['npm_binary']) {
            $browser->setNpmBinary($config['npm_binary']);
        }
        if ($chrome = $this->chromePath()) {
            $browser->setChromePath($chrome);
        }
        if ($config['no_sandbox']) {
            $browser->noSandbox();
        }

        // Chrome writes its profile and crash database under HOME. The PHP user's home (/var/www on
        // the VPS) is read-only, which makes chrome_crashpad_handler abort, so give it a writable one.
        $home = storage_path('app/chrome');
        if (! is_dir($home)) {
            @mkdir($home, 0700, true);
        }

        return $browser
            ->setEnvironmentOptions(['HOME' => $home, 'XDG_CONFIG_HOME' => $home, 'XDG_CACHE_HOME' => $home])
            ->addChromiumArguments(['disable-crash-reporter', 'no-first-run'])
            ->setNodeModulePath(base_path('node_modules'))
            ->timeout(60);
    }

    /**
     * The configured browser, else the newest Chrome that `npm ci` put in .cache/puppeteer.
     * Found by path because PHP-FPM's working directory is not the project, so Puppeteer's config is not read there.
     */
    public function chromePath(): ?string
    {
        if ($path = config('crm.pdf.chrome_path')) {
            return $path;
        }

        $candidates = array_merge(
            glob(base_path('.cache/puppeteer/chrome/*/chrome-linux64/chrome')) ?: [],
            glob(base_path('.cache/puppeteer/chrome/*/chrome-mac*/Google Chrome for Testing.app/Contents/MacOS/Google Chrome for Testing')) ?: [],
        );
        natsort($candidates);

        return end($candidates) ?: null;
    }

    public function filename(Document $document): string
    {
        return ($document->isEstimate() ? 'Estimate-' : 'Invoice-').($document->number ?? 'draft-'.$document->id).'.pdf';
    }
}
