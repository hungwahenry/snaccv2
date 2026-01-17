<x-app-layout>
    <div>
        <!-- Success Message -->
        @if(session('success'))
            <div class="mx-4 my-6 px-4 py-3 bg-primary-50 dark:bg-primary-900/20 border-2 border-primary-200 dark:border-primary-800 rounded-2xl">
                <p class="text-sm text-primary-900 dark:text-primary-100 lowercase">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Posts Feed -->
        @forelse($snaccs as $snacc)
            <x-posts.card :snacc="$snacc" />
        @empty
            <div class="text-center py-12 px-4">
                <x-solar-document-text-linear class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white lowercase mb-2">no snaccs yet</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 lowercase">be the first to post a snacc!</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($snaccs->hasPages())
            <div class="px-4 py-6">
                {{ $snaccs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
