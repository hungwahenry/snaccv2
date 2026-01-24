@props(['route', 'sort'])

<div x-data="{ 
        visible: true, 
        lastScroll: 0,
        scrolled: false,
        init() {
            this.lastScroll = window.scrollY;
            window.addEventListener('scroll', () => {
                this.updateVisibility();
                this.scrolled = (window.scrollY > 10);
            });
        },
        updateVisibility() {
            const current = window.scrollY;
            if (current > this.lastScroll && current > 60) {
                this.visible = false;
            } else {
                this.visible = true;
            }
            this.lastScroll = current;
        }
     }"
     :class="[
        visible ? 'translate-y-0' : '-translate-y-full',
        scrolled ? 'bg-white/80 dark:bg-dark-bg/80 backdrop-blur-md border-b border-gray-200 dark:border-dark-border' : 'bg-transparent border-transparent'
     ]"
     id="feed-tabs" 
     class="flex items-center gap-6 px-4 py-3 sticky top-14 lg:top-0 z-30 transition-all duration-300 transform">
    
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

    <!-- Added Tab -->
    <a href="{{ route($route, ['sort' => 'added']) }}" 
       class="flex items-center gap-2 text-sm font-medium transition-colors {{ $sort === 'added' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
        @if($sort === 'added')
            <x-solar-heart-bold class="w-5 h-5" />
        @else
            <x-solar-heart-linear class="w-5 h-5" />
        @endif
        <span>added</span>
    </a>
</div>
