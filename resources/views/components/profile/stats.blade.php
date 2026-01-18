@props(['user', 'profile'])

<div 
    x-data="{ 
        postsCount: {{ $user->posts_count ?? 0 }},
        addedByCount: {{ $user->added_by_count ?? 0 }},
        addsCount: {{ $user->adds_count ?? 0 }}
    }"
    @user-stats-updated.window="if ($event.detail.userId === {{ $user->id }}) { addedByCount = $event.detail.addedByCount; }"
    class="flex items-center gap-6 mt-4 text-sm"
>
    <div class="flex flex-col items-center">
        <span class="font-bold text-gray-900 dark:text-white" x-text="postsCount">
            {{ $user->posts_count ?? 0 }}
        </span>
        <span class="text-gray-500 text-xs uppercase tracking-wide">Posts</span>
    </div>

    <!-- Cred Score -->
    <div class="flex flex-col items-center">
        <span class="font-bold text-gray-900 dark:text-white">
            {{ number_format($user->cred_score ?? 0) }}
        </span>
        <span class="text-gray-500 text-xs uppercase tracking-wide">
            {{ $user->credTier->name ?? 'Unranked' }}
        </span>
    </div>

    <!-- Added By (Followers equivalent) -->
    <div class="flex flex-col items-center">
        <span class="font-bold text-gray-900 dark:text-white" x-text="addedByCount">
            {{ $user->added_by_count ?? 0 }}
        </span>
        <span class="text-gray-500 text-xs uppercase tracking-wide">Added By</span>
    </div>

    <!-- Adds (Following equivalent) -->
    <div class="flex flex-col items-center">
        <span class="font-bold text-gray-900 dark:text-white" x-text="addsCount">
            {{ $user->adds_count ?? 0 }}
        </span>
        <span class="text-gray-500 text-xs uppercase tracking-wide">Adds</span>
    </div>
</div>
