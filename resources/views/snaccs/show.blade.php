<x-app-layout :hideNavigation="true">
    <div class="max-w-2xl mx-auto pb-24"
         x-data="{
             comments: {{ Js::from($comments->items()) }},
             hasMorePages: {{ $comments->hasMorePages() ? 'true' : 'false' }},
             currentPage: {{ $comments->currentPage() }},
             emptyState: {{ $comments->isEmpty() ? 'true' : 'false' }},
             loadingMore: false,
             async loadMoreComments() {
                 const nextPage = this.currentPage + 1;
                 const url = '{{ route('comments.index', $snacc) }}?page=' + nextPage;

                 console.log('📋 [LOAD MORE COMMENTS] Button clicked', {
                     loadingMore: this.loadingMore,
                     hasMorePages: this.hasMorePages,
                     currentPage: this.currentPage,
                     nextPage: nextPage,
                     url: url
                 });

                 if (this.loadingMore || !this.hasMorePages) {
                     console.warn('📋 [LOAD MORE COMMENTS] Blocked:', {
                         loadingMore: this.loadingMore,
                         hasMorePages: this.hasMorePages
                     });
                     return;
                 }

                 this.loadingMore = true;
                 console.log('📋 [LOAD MORE COMMENTS] Fetching:', url);

                 try {
                     const response = await fetch(url, {
                         headers: {
                             'Accept': 'application/json'
                         }
                     });

                     console.log('📋 [LOAD MORE COMMENTS] Response status:', response.status);
                     const data = await response.json();
                     console.log('📋 [LOAD MORE COMMENTS] Response data:', data);

                     if (data.success) {
                         console.log('📋 [LOAD MORE COMMENTS] Adding comments:', {
                             newComments: data.comments.length,
                             currentTotal: this.comments.length
                         });
                         this.comments.push(...data.comments);
                         this.hasMorePages = data.has_more;
                         this.currentPage = nextPage;

                         console.log('📋 [LOAD MORE COMMENTS] State updated:', {
                             totalComments: this.comments.length,
                             hasMorePages: this.hasMorePages,
                             currentPage: this.currentPage
                         });
                     }
                 } catch (error) {
                     console.error('📋 [LOAD MORE COMMENTS] ERROR:', error);
                 } finally {
                     this.loadingMore = false;
                 }
             }
         }"
         @comment-posted.window="
             if ($event.detail.comment.parent_comment_id === null) {
                 comments.unshift($event.detail.comment);
                 emptyState = false;
             }
         "
    >
        <!-- Single Snacc Post -->
        <x-posts.card :snacc="$snacc" />

        <!-- Comments Section -->
        <div class="mt-4">
            <div x-show="emptyState" class="text-center py-12 px-4">
                <x-solar-chat-round-line-linear class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white lowercase mb-2">no comments yet</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 lowercase">be the first to comment!</p>
            </div>

            <template x-for="comment in comments" :key="comment.id">
                <div x-html="comment.html"></div>
            </template>

            <!-- Load More Comments -->
            <div x-show="hasMorePages" class="px-4 py-6">
                <button
                    @click="loadMoreComments()"
                    :disabled="loadingMore"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-bg border border-gray-200 dark:border-dark-border hover:border-primary-500 dark:hover:border-primary-500 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors lowercase disabled:opacity-50 disabled:cursor-not-allowed"
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
