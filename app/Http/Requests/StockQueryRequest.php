<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ];
    }

    public function messages(): array
    {
        return [
            'start.required' => 'Start date is required.',
            'start.date' => 'Start date must be a valid date.',
            'end.required' => 'End date is required.',
            'end.date' => 'End date must be a valid date.',
            'end.after_or_equal' => 'End date must be after or equal to the start date.',
        ];
    }
}
