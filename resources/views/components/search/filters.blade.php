@props(['query', 'type', 'scope', 'sort'])

<div class="ml-auto px-4" x-data="{ open: false }">
    <button 
        @click="open = !open"
        type="button"
        class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-surface rounded-lg transition-colors">
        <x-solar-tuning-2-linear class="w-5 h-5" />
        <span class="hidden sm:inline">filters</span>
    </button>

    <!-- Filters Dropdown Menu -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute right-4 mt-2 w-48 bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-xl shadow-lg overflow-hidden z-50">
        
        <!-- Scope Filter -->
        <div class="p-2 border-b border-gray-200 dark:border-dark-border">
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase px-2 py-1">scope</p>
            <a href="{{ route('search', ['q' => $query, 'type' => $type, 'scope' => 'global', 'sort' => $sort]) }}"
               class="block px-3 py-2 text-sm rounded-lg {{ $scope === 'global' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg' }}">
                global
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => $type, 'scope' => 'campus', 'sort' => $sort]) }}"
               class="block px-3 py-2 text-sm rounded-lg {{ $scope === 'campus' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg' }}">
                campus only
            </a>
        </div>

        <!-- Sort Filter -->
        <div class="p-2">
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase px-2 py-1">sort by</p>
            <a href="{{ route('search', ['q' => $query, 'type' => $type, 'scope' => $scope, 'sort' => 'relevant']) }}"
               class="block px-3 py-2 text-sm rounded-lg {{ $sort === 'relevant' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg' }}">
                relevant
            </a>
            @if($type === 'posts')
                <a href="{{ route('search', ['q' => $query, 'type' => $type, 'scope' => $scope, 'sort' => 'trending']) }}"
                   class="block px-3 py-2 text-sm rounded-lg {{ $sort === 'trending' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg' }}">
                    trending
                </a>
                <a href="{{ route('search', ['q' => $query, 'type' => $type, 'scope' => $scope, 'sort' => 'latest']) }}"
                   class="block px-3 py-2 text-sm rounded-lg {{ $sort === 'latest' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg' }}">
                    latest
                </a>
                <a href="{{ route('search', ['q' => $query, 'type' => $type, 'scope' => $scope, 'sort' => 'popular']) }}"
                   class="block px-3 py-2 text-sm rounded-lg {{ $sort === 'popular' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg' }}">
                    popular
                </a>
            @else
                <a href="{{ route('search', ['q' => $query, 'type' => $type, 'scope' => $scope, 'sort' => 'popular']) }}"
                   class="block px-3 py-2 text-sm rounded-lg {{ $sort === 'popular' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg' }}">
                    popular
                </a>
            @endif
        </div>
    </div>
</div>
