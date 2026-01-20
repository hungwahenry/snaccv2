@props(['rendered'])

<a href="{{ $rendered['url'] }}" 
   class="group block border-b border-gray-200 dark:border-dark-border hover:bg-gray-50 dark:hover:bg-dark-elem/50 transition-colors duration-200"
>
    <div class="px-4 py-4 flex gap-4 {{ !$rendered['is_read'] ? 'bg-primary-50/10 dark:bg-primary-900/10' : '' }}">
        <!-- Left: Actor Avatar(s) -->
        <div class="flex-shrink-0 relative">
            @if($rendered['is_grouped'])
                {{-- Avatar Stack for grouped notifications --}}
                <div class="flex -space-x-2">
                    @foreach(array_slice($rendered['actors'], 0, 3) as $index => $actor)
                        <img 
                            src="{{ $actor['avatar'] ? Storage::url($actor['avatar']) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($actor['name']) }}" 
                            alt="{{ $actor['name'] }}" 
                            class="w-12 h-12 rounded-full object-cover border-2 border-white dark:border-dark-bg {{ $index > 0 ? 'relative' : '' }}"
                            style="z-index: {{ 3 - $index }}"
                        >
                    @endforeach
                    @if($rendered['total_count'] > 3)
                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 border-2 border-white dark:border-dark-bg flex items-center justify-center text-xs font-semibold text-gray-500 dark:text-gray-400">
                            +{{ $rendered['total_count'] - 3 }}
                        </div>
                    @endif
                </div>
            @else
                {{-- Single avatar --}}
                @php $firstActor = $rendered['actors'][0] ?? ['name' => 'System', 'avatar' => null]; @endphp
                <img 
                    src="{{ $firstActor['avatar'] ? Storage::url($firstActor['avatar']) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($firstActor['name']) }}" 
                    alt="{{ $firstActor['name'] }}" 
                    class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-dark-border"
                >
            @endif
            
            <!-- Type Icon Badge -->
            <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center text-xs border-2 border-white dark:border-dark-bg bg-primary-500 text-white">
                <x-dynamic-component :component="$rendered['icon']" class="w-3.5 h-3.5" />
            </div>
        </div>

        <!-- Center: Content -->
        <div class="flex-1 min-w-0 flex flex-col justify-center">
            <p class="text-[15px] text-gray-900 dark:text-gray-100 leading-snug">
                {{ $rendered['message'] }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ $rendered['date'] }}
            </p>
        </div>

        <!-- Right: Unread Indicator -->
        @if(!$rendered['is_read'])
            <div class="flex-shrink-0 self-center">
                <div class="w-2.5 h-2.5 bg-primary-500 rounded-full"></div>
            </div>
        @endif
    </div>
</a>
