<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

use App\Services\LikeService;

class CommentLikeController extends Controller
{
    public function __construct(
        protected LikeService $likeService
    ) {}

    public function toggle(Comment $comment): JsonResponse
    {
        $result = $this->likeService->toggleCommentLike($comment, auth()->user());
        
        return response()->json([
            'success' => true,
            'is_liked' => $result['is_liked'],
            'likes_count' => $result['likes_count'],
        ]);
    }
}
