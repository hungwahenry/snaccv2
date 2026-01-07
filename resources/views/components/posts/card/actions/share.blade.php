@props(['snacc'])

@php
    $shareUrl = route('snaccs.show', $snacc);
    $shareTitle = 'Check out this snacc from ' . $snacc->user->profile->username;
@endphp

<button
    type="button"
    x-data=""
    @click="
        if (navigator.share) {
            navigator.share({
                title: '{{ $shareTitle }}',
                url: '{{ $shareUrl }}'
            }).catch((error) => {
                if (error.name !== 'AbortError') {
                    console.error('Error sharing:', error);
                }
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText('{{ $shareUrl }}').then(() => {
                alert('link copied to clipboard!');
            });
        }
    "
    class="flex items-center gap-1 group text-gray-500 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors"
>
    <x-solar-upload-minimalistic-linear class="w-5 h-5" />
</button>
