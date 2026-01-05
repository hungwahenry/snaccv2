<?php

namespace App\Http\Controllers;

use App\Models\Snacc;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get snaccs based on user's visibility preferences
        // For now, show all snaccs from user's university (campus) and global snaccs
        $snaccs = Snacc::with(['user.profile', 'university', 'images', 'vibetags', 'quotedSnacc.user.profile', 'quotedSnacc.images'])
            ->where(function ($query) use ($user) {
                $query->where('visibility', 'global')
                    ->orWhere(function ($q) use ($user) {
                        $q->where('visibility', 'campus')
                            ->where('university_id', $user->profile->university_id);
                    });
            })
            ->where('is_deleted', false)
            ->latest()
            ->paginate(20);

        return view('home', compact('snaccs'));
    }
}
