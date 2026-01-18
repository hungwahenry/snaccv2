@props(['snacc'])

<article 
    class="border-b border-gray-200 dark:border-dark-border hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-all duration-300"
    @if($snacc->heat_tier)
        style="box-shadow: inset 4px 0 0 0 {{ $snacc->heat_tier->color }}, inset 24px 0 20px -10px {{ $snacc->heat_tier->color }}20, 0 4px 20px -5px {{ $snacc->heat_tier->color }}15;"
    @endif
>
    <div class="px-4 py-3">
        <div class="flex gap-3">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <img
                    src="{{ $snacc->user->profile->profile_photo ? Storage::url($snacc->user->profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($snacc->user->profile->username) }}"
                    alt="{{ $snacc->user->profile->username }}"
                    class="w-10 h-10 rounded-full object-cover"
                >
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <!-- Header -->
                <x-posts.card.header :snacc="$snacc" />

                <!-- Post Content -->
                <x-posts.card.content :snacc="$snacc" />

                <!-- Media (Images or GIF) -->
                @if($snacc->images->isNotEmpty())
                    <x-posts.card.image-gallery :images="$snacc->images" />
                @elseif($snacc->gif_url)
                    <x-posts.card.gif :url="$snacc->gif_url" />
                @endif

                <!-- Quoted Snacc -->
                @if($snacc->quotedSnacc)
                    <x-posts.card.quoted-snacc :snacc="$snacc->quotedSnacc" />
                @endif

                <!-- Actions -->
                <x-posts.card.actions :snacc="$snacc" />
            </div>
        </div>
    </div>
</article>
