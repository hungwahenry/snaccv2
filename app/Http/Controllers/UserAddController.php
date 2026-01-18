<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Services\UserAddService;

class UserAddController extends Controller
{
    public function __construct(
        protected UserAddService $userAddService
    ) {}
    public function store(Request $request, User $user)
    {
        // Authorize basic create permission
        Gate::authorize('create', UserAdd::class);
        
        try {
            $this->userAddService->addUser($request->user(), $user);
        } catch (\InvalidArgumentException $e) {
            abort(403, $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_added' => true,
                'added_by_count' => $user->fresh()->added_by_count
            ]);
        }

        return back();
    }

    public function destroy(Request $request, User $user)
    {
        $this->userAddService->removeUser($request->user(), $user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_added' => false,
                'added_by_count' => $user->fresh()->added_by_count
            ]);
        }

        return back();
    }
}

