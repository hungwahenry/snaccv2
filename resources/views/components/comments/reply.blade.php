@props(['comment'])

<div class="flex gap-2">
    <!-- Avatar (smaller) -->
    <div class="flex-shrink-0">
        <img
            src="{{ $comment->user->profile->profile_photo ? Storage::url($comment->user->profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($comment->user->name) }}"
            alt="{{ $comment->user->name }}"
            class="w-8 h-8 rounded-full object-cover"
        >
    </div>

    <!-- Content -->
    <div class="flex-1 min-w-0">
        <!-- Header -->
        <div class="flex items-center gap-1.5 mb-1">
            <span class="font-semibold text-xs text-gray-900 dark:text-white lowercase">
                {{ $comment->user->profile->username }}
            </span>
            <span class="text-gray-400 dark:text-gray-500 text-xs">·</span>
            <span class="text-xs text-gray-500 dark:text-gray-400 lowercase">
                {{ $comment->user->profile->university?->acronym }}
            </span>
            <span class="text-gray-400 dark:text-gray-500 text-xs">·</span>
            <time class="text-xs text-gray-500 dark:text-gray-400 lowercase">
                {{ $comment->created_at->diffForHumans(short: true) }}
            </time>
            <div class="ml-auto">
                <x-comments.menu :comment="$comment" />
            </div>
        </div>

        <!-- Replied To User -->
        @if($comment->replied_to_user_id && $comment->repliedToUser)
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                replying to <span class="text-primary-600 dark:text-primary-400 lowercase">{{ '@' . $comment->repliedToUser->profile->username }}</span>
            </div>
        @endif

        <!-- Reply Content -->
        @if($comment->content)
            <div class="text-sm text-gray-900 dark:text-white break-words">
                {{ $comment->content }}
            </div>
        @endif

        <!-- GIF -->
        @if($comment->gif_url)
            <div class="mt-2">
                <img src="{{ $comment->gif_url }}" alt="Reply GIF" class="max-w-full h-auto rounded-lg max-h-48 object-contain">
            </div>
        @endif

        <!-- Actions -->
        <x-comments.actions :comment="$comment" />
    </div>
</div>
