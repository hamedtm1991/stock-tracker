<?php

namespace App\Core\Repositories;

use App\Core\Repositories\Contracts\EquityRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EloquentEquityRepository implements EquityRepositoryInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function bulkUpsert(array $data): void
    {
        DB::table('stock_prices')->upsert(
            $data,
            ['date'],
            ['price', 'updated_at']
        );
    }

    /**
     * @return object|null
     */
    public function getLatest(): ?object
    {
        return DB::table('stock_prices')
            ->orderBy('date', 'desc')
            ->first();
    }

    /**
     * @return object|null
     */
    public function getOldest(): ?object
    {
        return DB::table('stock_prices')
            ->orderBy('date', 'asc')
            ->first();
    }

    /**
     * @param string $targetDate
     * @return object|null
     */
    private function getClosestToDate(string $targetDate): ?object
    {
        $rows = DB::table('stock_prices')->get();

        $closest = $rows->sortBy(function($row) use ($targetDate) {
            return abs(Carbon::parse($row->date)->diffInDays(Carbon::parse($targetDate)));
        })->first();

        return $closest;
    }

    /**
     * @param int $days
     * @return object|null
     * @throws Exception
     */
    public function getDaysAgo(int $days): ?object
    {
        $latest = $this->getLatest();
        if (!$latest) return null;

        $targetDate = Carbon::parse($latest->date)->subDays($days)->toDateString();
        $closest = $this->getClosestToDate($targetDate);

        if (!$closest || Carbon::parse($closest->date)->gt(Carbon::parse($latest->date))) {
            throw new Exception("Not enough data to compute {$days}-day change");
        }

        return $closest;
    }

    /**
     * @param int $months
     * @return object|null
     * @throws Exception
     */
    public function getMonthsAgo(int $months): ?object
    {
        $latest = $this->getLatest();
        if (!$latest) return null;

        $targetDate = Carbon::parse($latest->date)->subMonths($months)->toDateString();
        $closest = $this->getClosestToDate($targetDate);

        if (!$closest || Carbon::parse($closest->date)->gt(Carbon::parse($latest->date))) {
            throw new Exception("Not enough data to compute {$months}-month change");
        }

        return $closest;
    }

    /**
     * @param int $years
     * @return object|null
     * @throws Exception
     */
    public function getYearsAgo(int $years): ?object
    {
        $latest = $this->getLatest();
        if (!$latest) return null;

        $targetDate = Carbon::parse($latest->date)->subYears($years)->toDateString();
        $closest = $this->getClosestToDate($targetDate);

        if (!$closest || Carbon::parse($closest->date)->gt(Carbon::parse($latest->date))) {
            throw new Exception("Not enough data to compute {$years}-year change");
        }

        return $closest;
    }

    /**
     * @return object|null
     * @throws Exception
     */
    public function getStartOfYear(): ?object
    {
        $latest = $this->getLatest();
        if (!$latest) return null;

        $yearStart = Carbon::parse($latest->date)->startOfYear()->toDateString();
        $closest = $this->getClosestToDate($yearStart);

        if (!$closest || Carbon::parse($closest->date)->gt(Carbon::parse($latest->date))) {
            throw new Exception("Not enough data to compute YTD change");
        }

        return $closest;
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     */
    public function getBetweenDates(string $start, string $end): array
    {
        $startPrice = DB::table('stock_prices')
            ->whereDate('date', '<=', $start)
            ->orderBy('date', 'desc')
            ->first();

        $endPrice = DB::table('stock_prices')
            ->whereDate('date', '<=', $end)
            ->orderBy('date', 'desc')
            ->first();

        return [$startPrice, $endPrice];
    }
}
