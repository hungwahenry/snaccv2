@props(['comment'])

<button
    type="button"
    @click="window.dispatchEvent(new CustomEvent('reply-to-comment', {
        detail: {
            commentId: {{ $comment->id }},
            parentCommentId: {{ $comment->parent_comment_id ?? $comment->id }},
            username: '{{ $comment->user->profile->username }}',
            userId: {{ $comment->user_id }}
        }
    }))"
    class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors text-xs"
>
    <x-solar-reply-linear class="w-4 h-4" />
    <span>reply</span>
</button>
