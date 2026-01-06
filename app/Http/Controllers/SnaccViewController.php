<?php

namespace App\Http\Controllers;

use App\Models\Snacc;
use App\Services\CommentService;
use App\Traits\RendersCommentHtml;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SnaccViewController extends Controller
{
    use RendersCommentHtml;

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

        // Get comments using service (no replies loaded, just the count)
        $comments = $this->commentService->getCommentsForSnacc($snacc);

        // Transform comments to include HTML (no replies to transform)
        $comments = $this->transformCommentsWithHtml($comments, includeReplies: false);

        return view('snaccs.show', compact('snacc', 'comments'));
    }
}
