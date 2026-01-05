<?php

namespace App\Core\Traits;

use App\Core\Imports\ChunkedExcelImport;
use Maatwebsite\Excel\Facades\Excel;

trait ExcelImporterTrait
{
    /**
     * Read Excel file in true chunks
     */
    public function readExcelInChunks(
        string $relativePath,
        callable $callback,
        int $chunkSize = 500
    ): void {
        $fullPath = storage_path('app/private/' . $relativePath);

        if (!file_exists($fullPath)) {
            throw new \RuntimeException("Excel file not found: {$fullPath}");
        }

        Excel::import(
            new ChunkedExcelImport($callback, $chunkSize),
            $fullPath
        );
    }
}
