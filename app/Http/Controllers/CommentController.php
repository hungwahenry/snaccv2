<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Snacc;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    public function store(Request $request, Snacc $snacc): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'nullable|string|max:1000',
            'gif_url' => 'nullable|url',
            'parent_comment_id' => 'nullable|exists:comments,id',
            'replied_to_user_id' => 'nullable|exists:users,id',
        ]);

        // Ensure at least content or gif_url is provided
        if (empty($validated['content']) && empty($validated['gif_url'])) {
            return redirect()->back()->withErrors(['content' => 'Comment must have content or a GIF.']);
        }

        $this->commentService->createComment(
            snaccId: $snacc->id,
            userId: auth()->id(),
            content: $validated['content'] ?? null,
            gifUrl: $validated['gif_url'] ?? null,
            parentCommentId: $validated['parent_comment_id'] ?? null,
            repliedToUserId: $validated['replied_to_user_id'] ?? null
        );

        return redirect()->route('snaccs.show', $snacc)->with('success', 'Comment posted!');
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
