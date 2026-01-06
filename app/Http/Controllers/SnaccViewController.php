<?php

namespace App\Http\Controllers;

use App\Models\Snacc;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SnaccViewController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    public function show(Snacc $snacc): View
    {
        // Load the snacc with all necessary relationships
        $snacc->load([
            'user.profile',
            'university',
            'images',
            'vibetags',
            'quotedSnacc.user.profile',
            'quotedSnacc.images',
            'quotedSnacc.university'
        ]);

        // Get comments using service
        $comments = $this->commentService->getCommentsForSnacc($snacc);

        // Transform comments to include HTML for Alpine rendering
        $comments->getCollection()->transform(function ($comment) {
            // Transform replies to include HTML
            if ($comment->replies) {
                $comment->replies->transform(function ($reply) {
                    $reply->html = view('components.comments.reply', ['comment' => $reply])->render();
                    return $reply;
                });
            }

            $comment->html = view('components.comments.card', ['comment' => $comment])->render();
            return $comment;
        });

        return view('snaccs.show', compact('snacc', 'comments'));
    }
}
