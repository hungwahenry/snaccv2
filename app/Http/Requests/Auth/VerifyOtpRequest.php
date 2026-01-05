<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class VerifyOtpRequest extends FormRequest
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
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'otp.regex' => 'The OTP must be a 6-digit number.',
        ];
    }

    /**
     * Handle rate limiting for OTP verification attempts.
     */
    protected function passedValidation(): void
    {
        $key = 'verify-otp:' . $this->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'otp' => 'Too many failed attempts. Please request a new OTP.',
            ]);
        }
    }

    /**
     * Increment rate limiter on failed verification.
     */
    public function recordFailedAttempt(): void
    {
        $key = 'verify-otp:' . $this->ip();
        RateLimiter::hit($key, 300);
    }

    /**
     * Clear rate limiter on successful verification.
     */
    public function clearAttempts(): void
    {
        $key = 'verify-otp:' . $this->ip();
        RateLimiter::clear($key);
    }
}
