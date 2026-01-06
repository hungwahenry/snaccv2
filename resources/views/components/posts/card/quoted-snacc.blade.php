@props(['snacc'])

<div class="mt-3 border border-gray-200 dark:border-dark-border rounded-xl overflow-hidden hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-colors cursor-pointer">
    <div class="px-4 pt-3 pb-0">
        <!-- Header (scaled down) -->
        <div class="flex items-center gap-1.5 mb-1.5 text-xs">
            <img
                src="{{ $snacc->user->profile->profile_photo ? Storage::url($snacc->user->profile->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($snacc->user->name) . '&background=random' }}"
                alt="{{ $snacc->user->name }}"
                class="w-5 h-5 rounded-full object-cover"
            >
            <span class="font-semibold text-gray-900 dark:text-white lowercase">
                {{ $snacc->user->profile->username }}
            </span>
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
        <div class="text-sm line-clamp-3">
            <x-posts.card.content :snacc="$snacc" />
        </div>

        <!-- Media -->
        @if($snacc->images->isNotEmpty())
            <x-posts.card.image-gallery :images="$snacc->images" />
        @elseif($snacc->gif_url)
            <x-posts.card.gif :url="$snacc->gif_url" />
        @endif
    </div>
</div>
