<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,csv|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'You must upload an Excel file.',
            'file.mimes' => 'Only Excel or CSV files are allowed.',
            'file.max' => 'File size cannot exceed 10MB.',
        ];
    }
}
