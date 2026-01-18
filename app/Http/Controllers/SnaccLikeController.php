<?php

namespace App\Http\Controllers;

use App\Models\Snacc;
use App\Models\SnaccLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SnaccLikeController extends Controller
{
    public function __construct(
        protected \App\Services\LikeService $likeService
    ) {}

    public function toggle(Snacc $snacc): JsonResponse
    {
        $result = $this->likeService->toggleSnaccLike($snacc, auth()->user());
        $isLiked = $result['is_liked'];
        // $result['likes_count'] is also available if needed, but we refresh snacc below or use result directly

        // Refresh the snacc to get updated likes_count
        $snacc->refresh();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $snacc->likes_count,
        ]);
    }
}
