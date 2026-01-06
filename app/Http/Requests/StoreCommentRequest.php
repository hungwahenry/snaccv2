<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
        return [
            'content' => 'nullable|string|max:1000',
            'gif_url' => 'nullable|url',
            'parent_comment_id' => 'nullable|exists:comments,id',
            'replied_to_user_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (empty($this->content) && empty($this->gif_url)) {
                $validator->errors()->add('content', 'Comment must have content or a GIF.');
            }
        });
    }
}
