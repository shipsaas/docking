<?php

namespace App\Jobs;

use App\Models\DocumentFile;
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
}
