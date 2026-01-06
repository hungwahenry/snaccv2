<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Snacc;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    public function store(StoreCommentRequest $request, Snacc $snacc): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $comment = $this->commentService->createComment(
            snaccId: $snacc->id,
            userId: auth()->id(),
            content: $validated['content'] ?? null,
            gifUrl: $validated['gif_url'] ?? null,
            parentCommentId: $validated['parent_comment_id'] ?? null,
            repliedToUserId: $validated['replied_to_user_id'] ?? null
        );

        // Load relationships for JSON response
        $comment->load([
            'user.profile',
            'repliedToUser.profile',
            'replies' => function($query) {
                $query->with(['user.profile', 'repliedToUser.profile'])
                      ->orderBy('created_at', 'asc')
                      ->limit(3);
            }
        ]);

        if ($request->wantsJson()) {
            // Determine the correct view based on whether this is a reply or top-level comment
            $viewName = $comment->parent_comment_id
                ? 'components.comments.reply'
                : 'components.comments.card';

            return response()->json([
                'success' => true,
                'comment' => array_merge($comment->toArray(), [
                    'html' => view($viewName, ['comment' => $comment])->render()
                ]),
            ]);
        }

        return redirect()->route('snaccs.show', $snacc)->with('success', 'Comment posted!');
    }

    public function index(Request $request, Snacc $snacc): JsonResponse
    {
        $page = $request->get('page', 1);
        $comments = $this->commentService->getCommentsForSnacc($snacc, 10);

        // Transform comments to include HTML
        $comments->getCollection()->transform(function ($comment) {
            if ($comment->replies) {
                $comment->replies->transform(function ($reply) {
                    $reply->html = view('components.comments.reply', ['comment' => $reply])->render();
                    return $reply;
                });
            }

            $comment->html = view('components.comments.card', ['comment' => $comment])->render();
            return $comment;
        });

        return response()->json([
            'success' => true,
            'comments' => $comments->items(),
            'has_more' => $comments->hasMorePages(),
            'next_page_url' => $comments->nextPageUrl(),
        ]);
    }

    public function replies(Request $request, Comment $comment): JsonResponse
    {
        $page = $request->get('page', 1);
        $replies = $this->commentService->getRepliesForComment($comment, 10);

        // Transform replies to include HTML
        $replies->getCollection()->transform(function ($reply) {
            $reply->html = view('components.comments.reply', ['comment' => $reply])->render();
            return $reply;
        });

        return response()->json([
            'success' => true,
            'replies' => $replies->items(),
            'has_more' => $replies->hasMorePages(),
            'next_page' => $replies->currentPage() + 1,
        ]);
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        // Ensure user owns the comment
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $snaccId = $comment->snacc_id;
        $this->commentService->deleteComment($comment);

        return redirect()->route('snaccs.show', $snaccId)->with('success', 'Comment deleted!');
    }
}
