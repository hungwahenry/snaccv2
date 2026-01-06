<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\JsonResponse;

class CommentLikeController extends Controller
{
    public function toggle(Comment $comment): JsonResponse
    {
        $user = auth()->user();

        $like = CommentLike::where('comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $isLiked = false;
        } else {
            // Like
            CommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
            ]);
            $isLiked = true;
        }

        // Refresh the comment to get updated likes_count
        $comment->refresh();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $comment->likes_count,
        ]);
    }
}
