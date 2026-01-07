@props(['comment'])

<article class="border-b border-gray-200 dark:border-dark-border"
         x-data="commentCard('{{ $comment->slug }}', {{ $comment->replies_count ?? 0 }})"
>
    <div class="px-4 py-3">
        <div class="flex gap-3">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <img
                    src="{{ $comment->user->profile->profile_photo ? Storage::url($comment->user->profile->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&background=random' }}"
                    alt="{{ $comment->user->name }}"
                    class="w-10 h-10 rounded-full object-cover"
                >
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <!-- Header -->
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="font-semibold text-sm text-gray-900 dark:text-white lowercase">
                        {{ $comment->user->profile->username }}
                    </span>
                    <span class="text-gray-400 dark:text-gray-500 text-xs">·</span>
                    <time class="text-xs text-gray-500 dark:text-gray-400 lowercase">
                        {{ $comment->created_at->diffForHumans(short: true) }}
                    </time>
                    <div class="ml-auto">
                        <x-comments.menu :comment="$comment" />
                    </div>
                </div>

                <!-- Replied To User (if this is a reply) -->
                @if($comment->replied_to_user_id && $comment->repliedToUser)
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                        replying to <span class="text-primary-600 dark:text-primary-400 lowercase">{{ '@' . $comment->repliedToUser->profile->username }}</span>
                    </div>
                @endif

                <!-- Comment Content -->
                @if($comment->content)
                    <div class="text-sm text-gray-900 dark:text-white break-words">
                        {{ $comment->content }}
                    </div>
                @endif

                <!-- GIF -->
                @if($comment->gif_url)
                    <div class="mt-2">
                        <img src="{{ $comment->gif_url }}" alt="Comment GIF" class="max-w-full h-auto rounded-lg max-h-64 object-contain">
                    </div>
                @endif

                <!-- Actions -->
                <x-comments.actions :comment="$comment" />

                <!-- View Replies Button (collapsed state) -->
                <div x-show="repliesCount > 0 && !showReplies" class="mt-3">
                    <button
                        @click="toggleReplies()"
                        :disabled="loadingReplies"
                        type="button"
                        class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 lowercase font-medium disabled:opacity-50"
                    >
                        <span x-show="!loadingReplies" x-text="'view ' + repliesCount + ' ' + (repliesCount === 1 ? 'reply' : 'replies')"></span>
                        <span x-show="loadingReplies">loading...</span>
                    </button>
                </div>

                <!-- Replies Section (expanded state) -->
                <div x-show="showReplies && repliesCount > 0" x-cloak class="mt-3">
                    <div class="space-y-3">
                        <template x-for="reply in replies" :key="reply.slug">
                            <div x-html="reply.html || ''"></div>
                        </template>
                    </div>

                    <!-- Load More Replies Button -->
                    <div x-show="hasMoreReplies" class="mt-2">
                        <button
                            @click="loadMoreReplies()"
                            :disabled="loadingReplies"
                            type="button"
                            class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 lowercase font-medium disabled:opacity-50"
                        >
                            <span x-show="!loadingReplies" x-text="'view ' + (repliesCount - replies.length) + ' more ' + ((repliesCount - replies.length) === 1 ? 'reply' : 'replies')"></span>
                            <span x-show="loadingReplies">loading...</span>
                        </button>
                    </div>

                    <!-- Hide Replies Button -->
                    <button
                        @click="toggleReplies()"
                        type="button"
                        class="mt-2 text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 lowercase"
                    >
                        hide replies
                    </button>
                </div>
            </div>
        </div>
    </div>
</article>
