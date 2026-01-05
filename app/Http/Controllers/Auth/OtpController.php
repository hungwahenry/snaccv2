<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showEmailForm(): View
    {
        return view('auth.otp.request');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        $key = 'send-otp:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Too many OTP requests. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($key, 60);

        $email = $request->email;

        // Generate OTP
        $otp = $this->otpService->generate($email);

        // Send OTP email
        Mail::to($email)->send(new OtpMail($otp));

        // Store email in session for verification step
        $request->session()->put('otp_email', $email);

        return redirect()->route('auth.verify.show')
            ->with('status', 'We\'ve sent a 6-digit code to your email. Please check your inbox.');
    }

    public function showVerifyForm(): View
    {
        if (!session('otp_email')) {
            return redirect()->route('auth')->with('error', 'Please request an OTP first.');
        }

        return view('auth.otp.verify');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('auth')->with('error', 'Session expired. Please request a new OTP.');
        }

        $key = 'verify-otp:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'otp' => 'Too many failed attempts. Please request a new OTP.',
            ]);
        }

        if (!$this->otpService->verify($email, $request->otp)) {
            RateLimiter::hit($key, 300);

            throw ValidationException::withMessages([
                'otp' => 'Invalid or expired OTP code.',
            ]);
        }

        RateLimiter::clear($key);

        // Check if user exists
        $user = User::where('email', $email)->first();
        $isNewUser = !$user;

        if ($isNewUser) {
            // Create user with temporary name (will be updated in onboarding)
            $user = User::create([
                'email' => $email,
                'name' => explode('@', $email)[0], // Temporary
            ]);

            event(new Registered($user));
        }

        // Log the user in
        Auth::login($user, true);

        $request->session()->regenerate();
        $request->session()->forget('otp_email');

        // Redirect to onboarding if new user, otherwise dashboard
        if ($isNewUser) {
            return redirect()->route('onboarding');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('auth')->with('error', 'Session expired. Please start over.');
        }

        $key = 'resend-otp:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 2)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->with('error', "Please wait {$seconds} seconds before requesting another code.");
        }

        RateLimiter::hit($key, 120);

        // Generate new OTP
        $otp = $this->otpService->generate($email);

        // Send OTP email
        Mail::to($email)->send(new OtpMail($otp));

        return back()->with('status', 'A new code has been sent to your email.');
    }
}
