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
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    use RendersCommentHtml;

    public function __construct(
        protected CommentService $commentService
    ) {}

    public function store(StoreCommentRequest $request, Snacc $snacc): JsonResponse|RedirectResponse
    {
        Log::info('💬 [COMMENT STORE] Request received', [
            'snacc_id' => $snacc->id,
            'user_id' => auth()->id(),
            'is_json' => $request->wantsJson(),
        ]);

        $validated = $request->validated();
        Log::info('💬 [COMMENT STORE] Validated data', $validated);

        $comment = $this->commentService->createComment(
            snaccId: $snacc->id,
            userId: auth()->id(),
            content: $validated['content'] ?? null,
            gifUrl: $validated['gif_url'] ?? null,
            parentCommentId: $validated['parent_comment_id'] ?? null,
            repliedToUserId: $validated['replied_to_user_id'] ?? null
        );

        Log::info('💬 [COMMENT STORE] Comment created', [
            'comment_id' => $comment->id,
            'parent_comment_id' => $comment->parent_comment_id,
            'is_reply' => $comment->parent_comment_id !== null,
        ]);

        // Load relationships for JSON response
        $comment->load(['user.profile', 'repliedToUser.profile'])
                ->loadCount('replies');

        if ($request->wantsJson()) {
            // Determine the correct view based on whether this is a reply or top-level comment
            $viewName = $comment->parent_comment_id
                ? 'components.comments.reply'
                : 'components.comments.card';

            Log::info('💬 [COMMENT STORE] Rendering view', [
                'view' => $viewName,
                'comment_id' => $comment->id,
            ]);

            $response = [
                'success' => true,
                'comment' => array_merge($comment->toArray(), [
                    'html' => view($viewName, ['comment' => $comment])->render()
                ]),
            ];

            Log::info('💬 [COMMENT STORE] JSON response prepared', [
                'comment_id' => $comment->id,
                'has_html' => isset($response['comment']['html']),
            ]);

            return response()->json($response);
        }

        return redirect()->route('snaccs.show', $snacc)->with('success', 'Comment posted!');
    }

    public function index(Request $request, Snacc $snacc): JsonResponse
    {
        Log::info('📋 [COMMENTS INDEX] Load more comments requested', [
            'snacc_id' => $snacc->id,
            'page' => $request->get('page', 1),
        ]);

        $comments = $this->commentService->getCommentsForSnacc($snacc, 10);

        Log::info('📋 [COMMENTS INDEX] Comments fetched from DB', [
            'count' => $comments->count(),
            'total' => $comments->total(),
            'current_page' => $comments->currentPage(),
            'has_more' => $comments->hasMorePages(),
        ]);

        // Transform comments to include HTML (no replies to transform)
        $comments = $this->transformCommentsWithHtml($comments, includeReplies: false);

        $response = [
            'success' => true,
            'comments' => $comments->items(),
            'has_more' => $comments->hasMorePages(),
            'next_page_url' => $comments->nextPageUrl(),
        ];

        Log::info('📋 [COMMENTS INDEX] Response prepared', [
            'comments_count' => count($response['comments']),
            'has_more' => $response['has_more'],
            'next_page_url' => $response['next_page_url'],
        ]);

        return response()->json($response);
    }

    public function replies(Request $request, Comment $comment): JsonResponse
    {
        Log::info('💭 [REPLIES INDEX] Load replies requested', [
            'comment_id' => $comment->id,
            'page' => $request->get('page', 1),
        ]);

        $replies = $this->commentService->getRepliesForComment($comment, 10);

        Log::info('💭 [REPLIES INDEX] Replies fetched from DB', [
            'count' => $replies->count(),
            'total' => $replies->total(),
            'current_page' => $replies->currentPage(),
            'has_more' => $replies->hasMorePages(),
        ]);

        // Transform replies to include HTML using the collection method
        $this->transformRepliesWithHtml($replies->getCollection());

        $response = [
            'success' => true,
            'replies' => $replies->items(),
            'has_more' => $replies->hasMorePages(),
            'next_page' => $replies->currentPage() + 1,
        ];

        Log::info('💭 [REPLIES INDEX] Response prepared', [
            'replies_count' => count($response['replies']),
            'has_more' => $response['has_more'],
            'next_page' => $response['next_page'],
        ]);

        return response()->json($response);
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
