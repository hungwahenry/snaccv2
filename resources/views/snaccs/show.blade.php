@php
    $pageTitle = $snacc->is_ghost ? 'Ghost Snacc' : 'Snacc by ' . $snacc->user->profile->username;
    $pageDescription = Str::limit($snacc->content, 150);
@endphp

<x-app-layout :title="$pageTitle" :description="$pageDescription">
    <div class="pb-24"
         x-data="commentsList(
             {{ Js::from($comments->items()) }},
             {{ $comments->hasMorePages() ? 'true' : 'false' }},
             {{ $comments->currentPage() }},
             '{{ route('comments.index', $snacc) }}'
         )"
    >
        <!-- Single Snacc -->
        <x-posts.card :snacc="$snacc" />

        <!-- Comments Section -->
        <div class="mt-4">
            <div x-show="emptyState" class="text-center py-12 px-4">
                <x-solar-chat-round-line-linear class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white lowercase mb-2">no comments yet</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 lowercase">be the first to comment!</p>
            </div>

            <template x-for="comment in comments" :key="comment.slug">
                <div x-html="comment.html"></div>
            </template>

            <!-- Load More Comments -->
            <div x-show="hasMorePages" class="px-4 py-6">
                <button
                    @click="loadMoreComments()"
                    :disabled="loadingMore"
                    class="w-full min-h-[42px] px-4 py-2.5 bg-gray-50 dark:bg-dark-bg border border-gray-200 dark:border-dark-border hover:border-primary-500 dark:hover:border-primary-500 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors lowercase disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="!loadingMore">view more comments</span>
                    <span x-show="loadingMore" class="flex items-center justify-center">
                        <x-loading-dots size="sm" />
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Fixed Comment Input at Bottom -->
    <x-comments.input :snacc="$snacc" />
</x-app-layout>
