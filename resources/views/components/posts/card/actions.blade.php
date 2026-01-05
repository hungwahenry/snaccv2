@props(['snacc'])

@php
    $isLiked = auth()->check() && $snacc->isLikedBy(auth()->user());
    $likesCount = $snacc->likes_count ?? 0;
    $commentsCount = $snacc->comments_count ?? 0;
    $quotesCount = $snacc->quotes_count ?? 0;
@endphp

<div class="flex items-center gap-12 mt-3">
    <!-- Like Button -->
    <button class="flex items-center gap-1 group {{ $isLiked ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-red-500 dark:hover:text-red-400 transition-colors">
        @if($isLiked)
            <x-solar-heart-bold class="w-5 h-5" />
        @else
            <x-solar-heart-linear class="w-5 h-5" />
        @endif
        @if($likesCount > 0)
            <span class="text-xs">{{ $likesCount }}</span>
        @endif
    </button>

    <!-- Comment Button -->
    <button class="flex items-center gap-1 group text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
        <x-solar-chat-round-line-linear class="w-5 h-5" />
        @if($commentsCount > 0)
            <span class="text-xs">{{ $commentsCount }}</span>
        @endif
    </button>

    <!-- Quote Button -->
    <button class="flex items-center gap-1 group text-gray-500 dark:text-gray-400 hover:text-green-500 dark:hover:text-green-400 transition-colors">
        <x-solar-square-share-line-linear class="w-5 h-5" />
        @if($quotesCount > 0)
            <span class="text-xs">{{ $quotesCount }}</span>
        @endif
    </button>

    <!-- Share Button -->
    <button class="flex items-center gap-1 group text-gray-500 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
        <x-solar-upload-minimalistic-linear class="w-5 h-5" />
    </button>
</div>
