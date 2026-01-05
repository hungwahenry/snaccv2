<div x-show="images.length > 0 || selectedGif || error" class="relative">
    <!-- Error Message -->
    <div x-show="error" x-text="error" class="mb-2 text-sm text-red-600 dark:text-red-400 lowercase"></div>

    <!-- Horizontal Scrollable Media Container -->
    <div x-show="images.length > 0 || selectedGif" class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
        <!-- Selected GIF -->
        <template x-if="selectedGif">
            <div class="relative flex-shrink-0 w-32 h-32 rounded-xl overflow-hidden bg-gray-100 dark:bg-dark-bg group">
                <img :src="selectedGif.url" class="w-full h-full object-cover" loading="lazy" />
                <button
                    type="button"
                    @click="selectedGif = null"
                    class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center bg-black/60 hover:bg-black/80 rounded-full text-white transition-colors opacity-0 group-hover:opacity-100"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 bg-black/60 rounded text-white text-[10px] font-medium uppercase">
                    gif
                </div>
            </div>
        </template>

        <!-- Images -->
        <template x-for="(preview, index) in previews" :key="index">
            <div class="relative flex-shrink-0 w-32 h-32 rounded-xl overflow-hidden bg-gray-100 dark:bg-dark-bg group">
                <img :src="preview" class="w-full h-full object-cover" />
                <button
                    type="button"
                    @click="removeImage(index)"
                    class="absolute top-1.5 right-1.5 w-6 h-6 flex items-center justify-center bg-black/60 hover:bg-black/80 rounded-full text-white transition-colors opacity-0 group-hover:opacity-100"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>
</div>
