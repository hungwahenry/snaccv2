<x-modal name="gif-picker" maxWidth="md">
    <div class="flex flex-col max-h-[85vh]" x-data="gifPicker('{{ route('giphy.trending') }}', '{{ route('giphy.search') }}')">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-dark-border">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase" style="font-family: 'Boldonse', sans-serif;">choose gif</h2>
            <button
                type="button"
                @click="$dispatch('close-modal', 'gif-picker')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Search -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-dark-border">
            <div class="relative">
                <input
                    type="text"
                    x-model="searchQuery"
                    @input.debounce.500ms="search()"
                    placeholder="search gifs..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-dark-bg border border-gray-200 dark:border-dark-border focus:border-primary-500 dark:focus:border-primary-500 rounded-xl text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-colors"
                />
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
        </div>

        <!-- GIF Grid -->
        <div class="flex-1 overflow-y-auto px-4 py-3">
            <div x-show="loading" class="flex items-center justify-center py-12">
                <x-loading-dots size="lg" />
            </div>

            <div x-show="!loading && gifs.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-sm text-gray-600 dark:text-gray-400 lowercase">no gifs found</p>
            </div>

            <div x-show="!loading && gifs.length > 0" class="grid grid-cols-2 gap-2">
                <template x-for="gif in gifs" :key="gif.id">
                    <button
                        type="button"
                        @click="selectGif(gif)"
                        class="relative aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-dark-bg hover:ring-2 hover:ring-primary-500 transition-all group"
                    >
                        <img
                            :src="gif.url"
                            :alt="gif.title"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        />
                    </button>
                </template>
            </div>

            <div x-show="!loading && hasMore" class="mt-4">
                <button
                    type="button"
                    @click="loadMore()"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-bg border border-gray-200 dark:border-dark-border hover:border-primary-500 dark:hover:border-primary-500 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors lowercase"
                >
                    load more
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-gray-200 dark:border-dark-border">
            <p class="text-xs text-center text-gray-500 dark:text-gray-400 lowercase">
                powered by <a href="https://giphy.com" target="_blank" class="text-primary-500 hover:underline">giphy</a>
            </p>
        </div>
    </div>
</x-modal>
