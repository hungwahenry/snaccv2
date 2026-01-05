@props(['snacc'])

<div class="mt-3 border border-gray-200 dark:border-dark-border rounded-xl p-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-colors">
    <!-- Quoted Post Header -->
    <div class="flex items-center gap-1.5 mb-1.5">
        <img
            src="{{ $snacc->user->profile->profile_photo ? Storage::url($snacc->user->profile->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($snacc->user->name) . '&background=random' }}"
            alt="{{ $snacc->user->name }}"
            class="w-5 h-5 rounded-full object-cover"
        >
        <span class="font-semibold text-xs text-gray-900 dark:text-white lowercase">
            {{ $snacc->user->profile->username }}
        </span>
        <span class="text-gray-400 dark:text-gray-500 text-xs">·</span>
        <time class="text-xs text-gray-500 dark:text-gray-400 lowercase">
            {{ $snacc->created_at->diffForHumans(short: true) }}
        </time>
    </div>

    <!-- Quoted Post Content -->
    @if($snacc->content)
        <div class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">
            {{ $snacc->content }}
        </div>
    @endif

    <!-- Quoted Post Media Preview -->
    @if($snacc->images->isNotEmpty())
        <div class="mt-2">
            <img
                src="{{ Storage::url($snacc->images->first()->image_path) }}"
                alt="Quoted post"
                class="w-full h-32 object-cover rounded-lg"
            >
        </div>
    @elseif($snacc->gif_url)
        <div class="mt-2">
            <img
                src="{{ $snacc->gif_url }}"
                alt="Quoted GIF"
                class="w-full h-32 object-cover rounded-lg"
            >
        </div>
    @endif
</div>
