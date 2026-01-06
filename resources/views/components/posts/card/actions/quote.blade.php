@props(['snacc'])

@php
    $quotesCount = $snacc->quotes_count ?? 0;
@endphp

<button
    type="button"
    x-data=""
    @click="
        window.dispatchEvent(new CustomEvent('quote-snacc', {
            detail: {
                slug: '{{ $snacc->slug }}',
                user: {
                    name: '{{ $snacc->user->name }}',
                    username: '{{ $snacc->user->profile->username }}',
                    avatar: '{{ $snacc->user->profile->profile_photo ? Storage::url($snacc->user->profile->profile_photo) : '' }}'
                },
                content: {{ Js::from($snacc->content) }},
                created_at: '{{ $snacc->created_at->diffForHumans(short: true) }}',
                first_image: {{ $snacc->images->isNotEmpty() ? Js::from(Storage::url($snacc->images->first()->image_path)) : 'null' }},
                gif_url: {{ Js::from($snacc->gif_url) }}
            }
        }));
        $dispatch('open-modal', 'create-snacc');
    "
    class="flex items-center gap-1 group text-gray-500 dark:text-gray-400 hover:text-green-500 dark:hover:text-green-400 transition-colors"
>
    <x-solar-square-share-line-linear class="w-5 h-5" />
    @if($quotesCount > 0)
        <span class="text-xs">{{ $quotesCount }}</span>
    @endif
</button>
