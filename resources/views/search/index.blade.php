<x-app-layout>
    <div>
        <!-- Search Header -->
        <div class="sticky top-14 lg:top-0 z-30 bg-white/80 dark:bg-dark-bg/80 backdrop-blur-md border-b border-gray-200 dark:border-dark-border">
            <div class="px-4 py-4">
                <!-- Search Input -->
                <form method="GET" action="{{ route('search') }}" class="relative">
                    <input 
                        type="text" 
                        name="q"
                        value="{{ $query }}"
                        placeholder="search snaccs and users..."
                        autocomplete="off"
                        class="w-full px-4 py-3 pl-12 bg-gray-50 dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white"
                        autofocus
                    >
                    <x-solar-magnifer-linear class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                    
                    <!-- Hidden fields to preserve filters -->
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="scope" value="{{ $scope }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                </form>
            </div>

            @if($query)
                <!-- Search Tabs -->
                <x-search.tabs :query="$query" :type="$type" :scope="$scope" :sort="$sort" />
            @endif
        </div>

        <!-- Search Results -->
        @if(empty($query))
            <!-- Empty State: No Search Query -->
            <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                <x-solar-magnifer-linear class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">search snacc</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md">
                    find posts, users, and vibetags across campus or the entire snacc community
                </p>
            </div>
        @elseif(!$hasResults)
            <!-- Empty State: No Results -->
            <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                <x-solar-sleeping-square-bold class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">no results found</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md">
                    damn, couldn't find anything for "<strong>{{ $query }}</strong>". maybe search something else?
                </p>
            </div>
        @else
            <!-- Results Container -->
            <div id="search-results">
                @if($type === 'users')
                    <x-search.users-list :results="$results" />
                @else
                    <x-posts.feed-list :snaccs="$results" />
                @endif
            </div>

            <!-- Infinite Scroll Sentinel -->
            @if($results->hasMorePages())
                <x-infinite-scroll 
                    nextUrl="{{ $results->nextPageUrl() }}" 
                    container="#search-results" 
                />
            @endif
        @endif
    </div>
</x-app-layout>
