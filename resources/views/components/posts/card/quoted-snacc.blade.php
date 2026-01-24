@props(['snacc'])

@can('view', $snacc)
    <div 
        class="relative block mt-3 rounded-xl overflow-hidden hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-colors {{ $snacc->is_ghost ? 'border-2 border-dashed border-gray-300 dark:border-dark-border' : 'border border-gray-200 dark:border-dark-border' }}"
        @if($snacc->heat_tier)
            style="box-shadow: inset 4px 0 0 0 {{ $snacc->heat_tier->color }}, 0 4px 20px -5px {{ $snacc->heat_tier->color }}15;"
        @endif
    >
        <!-- Background Heat Emoji -->
        @if($snacc->heat_tier)
            <div class="absolute right-[-10px] top-[-5px] text-[80px] opacity-[0.03] select-none pointer-events-none rotate-12 z-0"
                 style="color: {{ $snacc->heat_tier->color }}">
                {{ $snacc->heat_tier->emoji }}
            </div>
        @endif

        <!-- Main Card Link -->
        <a href="{{ route('snaccs.show', $snacc) }}" class="absolute inset-0 z-0 select-none" aria-label="View Snacc"></a>

        <div class="px-4 pt-3 relative pointer-events-none z-10">
            <!-- Header (scaled down) -->
            <div class="flex items-center gap-1.5 mb-1.5 text-xs pointer-events-auto">
                @if($snacc->is_ghost)
                    <span class="font-semibold text-gray-900 dark:text-white lowercase">ghost snacc</span>
                @else
                    <a href="{{ route('profile.show', $snacc->user->profile->username) }}" class="flex-shrink-0 relative z-10">
                        <img
                            src="{{ $snacc->user->profile->profile_photo ? Storage::url($snacc->user->profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($snacc->user->profile->username) }}"
                            alt="{{ $snacc->user->profile->username }}"
                            class="w-5 h-5 rounded-full object-cover"
                        >
                    </a>
                    <a href="{{ route('profile.show', $snacc->user->profile->username) }}" class="font-semibold text-gray-900 dark:text-white lowercase hover:underline relative z-10">
                        {{ $snacc->user->profile->username }}
                    </a>
                @endif
                <span class="text-gray-400 dark:text-gray-500">·</span>
                <span class="text-gray-500 dark:text-gray-400 lowercase">
                    {{ $snacc->university->acronym }}
                </span>
                <span class="text-gray-400 dark:text-gray-500">·</span>
                <time class="text-gray-500 dark:text-gray-400 lowercase">
                    {{ $snacc->created_at->diffForHumans(short: true) }}
                </time>
            </div>

            <!-- Content (scaled down and clamped) -->
            <div class="text-sm line-clamp-3 mb-3 pointer-events-none">
                <x-posts.card.content :snacc="$snacc" />
            </div>
        </div>

        <!-- Media -->
        @if($snacc->images->isNotEmpty())
            <div class="px-4 pb-3 relative pointer-events-auto z-10">
                <x-posts.card.image-gallery :images="$snacc->images" />
            </div>
        @elseif($snacc->gif_url)
            <div class="px-4 pb-3 relative pointer-events-none z-10">
                <x-posts.card.gif :url="$snacc->gif_url" />
            </div>
        @endif
    </div>
@else
    <div class="mt-3 p-4 bg-gray-50 dark:bg-dark-bg/50 border border-gray-200 dark:border-dark-border rounded-xl text-center select-none">
        <span class="text-sm text-gray-500 dark:text-gray-400 italic">
            you can't view this content 🙈
        </span>
    </div>
@endcan
