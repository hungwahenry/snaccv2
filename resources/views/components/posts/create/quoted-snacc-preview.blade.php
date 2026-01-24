<!-- Quoted Snacc Preview (shown in create modal) -->
<div x-show="quotedSnaccData" x-cloak class="relative">
    <div 
        class="rounded-xl p-3 bg-gray-50 dark:bg-dark-surface"
        :class="quotedSnaccData?.is_ghost ? 'border-2 border-dashed border-gray-300 dark:border-dark-border' : 'border border-gray-200 dark:border-dark-border'"
    >
        <div class="flex gap-2">
            <!-- Content Section -->
            <div class="flex-1 min-w-0">
                <!-- Header -->
                <div class="flex items-center gap-1.5 mb-1.5">
                    <template x-if="quotedSnaccData?.is_ghost">
                        <span class="font-semibold text-xs text-gray-900 dark:text-white lowercase">ghost snacc</span>
                    </template>
                    <template x-if="!quotedSnaccData?.is_ghost">
                        <div class="flex items-center gap-1.5">
                            <img
                                :src="quotedSnaccData?.user?.avatar || 'https://api.dicebear.com/9.x/thumbs/svg?seed=' + encodeURIComponent(quotedSnaccData?.user?.name || 'User') "
                                :alt="quotedSnaccData?.user?.name"
                                class="w-5 h-5 rounded-full object-cover"
                            >
                            <span class="font-semibold text-xs text-gray-900 dark:text-white lowercase" x-text="quotedSnaccData?.user?.username"></span>
                        </div>
                    </template>
                    <span class="text-gray-400 dark:text-gray-500 text-xs">·</span>
                    <time class="text-xs text-gray-500 dark:text-gray-400 lowercase" x-text="quotedSnaccData?.created_at"></time>
                </div>

                <!-- Content -->
                <div x-show="quotedSnaccData?.content" class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2" x-text="quotedSnaccData?.content"></div>
            </div>

            <!-- Media Preview (small square) -->
            <template x-if="quotedSnaccData?.first_image">
                <div class="flex-shrink-0">
                    <img
                        :src="quotedSnaccData.first_image"
                        alt="Post image"
                        class="w-16 h-16 object-cover rounded-lg"
                    >
                </div>
            </template>

            <template x-if="!quotedSnaccData?.first_image && quotedSnaccData?.gif_url">
                <div class="flex-shrink-0">
                    <img
                        :src="quotedSnaccData.gif_url"
                        alt="Quoted GIF"
                        class="w-16 h-16 object-cover rounded-lg"
                    >
                </div>
            </template>
        </div>

        <!-- Remove Button -->
        <button
            type="button"
            @click="removeQuotedSnacc"
            class="absolute top-2 right-2 p-1 text-gray-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-white dark:hover:bg-dark-bg rounded-full transition-colors"
            title="remove quote"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
