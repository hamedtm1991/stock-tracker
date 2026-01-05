<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockUploadRequest;
use App\Jobs\ImportEquityExcelJob;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StockUploadController extends Controller
{
    /**
     * @return Factory|View
     */
    public function showForm()
    {
        return view('stocks.upload');
    }

    /**
     * @param StockUploadRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(StockUploadRequest $request)
    {
        $filePath = $request->file('file')->store('uploads');

        ImportEquityExcelJob::dispatch($filePath);

        return back()->with('success', 'File uploaded successfully. Processing in background.');
    }
}
