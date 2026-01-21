@props(['query', 'type', 'scope', 'sort'])

<div class="flex items-center gap-4 border-b border-gray-200 dark:border-dark-border">
    <!-- Type Tabs -->
    <div class="flex gap-6 px-4">
        <!-- Posts Tab -->
        <a href="{{ route('search', ['q' => $query, 'type' => 'posts', 'scope' => $scope, 'sort' => $sort]) }}" 
           class="flex items-center gap-2 py-3 text-sm font-medium border-b-2 transition-colors {{ $type === 'posts' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            <x-solar-document-text-linear class="w-5 h-5" />
            <span>posts</span>
        </a>

        <!-- Users Tab -->
        <a href="{{ route('search', ['q' => $query, 'type' => 'users', 'scope' => $scope, 'sort' => $sort]) }}" 
           class="flex items-center gap-2 py-3 text-sm font-medium border-b-2 transition-colors {{ $type === 'users' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            <x-solar-users-group-rounded-linear class="w-5 h-5" />
            <span>users</span>
        </a>
    </div>

    <!-- Filters Dropdown -->
    <x-search.filters :query="$query" :type="$type" :scope="$scope" :sort="$sort" />
</div>
