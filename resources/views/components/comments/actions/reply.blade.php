@props(['comment'])

<button
    type="button"
    @click="window.dispatchEvent(new CustomEvent('reply-to-comment', {
        detail: {
            commentSlug: '{{ $comment->slug }}',
            parentCommentSlug: '{{ $comment->parent_comment_id ? ($comment->parentComment->slug ?? $comment->slug) : $comment->slug }}',
            username: '{{ $comment->user->profile->username }}',
            userSlug: '{{ $comment->user->profile->username }}'
        }
    }))"
    class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors text-xs"
>
    <x-solar-reply-linear class="w-4 h-4" />
    <span>reply</span>
</button>
