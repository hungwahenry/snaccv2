<!-- Lightbox Modal -->
<div
    x-data="lightbox()"
    x-show="isOpen"
    x-cloak
    style="display: none"
    @keydown.escape.window="close()"
    @lightbox-open.window="open($event.detail)"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/95"
    @click.self="close()"
>
    <!-- Close Button -->
    <button
        @click="close()"
        class="absolute top-4 right-4 z-10 p-2 text-white hover:text-gray-300 transition-colors"
        aria-label="Close lightbox"
    >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Previous Button -->
    <button
        x-show="images.length > 1 && currentIndex > 0"
        @click="previous()"
        class="absolute left-4 top-1/2 -translate-y-1/2 z-10 p-3 text-white hover:text-gray-300 transition-colors"
        aria-label="Previous image"
    >
        <x-solar-alt-arrow-left-linear class="w-8 h-8" />
    </button>

    <!-- Image Container -->
    <div class="relative max-w-7xl max-h-[90vh] w-full h-full flex items-center justify-center p-4">
        <img
            :src="currentImage"
            :alt="'Image ' + (currentIndex + 1)"
            class="max-w-full max-h-full object-contain"
            @click.stop
        />
    </div>

    <!-- Next Button -->
    <button
        x-show="images.length > 1 && currentIndex < images.length - 1"
        @click="next()"
        class="absolute right-4 top-1/2 -translate-y-1/2 z-10 p-3 text-white hover:text-gray-300 transition-colors"
        aria-label="Next image"
    >
        <x-solar-alt-arrow-right-linear class="w-8 h-8" />
    </button>

    <!-- Image Counter -->
    <div
        x-show="images.length > 1"
        class="absolute bottom-4 left-1/2 -translate-x-1/2 px-4 py-2 bg-black/60 rounded-full text-white text-sm"
    >
        <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
    </div>
</div>
