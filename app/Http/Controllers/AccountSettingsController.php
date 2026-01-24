<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountSettingsController extends Controller
{
    /**
     * Display the account settings.
     */
    public function edit(Request $request): View
    {
        return view('settings.account', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's email address.
     */
    public function updateEmail(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
        ]);

        $user = $request->user();
        $user->fill($request->only('email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->save();

            // Handle Re-verification
            $email = $user->email;
            $otp = $otpService->generate($email);
            Mail::to($email)->send(new OtpMail($otp));
            
            $request->session()->put('otp_email', $email);
            
            return redirect()->route('auth.verify.show')
                ->with('success', 'email updated. please verify your new email.');
        }

        $user->save();

        return back()->with('success', 'email updated.');
    }

    /**
     * Export user data.
     */
    public function export(Request $request)
    {
        $user = $request->user();
        
        $data = [
            'profile' => $user->profile->toArray(),
            'snaccs' => $user->snaccs()->with('vibetags')->get()->toArray(),
            'comments' => $user->comments()->get()->toArray(),
        ];

        $filename = 'snacc-data-' . now()->format('Y-m-d') . '.json';

        return Response::json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'email_confirmation' => ['required', 'email', 'in:' . $request->user()->email],
        ], [
            'email_confirmation.in' => 'The provided email does not match your account email.',
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
