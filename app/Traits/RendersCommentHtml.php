<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait RendersCommentHtml
{
    /**
     * Transform a paginated collection of comments to include rendered HTML
     */
    protected function transformCommentsWithHtml(LengthAwarePaginator $comments, bool $includeReplies = false): LengthAwarePaginator
    {
        $comments->getCollection()->transform(function ($comment) use ($includeReplies) {
            // Transform replies if they exist and are needed
            if ($includeReplies && $comment->replies) {
                $comment->replies->transform(function ($reply) {
                    $reply->html = view('components.comments.reply', ['comment' => $reply])->render();
                    return $reply;
                });
            }

            $comment->html = view('components.comments.card', ['comment' => $comment])->render();
            return $comment;
        });

        return $comments;
    }

    /**
     * Transform a collection of replies to include rendered HTML
     */
    protected function transformRepliesWithHtml(Collection $replies): Collection
    {
        return $replies->transform(function ($reply) {
            $reply->html = view('components.comments.reply', ['comment' => $reply])->render();
            return $reply;
        });
    }
}
