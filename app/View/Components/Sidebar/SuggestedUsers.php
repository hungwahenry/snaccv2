<?php

namespace App\View\Components\Sidebar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\User;

class SuggestedUsers extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = auth()->user();
        
        $suggestedUsers = collect();

        if ($user && $user->profile) {
            // Suggest users from same uni, high cred, not already added, not blocked
            $users = User::whereHas('profile', function ($q) use ($user) {
                    $q->where('university_id', $user->profile->university_id)
                      ->where('user_id', '!=', $user->id);
                })
                ->whereDoesntHave('addedByUsers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->whereDoesntHave('blockedByUsers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->whereDoesntHave('blockedUsers', function ($q) use ($user) {
                    $q->where('blocked_user_id', $user->id);
                })
                ->orderByDesc('cred_score')
                ->take(5)
                ->get();
            
            $suggestedUsers = $users;
        }

        return view('components.sidebar.suggested-users', compact('suggestedUsers'));
    }
}
