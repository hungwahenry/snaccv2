<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
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

    public function sendOtp(SendOtpRequest $request): RedirectResponse
    {
        $email = $request->validated()['email'];

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

    public function verifyOtp(VerifyOtpRequest $request): RedirectResponse
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('auth')->with('error', 'Session expired. Please request a new OTP.');
        }

        $otp = $request->validated()['otp'];

        if (!$this->otpService->verify($email, $otp)) {
            $request->recordFailedAttempt();

            throw ValidationException::withMessages([
                'otp' => 'Invalid or expired OTP code.',
            ]);
        }

        $request->clearAttempts();

        // Check if user exists
        $user = User::where('email', $email)->first();
        $isNewUser = !$user;

        if ($isNewUser) {
            $user = User::create([
                'email' => $email,
            ]);

            event(new Registered($user));
        }

        // Log the user in
        Auth::login($user, true);

        $request->session()->regenerate();
        $request->session()->forget('otp_email');

        // Redirect to onboarding if new user, otherwise home
        if ($isNewUser) {
            return redirect()->route('onboarding');
        }

        return redirect()->intended(route('home', absolute: false));
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
