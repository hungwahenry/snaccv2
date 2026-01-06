@props(['comment'])

@php
    $isLiked = auth()->check() && $comment->isLikedBy(auth()->user());
    $likesCount = $comment->likes_count ?? 0;
@endphp

<button
    type="button"
    x-data="{
        isLiked: {{ $isLiked ? 'true' : 'false' }},
        likesCount: {{ $likesCount }},
        async toggleLike() {
            // Optimistic update
            const previousLiked = this.isLiked;
            const previousCount = this.likesCount;

            this.isLiked = !this.isLiked;
            this.likesCount = this.isLiked ? this.likesCount + 1 : this.likesCount - 1;

            try {
                const response = await fetch('{{ route('comments.like.toggle', $comment) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.isLiked = data.is_liked;
                    this.likesCount = data.likes_count;
                } else {
                    this.isLiked = previousLiked;
                    this.likesCount = previousCount;
                }
            } catch (error) {
                console.error('Error toggling like:', error);
                this.isLiked = previousLiked;
                this.likesCount = previousCount;
            }
        }
    }"
    @click="toggleLike()"
    :class="isLiked ? 'text-red-500' : 'text-gray-500 dark:text-gray-400'"
    class="flex items-center gap-1 hover:text-red-500 dark:hover:text-red-400 transition-colors text-xs"
>
    <span x-show="isLiked">
        <x-solar-heart-bold class="w-4 h-4" />
    </span>
    <span x-show="!isLiked">
        <x-solar-heart-linear class="w-4 h-4" />
    </span>
    <span x-show="likesCount > 0" x-text="likesCount"></span>
</button>
