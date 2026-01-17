@props(['snacc'])

<div class="flex items-center justify-between gap-2 mb-1">
    <div class="flex items-center gap-1 min-w-0 flex-1">
        <span class="font-semibold text-gray-900 dark:text-white text-sm lowercase truncate">
            {{ $snacc->user->profile->username }}
        </span>
        <span class="text-gray-500 dark:text-gray-400 text-sm flex-shrink-0">·</span>
        <span class="text-gray-500 dark:text-gray-400 text-xs flex-shrink-0 lowercase">
            {{ $snacc->university->acronym }}
        </span>
        <span class="text-gray-500 dark:text-gray-400 text-sm flex-shrink-0">·</span>
        <span class="text-gray-500 dark:text-gray-400 text-sm flex-shrink-0 lowercase">
            {{ $snacc->created_at->diffForHumans(short: true) }}
        </span>
        
        @if($snacc->heat_tier)
            <span class="text-gray-500 dark:text-gray-400 text-sm flex-shrink-0">·</span>
            <span class="group flex items-center gap-1 flex-shrink-0 cursor-help" 
                  title="{{ $snacc->heat_tier->name }}! {{ number_format($snacc->heat_score) }} heat"
                  style="color: {{ $snacc->heat_tier->color }}">
                <span>{{ $snacc->heat_tier->emoji }}</span>
                <span class="text-xs font-semibold hidden group-hover:inline transition-all">{{ $snacc->heat_tier->name }}</span>
            </span>
        @endif
    </div>

    <!-- Menu Button -->
    <div class="flex-shrink-0">
        <x-posts.menu :snacc="$snacc" />
    </div>
</div>
