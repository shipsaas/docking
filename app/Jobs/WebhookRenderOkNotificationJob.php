<?php

namespace App\Jobs;

use App\Http\Resources\DocumentFileResource;
use App\Models\DocumentFile;
use App\Services\WebhookNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @method static static dispatch(string $webhookUrl, DocumentFile $file)
 */
class WebhookRenderOkNotificationJob implements ShouldQueue
{
    use SerializesModels;
    use Dispatchable;
    use Queueable;

    public function __construct(
        public string $webhookUrl,
        public DocumentFile $documentFile
    ) {
    }

    public function handle(WebhookNotificationService $service): void
    {
        $service->notify($this->webhookUrl, [
            'file' => DocumentFileResource::make($this->documentFile)->toArray(app('request')),
        ]);
    }
}
