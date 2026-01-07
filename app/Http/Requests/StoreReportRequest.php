<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'reportable_type' => ['required', 'in:snacc,comment,user'],
            'reportable_slug' => ['required', 'string'],
            'category_slug' => ['required', 'exists:report_categories,slug'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_slug.required' => 'please select a reason',
            'category_slug.exists' => 'invalid report category',
            'description.max' => 'description cannot exceed 500 characters',
            'reportable_type.in' => 'invalid content type',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('description') && is_string($this->input('description'))) {
            $trimmed = trim($this->input('description'));
            $this->merge([
                'description' => $trimmed ?: null,
            ]);
        }
    }
}
