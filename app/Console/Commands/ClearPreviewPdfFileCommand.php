<?php

namespace App\Console\Commands;

use App\Models\DocumentFile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ClearPreviewPdfFileCommand extends Command
{
    protected $signature = 'docking:clear-preview-files {date?}';
    protected $description = '[DocKing] Clear preview files';

    public function handle(): void
    {
        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))
            : null;

        DocumentFile::query()
            ->when($date, fn ($q) => $q->whereBetween(
                'created_at',
                [
                    $date->format('Y-m-d 00:00:00'),
                    $date->format('Y-m-d 23:59:59'),
                ]
            ))
            ->where('is_preview_file', true)
            ->chunk(100, $this->deleteBulk(...));

        $this->info(sprintf(
            'Cleared all files %s',
            $date ? "on $date" : ''
        ));
    }

    /**
     * @param Collection<DocumentFile> $files
     */
    private function deleteBulk(Collection $files): void
    {
        rescue(fn () => Storage::delete($files->pluck('path')));

        DocumentFile::query()
            ->whereIn('uuid', $files->pluck('uuid'))
            ->delete();
    }
}
