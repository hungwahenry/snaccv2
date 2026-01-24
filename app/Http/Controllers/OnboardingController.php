<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Models\Profile;
use App\Models\University;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (auth()->user()->profile) {
            return redirect()->route('home');
        }

        $universities = University::orderBy('name')->get();

        return view('onboarding.index', compact('universities'));
    }

    public function store(StoreProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $validated['user_id'] = auth()->id();

        Profile::create($validated);

        return redirect()->route('home')->with('success', 'welcome to snacc! your profile has been created.');
    }
}
