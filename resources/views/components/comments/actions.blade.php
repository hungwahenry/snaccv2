@props(['comment'])

<div class="flex items-center gap-8 mt-2">
    <x-comments.actions.like :comment="$comment" />
    <x-comments.actions.reply :comment="$comment" />
</div>
