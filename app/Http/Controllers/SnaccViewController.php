<?php

namespace App\Http\Controllers;

use App\Models\Snacc;
use App\Services\CommentService;

use App\Services\ViewService;
use App\Services\HeatService;
use App\Jobs\UpdateHeatScore;
use App\Traits\RendersCommentHtml;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SnaccViewController extends Controller
{
    use RendersCommentHtml;

    public function __construct(
        protected CommentService $commentService,
        protected ViewService $viewService
    ) {}

    public function show(Snacc $snacc): View
    {
        // Track unique view
        $this->viewService->recordView($snacc);
        UpdateHeatScore::dispatchAfterResponse($snacc);

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

        // Transform comments to include HTML
        $comments = $this->transformCommentsWithHtml($comments, includeReplies: false);

        return view('snaccs.show', compact('snacc', 'comments'));
    }
}
