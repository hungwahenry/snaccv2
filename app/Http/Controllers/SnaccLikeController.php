<?php

namespace App\Http\Controllers;

use App\Models\Snacc;
use App\Models\SnaccLike;
use Illuminate\Http\JsonResponse;

class SnaccLikeController extends Controller
{
    public function toggle(Snacc $snacc): JsonResponse
    {
        $user = auth()->user();

        $like = SnaccLike::where('snacc_id', $snacc->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $isLiked = false;
        } else {
            // Like
            SnaccLike::create([
                'snacc_id' => $snacc->id,
                'user_id' => $user->id,
            ]);
            $isLiked = true;
        }

        // Refresh the snacc to get updated likes_count
        $snacc->refresh();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $snacc->likes_count,
        ]);
    }
}
