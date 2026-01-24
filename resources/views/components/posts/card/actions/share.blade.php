@props(['snacc'])

@php
    $shareUrl = route('snaccs.show', $snacc);
    $shareTitle = $snacc->is_ghost 
        ? 'Check out this ghost snacc' 
        : 'Check out this snacc from ' . $snacc->user->profile->username;
@endphp

<button
    type="button"
    x-data=""
    @click="
        const shareData = {
            title: {{ Js::from($shareTitle) }},
            url: {{ Js::from($shareUrl) }}
        };

        if (navigator.share) {
            navigator.share(shareData);
        } else {
            navigator.clipboard.writeText(shareData.url);
            alert('Link copied!');
        }
    "
    class="flex items-center gap-1 group text-gray-500 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors"
>
    <x-solar-upload-minimalistic-linear class="w-5 h-5" />
</button>
