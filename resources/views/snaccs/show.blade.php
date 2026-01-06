<x-app-layout :hideNavigation="true">
    <div class="max-w-2xl mx-auto pb-24">
        <!-- Single Snacc Post -->
        <x-posts.card :snacc="$snacc" />

        <!-- Comments Section -->
        <div class="mt-4">
            @forelse($comments as $comment)
                <x-comments.card :comment="$comment" />
            @empty
                <div class="text-center py-12 px-4">
                    <x-solar-chat-round-line-linear class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white lowercase mb-2">no comments yet</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 lowercase">be the first to comment!</p>
                </div>
            @endforelse

            <!-- Load More Comments -->
            @if($comments->hasMorePages())
                <div class="px-4 py-6">
                    <a href="{{ $comments->nextPageUrl() }}" class="block text-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 lowercase">
                        view more comments
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Fixed Comment Input at Bottom -->
    <x-comments.input :snacc="$snacc" />
</x-app-layout>
