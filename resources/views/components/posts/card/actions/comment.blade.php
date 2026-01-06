@props(['snacc'])

@php
    $commentsCount = $snacc->comments_count ?? 0;
@endphp

<button class="flex items-center gap-1 group text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
    <x-solar-chat-round-line-linear class="w-5 h-5" />
    @if($commentsCount > 0)
        <span class="text-xs">{{ $commentsCount }}</span>
    @endif
</button>
