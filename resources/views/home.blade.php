<x-app-layout>
    <div>
        <!-- Success Message -->
        @if(session('success'))
            <div class="mx-4 my-6 px-4 py-3 bg-primary-50 dark:bg-primary-900/20 border-2 border-primary-200 dark:border-primary-800 rounded-2xl">
                <p class="text-sm text-primary-900 dark:text-primary-100 lowercase">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Feed Tabs -->
        <x-feed-tabs route="home" :sort="$sort" />

        <!-- Snaccs Feed -->
        <div id="feed-container">
            <x-posts.feed-list :snaccs="$snaccs" />
        </div>

        <!-- Infinite Scroll Sentinel -->
        @if($snaccs->hasMorePages())
            <x-infinite-scroll 
                nextUrl="{{ $snaccs->nextPageUrl() }}" 
                container="#feed-container" 
            />
        @endif
    </div>
</x-app-layout>
