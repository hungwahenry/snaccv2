<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Snacc;
use App\Services\CommentService;
use App\Traits\RendersCommentHtml;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use RendersCommentHtml;

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

        $comment->load(['user.profile', 'repliedToUser.profile'])
                ->loadCount('replies');

        if ($request->wantsJson()) {
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
        $comments = $this->commentService->getCommentsForSnacc($snacc, 10);
        $comments = $this->transformCommentsWithHtml($comments, includeReplies: false);

        return response()->json([
            'success' => true,
            'comments' => $comments->items(),
            'has_more' => $comments->hasMorePages(),
            'next_page_url' => $comments->nextPageUrl(),
        ]);
    }

    public function replies(Request $request, Comment $comment): JsonResponse
    {
        $replies = $this->commentService->getRepliesForComment($comment, 10);
        $this->transformRepliesWithHtml($replies->getCollection());

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
