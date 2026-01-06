@props(['snacc'])

<div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-dark-surface border-t border-gray-200 dark:border-dark-border shadow-lg z-10">
    <div class="max-w-2xl mx-auto px-4 py-3">
        <form
            action="{{ route('comments.store', $snacc) }}"
            method="POST"
            x-data="{
                content: '',
                gifUrl: '',
                selectedGif: null,
                parentCommentId: null,
                repliedToUserId: null,
                replyingToUsername: null,
                maxLength: 1000,
                get remainingChars() {
                    return this.maxLength - this.content.length;
                },
                clearReply() {
                    this.parentCommentId = null;
                    this.repliedToUserId = null;
                    this.replyingToUsername = null;
                },
                removeGif() {
                    this.selectedGif = null;
                    this.gifUrl = '';
                }
            }"
            @reply-to-comment.window="
                parentCommentId = $event.detail.parentCommentId;
                repliedToUserId = $event.detail.userId;
                replyingToUsername = $event.detail.username;
                $refs.commentInput.focus();
            "
            @gif-selected.window="
                selectedGif = $event.detail;
                gifUrl = $event.detail.original_url;
            "
        >
            @csrf

            <!-- Hidden inputs for reply data -->
            <input type="hidden" name="parent_comment_id" x-model="parentCommentId">
            <input type="hidden" name="replied_to_user_id" x-model="repliedToUserId">
            <input type="hidden" name="gif_url" x-model="gifUrl">

            <!-- Replying To Banner -->
            <div x-show="replyingToUsername" x-cloak class="mb-2 flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-dark-bg px-3 py-2 rounded-lg">
                <span>replying to <span class="text-primary-600 dark:text-primary-400 font-medium lowercase" x-text="'@' + replyingToUsername"></span></span>
                <button type="button" @click="clearReply()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- GIF Preview -->
            <div x-show="selectedGif" x-cloak class="mb-2 relative">
                <img :src="selectedGif?.url" alt="Selected GIF" class="max-h-40 rounded-lg">
                <button
                    type="button"
                    @click="removeGif()"
                    class="absolute top-2 right-2 p-1.5 bg-gray-900/80 hover:bg-gray-900 text-white rounded-full transition-colors"
                    title="Remove GIF"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Input Container -->
            <div class="flex items-center gap-3">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <img
                        src="{{ auth()->user()->profile->profile_photo ? Storage::url(auth()->user()->profile->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random' }}"
                        alt="{{ auth()->user()->name }}"
                        class="w-10 h-10 rounded-full object-cover"
                    >
                </div>

                <!-- Input Box with integrated actions -->
                <div class="flex-1 relative">
                    <div class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 dark:border-dark-border rounded-2xl bg-white dark:bg-dark-bg focus-within:ring-2 focus-within:ring-primary-500 dark:focus-within:ring-primary-400 focus-within:border-transparent">
                        <!-- Input -->
                        <textarea
                            name="content"
                            x-ref="commentInput"
                            x-model="content"
                            :maxlength="maxLength"
                            placeholder="write a comment..."
                            rows="1"
                            class="flex-1 bg-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none resize-none lowercase text-sm"
                            style="max-height: 100px;"
                            @input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 100) + 'px'"
                        ></textarea>

                        <!-- Character Counter (inline) -->
                        <span x-show="content.length > 0" class="text-xs text-gray-400 dark:text-gray-500 flex-shrink-0" x-text="remainingChars" x-cloak></span>

                        <!-- GIF Button -->
                        <button
                            type="button"
                            @click="$dispatch('open-modal', 'gif-picker')"
                            class="flex-shrink-0 p-1.5 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-dark-surface"
                            title="add GIF"
                        >
                            <x-solar-file-smile-linear class="w-5 h-5" />
                        </button>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="content.trim() === '' && gifUrl === ''"
                            :class="(content.trim() === '' && gifUrl === '') ? 'opacity-50 cursor-not-allowed' : 'hover:bg-primary-700 dark:hover:bg-primary-600'"
                            class="flex-shrink-0 p-1.5 bg-primary-600 dark:bg-primary-500 text-white rounded-full transition-colors"
                            title="post comment"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

