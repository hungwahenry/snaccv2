<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PrivacySettingsController extends Controller
{
    /**
     * Display the privacy settings.
     */
    public function edit(Request $request): View
    {
        $blockedUsers = $request->user()->blockedUsers()->paginate(10);

        return view('settings.privacy', compact('blockedUsers'));
    }
}
