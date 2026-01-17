@props(['route', 'sort'])

<div x-data="{ 
        visible: true, 
        lastScroll: 0,
        init() {
            this.lastScroll = window.scrollY;
        },
        updateVisibility() {
            const current = window.scrollY;
            // Hide if scrolling down (>0) AND past 60px
            // Show if scrolling up OR at top
            if (current > this.lastScroll && current > 60) {
                this.visible = false;
            } else {
                this.visible = true;
            }
            this.lastScroll = current;
        }
     }"
     @scroll.window.passive="updateVisibility()"
     :class="visible ? 'translate-y-0' : '-translate-y-full'"
     id="feed-tabs" 
     class="flex items-center gap-6 px-4 py-3 border-b border-gray-200 dark:border-dark-border sticky top-14 lg:top-0 bg-white/80 dark:bg-dark-bg/80 backdrop-blur-md z-30 transition-transform duration-300 transform">
    
    <!-- Trending Tab -->
    <a href="{{ route($route, ['sort' => 'trending']) }}" 
       class="flex items-center gap-2 text-sm font-medium transition-colors {{ $sort === 'trending' ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
        @if($sort === 'trending')
            <x-solar-fire-bold class="w-5 h-5" />
        @else
            <x-solar-fire-linear class="w-5 h-5" />
        @endif
        <span>trending</span>
    </a>

    <!-- Latest Tab -->
    <a href="{{ route($route, ['sort' => 'latest']) }}" 
       class="flex items-center gap-2 text-sm font-medium transition-colors {{ $sort === 'latest' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
        @if($sort === 'latest')
            <x-solar-clock-circle-bold class="w-5 h-5" />
        @else
            <x-solar-clock-circle-linear class="w-5 h-5" />
        @endif
        <span>latest</span>
    </a>
</div>
