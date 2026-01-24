@props(['snacc'])

@php
    $isLiked = auth()->check() && $snacc->isLikedBy(auth()->user());
    $likesCount = $snacc->likes_count ?? 0;
@endphp

<button
    type="button"
    x-data="{
        isLiked: {{ $isLiked ? 'true' : 'false' }},
        likesCount: {{ $likesCount }},
        isLoading: false,
        isAnimating: false,
        async toggleLike() {
            // Optimistic update
            const previousLiked = this.isLiked;
            const previousCount = this.likesCount;

            this.isLiked = !this.isLiked;
            this.likesCount = this.isLiked ? this.likesCount + 1 : this.likesCount - 1;

            // Trigger animation if liking
            if (this.isLiked) {
                this.isAnimating = true;
                setTimeout(() => this.isAnimating = false, 300);
            }

            try {
                const response = await fetch('{{ route('snaccs.like.toggle', $snacc) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update with server response
                    this.isLiked = data.is_liked;
                    this.likesCount = data.likes_count;
                } else {
                    // Revert on error
                    this.isLiked = previousLiked;
                    this.likesCount = previousCount;
                }
            } catch (error) {
                console.error('Error toggling like:', error);
                // Revert on error
                this.isLiked = previousLiked;
                this.likesCount = previousCount;
            }
        }
    }"
    @click="toggleLike()"
    :class="isLiked ? 'text-red-500' : 'text-gray-500 dark:text-gray-400'"
    class="flex items-center gap-1 group hover:text-red-500 dark:hover:text-red-400 transition-colors"
>
    <!-- Icon Container with Animation -->
    <div 
        class="relative flex items-center justify-center transition-transform duration-300 ease-[cubic-bezier(0.175,0.885,0.32,1.275)]"
        :class="isAnimating ? 'scale-150' : 'scale-100'"
    >
        <!-- Evaporating Ghost Icon -->
        <span 
            x-show="isAnimating"
            class="absolute inset-0 text-red-500 pointer-events-none"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-100 scale-100 transform translate-y-0"
            x-transition:enter-end="opacity-0 scale-[2] -translate-y-12 transform"
        >
            <x-solar-fire-bold class="w-5 h-5" />
        </span>

        <span x-show="isLiked" x-cloak>
            <x-solar-fire-bold class="w-5 h-5" />
        </span>
        <span x-show="!isLiked" x-cloak>
            <x-solar-fire-linear class="w-5 h-5" />
        </span>
    </div>
    
    <span x-show="likesCount > 0" class="text-xs" x-text="likesCount"></span>
</button>
