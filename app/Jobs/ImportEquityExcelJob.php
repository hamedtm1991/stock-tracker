<?php

namespace App\Jobs;

use App\Core\Services\EquityTrackerService;
use App\Core\Traits\ExcelImporterTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportEquityExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use ExcelImporterTrait;

    public string $filePath;
    public int $chunkSize;

    public function __construct(string $filePath, int $chunkSize = 500)
    {
        $this->filePath = $filePath;
        $this->chunkSize = $chunkSize;
    }

    public function handle(EquityTrackerService $service): void
    {
        $this->readExcelInChunks(
            $this->filePath,
            function ($rows) use ($service) {
                $service->importRows($rows);
            },
            $this->chunkSize
        );
    }
}
