<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ImportEquityExcelJob;

class StockUploadTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_redirects_after_web_form_upload()
    {
        Queue::fake();

        $file = UploadedFile::fake()->create('stocks.xlsx', 100);

        // Web form route: /stocks/upload
        $response = $this->post('/stocks/upload', [
            'file' => $file,
        ]);

        // Web form will redirect after successful submission
        $response->assertStatus(302);

        // Ensure the import job is dispatched
        Queue::assertPushed(ImportEquityExcelJob::class);
    }
}
