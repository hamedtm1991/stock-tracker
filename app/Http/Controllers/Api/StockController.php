<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Core\Services\PerformanceCalculator;
use App\Jobs\ImportEquityExcelJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class StockController extends Controller
{
    protected PerformanceCalculator $calculator;

    public function __construct(PerformanceCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');

        $path = $file->store('stocks', 'private');

        ImportEquityExcelJob::dispatch($path);

        return response()->json([
            'status' => 'success',
            'message' => 'File uploaded and import started',
            'path' => $path,
        ]);
    }

    /**
     * @param string $period
     * @return JsonResponse
     */
    public function byPeriod(string $period): JsonResponse
    {
        try {
            $data = $this->calculator->computeChange(strtoupper($period));

            return response()->json([
                'period' => strtoupper($period),
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function byCustomDates(Request $request): JsonResponse
    {
        $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date|after_or_equal:start',
        ]);

        try {
            $data = $this->calculator->computeChangeBetweenDates(
                $request->start,
                $request->end
            );

            return response()->json([
                'start' => $request->start,
                'end' => $request->end,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
