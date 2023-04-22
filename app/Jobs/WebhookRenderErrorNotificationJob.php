<?php

namespace App\Jobs;

use App\Results\ErrorCodes\PdfRenderErrorCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @method static static dispatch(string $webhookUrl, PdfRenderErrorCode $errorCode)
 */
class WebhookRenderErrorNotificationJob implements ShouldQueue
{
    use SerializesModels;
    use Dispatchable;
    use Queueable;

    public function __construct(
        public string $webhookUrl,
        public PdfRenderErrorCode $errorCode
    ) {
    }
}
