<x-app-layout>
    <div>
        <!-- Feed Tabs -->
        <x-feed-tabs route="explore" :sort="$sort" />

        <!-- Posts Feed -->
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
