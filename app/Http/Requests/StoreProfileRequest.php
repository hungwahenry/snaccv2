<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && !auth()->user()->profile;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'university_id' => ['required', 'exists:universities,id'],
            'username' => ['required', 'string', 'lowercase', 'min:3', 'max:30', 'alpha_dash', 'unique:profiles,username', 'regex:/^[a-z0-9_]+$/'],
            'graduation_year' => ['required', 'integer', 'min:' . date('Y'), 'max:' . (date('Y') + 10)],
            'gender' => ['required', 'in:male,female,other,prefer_not_to_say'],
            'bio' => ['nullable', 'string', 'max:500'],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'username.regex' => 'Username can only contain lowercase letters, numbers, and underscores.',
            'username.alpha_dash' => 'Username can only contain lowercase letters, numbers, and underscores.',
            'graduation_year.min' => 'Graduation year must be at least the current year.',
        ];
    }
}
