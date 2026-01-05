<?php

namespace App\Services;

use App\Models\OtpCode;
use Carbon\Carbon;

class OtpService
{
    public function generate(string $email): string
    {
        // Invalidate any previous OTP codes for this email
        OtpCode::where('email', $email)
            ->where('used', false)
            ->update(['used' => true]);

        // Generate a 6-digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create new OTP record
        OtpCode::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'used' => false,
        ]);

        return $otp;
    }

    public function verify(string $email, string $otp): bool
    {
        $otpCode = OtpCode::where('email', $email)
            ->where('otp', $otp)
            ->where('used', false)
            ->first();

        if (!$otpCode) {
            return false;
        }

        if ($otpCode->isExpired()) {
            return false;
        }

        // Mark OTP as used
        $otpCode->update(['used' => true]);

        return true;
    }

    public function cleanExpired(): void
    {
        OtpCode::where('expires_at', '<', Carbon::now())->delete();
    }
}
