<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSnaccRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['nullable', 'string', 'max:1200'],
            'visibility' => ['required', 'in:campus,global'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'max:5120', 'mimes:jpg,jpeg,png,gif'],
            'gif_url' => ['nullable', 'url', 'max:500'],
            'vibetags' => ['nullable', 'array'],
            'vibetags.*' => ['string', 'max:50'],
            'quoted_snacc_slug' => ['nullable', 'exists:snaccs,slug'],
            'is_ghost' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.max' => 'snacc content cannot exceed 1200 characters',
            'images.max' => 'you can only upload up to 10 images',
            'images.*.max' => 'each image must be less than 5mb',
            'visibility.required' => 'please select visibility',
            'visibility.in' => 'visibility must be either campus or global',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('content') && is_string($this->input('content'))) {
            $trimmed = trim($this->input('content'));
            $this->merge([
                'content' => $trimmed ?: null,
            ]);
        }
    }
}
