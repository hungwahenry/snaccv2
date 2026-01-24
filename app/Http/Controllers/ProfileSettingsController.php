<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileSettingsController extends Controller
{
    /**
     * Display the user's profile settings form.
     */
    public function edit(Request $request): View
    {
        return view('settings.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileSettingsRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        // Update Profile Photo
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile->profile_photo) {
                Storage::disk('public')->delete($user->profile->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile->profile_photo = $path;
        }

        // Update Profile Fields
        $user->profile->username = $validated['username'];
        $user->profile->bio = $validated['bio'];
        $user->profile->graduation_year = $validated['graduation_year'];
        $user->profile->gender = $validated['gender'];
        $user->profile->save();

        return back()->with('success', 'profile updated.');
    }
}
