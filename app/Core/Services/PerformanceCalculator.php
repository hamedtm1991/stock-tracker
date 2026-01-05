<?php

namespace App\Core\Services;

use App\Core\Repositories\Contracts\EquityRepositoryInterface;
use Exception;

class PerformanceCalculator
{
    protected EquityRepositoryInterface $repo;

    public function __construct(EquityRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param string $period
     * @return array
     * @throws Exception
     */
    public function computeChange(string $period): array
    {
        $latest = $this->repo->getLatest();
        if (!$latest) {
            throw new Exception("No data found");
        }

        $old = match ($period) {
            '1D' => $this->repo->getDaysAgo(1),
            '2D' => $this->repo->getDaysAgo(2),
            '1M' => $this->repo->getMonthsAgo(1),
            '3M' => $this->repo->getMonthsAgo(3),
            '6M' => $this->repo->getMonthsAgo(6),
            'YTD' => $this->repo->getStartOfYear(),
            '1Y' => $this->repo->getYearsAgo(1),
            '3Y' => $this->repo->getYearsAgo(3),
            '5Y' => $this->repo->getYearsAgo(5),
            '10Y' => $this->repo->getYearsAgo(10),
            'MAX' => $this->repo->getOldest(),
            default => throw new Exception("Invalid period: {$period}"),
        };

        if (!$old) {
            throw new Exception("Not enough data to compute {$period} change");
        }

        $change = (($latest->price / $old->price) - 1) * 100;

        return [
            'current' => round($latest->price, 2),
            'previous' => round($old->price, 2),
            'change_percentage' => round($change, 2) . '%',
        ];
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     * @throws Exception
     */
    public function computeChangeBetweenDates(string $start, string $end): array
    {
        [$startPrice, $endPrice] = $this->repo->getBetweenDates($start, $end);

        if (!$startPrice || !$endPrice) {
            throw new Exception("Not enough data between {$start} and {$end}");
        }

        $change = (($endPrice->price / $startPrice->price) - 1) * 100;

        return [
            'start' => round($startPrice->price, 2),
            'end' => round($endPrice->price, 2),
            'change_percentage' => round($change, 2) . '%'
        ];
    }
}
