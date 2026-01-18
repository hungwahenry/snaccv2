@props(['snacc'])

<div class="flex items-center justify-between gap-2 mb-1">
    <div class="flex items-center gap-1 min-w-0 flex-1">
        <a href="{{ route('profile.show', $snacc->user->profile->username) }}" class="font-semibold text-gray-900 dark:text-white text-sm lowercase truncate hover:underline">
            {{ $snacc->user->profile->username }}
        </a>
        <span class="text-gray-500 dark:text-gray-400 text-sm flex-shrink-0">·</span>
        <span class="text-gray-500 dark:text-gray-400 text-xs flex-shrink-0 lowercase">
            {{ $snacc->university->acronym }}
        </span>
        <span class="text-gray-500 dark:text-gray-400 text-sm flex-shrink-0">·</span>
        <span class="text-gray-500 dark:text-gray-400 text-sm flex-shrink-0 lowercase">
            {{ $snacc->created_at->diffForHumans(short: true) }}
        </span>
        
    </div>

    <!-- Menu Button -->
    <div class="flex-shrink-0">
        <x-posts.menu :snacc="$snacc" />
    </div>
</div>
