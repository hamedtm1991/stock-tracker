<?php

namespace App\Core\Repositories\Contracts;

interface EquityRepositoryInterface
{
    public function bulkUpsert(array $data);
    public function getLatest();
    public function getOldest();
    public function getDaysAgo(int $days);
    public function getMonthsAgo(int $months);
    public function getYearsAgo(int $years);
    public function getStartOfYear();
    public function getBetweenDates(string $start, string $end);
}
