<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $profileId = $this->user()->profile->id;

        return [
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:profiles,username,' . $profileId],
            'bio' => ['nullable', 'string', 'max:160'],
            'graduation_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'gender' => ['required', 'in:male,female,other,prefer_not_to_say'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ];
    }
}
