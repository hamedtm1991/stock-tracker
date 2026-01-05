<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class StockReportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_stock_performance_report(): void
    {
        DB::table('stock_prices')->insert([
            ['date' => '2026-01-01', 'price' => 100],
            ['date' => '2026-01-02', 'price' => 110],
        ]);

        $response = $this->getJson('/api/stocks/period/1D');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'current' => 110,
                    'previous' => 100,
                    'change_percentage' => '10%',
                ]
            ]);
    }
}
