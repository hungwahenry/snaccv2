@forelse($snaccs as $snacc)
    <x-posts.card :snacc="$snacc" />
@empty
    @if($snaccs->currentPage() === 1)
        <div class="text-center py-12 px-4">
            <x-solar-document-text-linear class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white lowercase mb-2">no snaccs found</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 lowercase">be the first to post!</p>
        </div>
    @endif
@endforelse
