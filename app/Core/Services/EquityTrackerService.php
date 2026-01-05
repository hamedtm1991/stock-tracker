<?php

namespace App\Core\Services;

use App\Core\Repositories\Contracts\EquityRepositoryInterface;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EquityTrackerService
{
    /**
     * @param EquityRepositoryInterface $repository
     */
    public function __construct(
        private EquityRepositoryInterface $repository
    ) {}

    /**
     * @param Collection $rows
     * @return void
     */
    public function importRows(Collection $rows): void
    {
        $data = $rows
            ->map(fn ($row) => $this->mapRow($row))
            ->filter()
            ->values()
            ->all();

        if ($data) {
            $this->repository->bulkUpsert($data);
        }
    }

    /**
     * @param Collection $row
     * @return array|null
     */
    private function mapRow(Collection $row): ?array
    {
        $date = $this->normalizeDate($row->get('date'));

        if (!$date || !$row->has('stock_price')) {
            return null;
        }

        return [
            'date'       => $date,
            'price'      => (float) $row->get('stock_price'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * @param mixed $value
     * @return string|null
     */
    private function normalizeDate(mixed $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return is_numeric($value)
                ? Carbon::instance(
                    ExcelDate::excelToDateTimeObject((float) $value)
                )->toDateString()
                : Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
}
