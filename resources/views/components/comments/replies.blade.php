@props(['comment'])

@php
    $repliesCount = $comment->replies_count ?? 0;
    $visibleReplies = $comment->replies ?? collect();
    $hasMoreReplies = $repliesCount > $visibleReplies->count();
@endphp

<div x-data="{ showAllReplies: false }" class="mt-3">
    <!-- Show first few replies -->
    @if($visibleReplies->isNotEmpty())
        <div class="space-y-3">
            @foreach($visibleReplies as $reply)
                <x-comments.reply :comment="$reply" />
            @endforeach
        </div>
    @endif

    <!-- View More Replies Button -->
    @if($hasMoreReplies)
        <button
            type="button"
            @click="showAllReplies = !showAllReplies"
            class="mt-2 text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 lowercase"
        >
            <span x-show="!showAllReplies">view {{ $repliesCount - $visibleReplies->count() }} more {{ $repliesCount - $visibleReplies->count() === 1 ? 'reply' : 'replies' }}</span>
            <span x-show="showAllReplies" x-cloak>hide replies</span>
        </button>

        <!-- Load more replies content -->
        <div x-show="showAllReplies" x-cloak class="mt-3 space-y-3">
        </div>
    @endif
</div>
