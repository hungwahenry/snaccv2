<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppSettingsController extends Controller
{
    /**
     * Display the app settings.
     */
    public function edit(Request $request): View
    {
        return view('settings.app', [
            'user' => $request->user(),
            'preferences' => $request->user()->preferences ?? [],
        ]);
    }

    /**
     * Update the app settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
            'autoplay_media' => ['required', 'in:always,wifi,never'],
        ]);

        $user = $request->user();
        $preferences = $user->preferences ?? [];
        
        // Merge new preferences with existing ones
        $user->preferences = array_merge($preferences, $validated);
        $user->save();

        return back()->with('success', 'settings saved.');
    }
}
