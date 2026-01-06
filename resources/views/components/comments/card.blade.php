@props(['comment'])

<article class="border-b border-gray-200 dark:border-dark-border"
         x-data="{
             replies: [],
             repliesCount: {{ $comment->replies_count ?? 0 }},
             showReplies: false,
             loadingReplies: false,
             repliesPage: 1,
             hasMoreReplies: {{ $comment->replies_count > 0 ? 'true' : 'false' }},
             repliesLoaded: false,
             async toggleReplies() {
                 console.log('💭 [TOGGLE REPLIES] Toggling for comment {{ $comment->id }}', {
                     showReplies: this.showReplies,
                     repliesLoaded: this.repliesLoaded,
                     repliesCount: this.repliesCount
                 });

                 if (!this.showReplies) {
                     this.showReplies = true;

                     // Load replies on first expand if not already loaded
                     if (!this.repliesLoaded && this.repliesCount > 0) {
                         console.log('💭 [TOGGLE REPLIES] Loading replies for first time');
                         await this.loadMoreReplies();
                         this.repliesLoaded = true;
                     }
                 } else {
                     console.log('💭 [TOGGLE REPLIES] Hiding replies');
                     this.showReplies = false;
                 }
             },
             async loadMoreReplies() {
                 console.log('💭 [LOAD MORE REPLIES] Starting for comment {{ $comment->id }}', {
                     loadingReplies: this.loadingReplies,
                     repliesPage: this.repliesPage,
                     currentRepliesCount: this.replies.length
                 });

                 if (this.loadingReplies) {
                     console.warn('💭 [LOAD MORE REPLIES] Already loading, skipping');
                     return;
                 }

                 this.loadingReplies = true;
                 const url = '/comments/{{ $comment->id }}/replies?page=' + this.repliesPage;
                 console.log('💭 [LOAD MORE REPLIES] Fetching:', url);

                 try {
                     const response = await fetch(url, {
                         headers: {
                             'Accept': 'application/json'
                         }
                     });

                     console.log('💭 [LOAD MORE REPLIES] Response status:', response.status);
                     const data = await response.json();
                     console.log('💭 [LOAD MORE REPLIES] Response data:', data);

                     if (data.success) {
                         console.log('💭 [LOAD MORE REPLIES] Adding replies:', {
                             newReplies: data.replies.length,
                             currentTotal: this.replies.length
                         });
                         this.replies.push(...data.replies);
                         this.hasMoreReplies = data.has_more;
                         this.repliesPage = data.next_page;
                         console.log('💭 [LOAD MORE REPLIES] State updated:', {
                             totalReplies: this.replies.length,
                             hasMoreReplies: this.hasMoreReplies,
                             nextPage: this.repliesPage
                         });
                     }
                 } catch (error) {
                     console.error('💭 [LOAD MORE REPLIES] ERROR:', error);
                 } finally {
                     this.loadingReplies = false;
                 }
             }
         }"
         @comment-posted.window="
             console.log('💭 [NEW REPLY EVENT] Received for comment {{ $comment->id }}', {
                 eventCommentId: $event.detail.comment.id,
                 parentCommentId: $event.detail.comment.parent_comment_id,
                 thisCommentId: {{ $comment->id }},
                 matches: $event.detail.comment.parent_comment_id == {{ $comment->id }}
             });
             if ($event.detail.comment.parent_comment_id == {{ $comment->id }}) {
                 console.log('💭 [NEW REPLY EVENT] Adding reply to this comment');
                 replies.push($event.detail.comment);
                 repliesCount++;
                 showReplies = true;
                 repliesLoaded = true;
                 console.log('💭 [NEW REPLY EVENT] Reply added', {
                     totalReplies: replies.length,
                     repliesCount: repliesCount
                 });
             }
         "
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
                        <template x-for="reply in replies" :key="reply.id">
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
