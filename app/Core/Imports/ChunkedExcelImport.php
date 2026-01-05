<?php

namespace App\Core\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChunkedExcelImport implements ToCollection, WithChunkReading, WithHeadingRow
{
    protected $callback;
    protected int $chunkSize;

    public function __construct(callable $callback, int $chunkSize = 500)
    {
        $this->callback  = $callback;
        $this->chunkSize = $chunkSize;
    }

    /**
     * Runs once PER CHUNK (not whole file)
     */
    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        call_user_func($this->callback, $rows);
    }

    /**
     * Chunk size for Excel reader
     */
    public function chunkSize(): int
    {
        return $this->chunkSize;
    }
}
