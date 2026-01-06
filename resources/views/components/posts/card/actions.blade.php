@props(['snacc'])

<div class="flex items-center gap-12 mt-3">
    <x-posts.card.actions.like :snacc="$snacc" />
    <x-posts.card.actions.comment :snacc="$snacc" />
    <x-posts.card.actions.quote :snacc="$snacc" />
    <x-posts.card.actions.share :snacc="$snacc" />
</div>
