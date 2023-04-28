<?php

namespace App\Jobs;

use App\Events\PdfRendered;
use App\Models\DocumentTemplate;
use App\Services\PdfRenderManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

/**
 * @method static static dispatch(DocumentTemplate $template, array $variables = [], array $metadata = [])
 */
class RenderJob implements ShouldQueue
{
    use SerializesModels;
    use Dispatchable;
    use Queueable;

    public function __construct(
        public DocumentTemplate $template,
        public array $variables = [],
        public array $metadata = []
    ) {
    }

    public function handle(PdfRenderManager $manager): void
    {
        $renderResult = $manager->render(
            $this->template,
            $this->variables,
            $this->metadata,
        );

        $webhookUrl = $this->metadata['webhook_url'];

        if ($renderResult->isError()) {
            $errorResult = $renderResult->getErrorResult();

            WebhookRenderErrorNotificationJob::dispatch(
                $webhookUrl,
                $errorResult->errorCode
            );

            return;
        }

        $okResult = $renderResult->getOkResult();

        Event::dispatch(new PdfRendered($okResult->file));

        WebhookRenderOkNotificationJob::dispatch(
            $webhookUrl,
            $okResult->file
        );
    }
}
