@props(['user', 'profile', 'isOwnProfile', 'isAdded'])

<div class="px-4 py-8 bg-white dark:bg-dark-bg border-b border-gray-200 dark:border-dark-border relative"
     style="background-image: radial-gradient(circle at 50% 0%, {{ $user->credTier->color ?? '#ffffff' }}15 0%, transparent 70%);">
    
    <!-- Profile Menu (Report etc) -->
    <div class="absolute top-4 right-4">
        <x-profile.menu :user="$user" />
    </div>

    <div class="flex flex-col items-center text-center gap-4">
        <!-- 1. Profile Photo -->
        <img 
            src="{{ $profile->profile_photo ? Storage::url($profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($profile->username) }}" 
            alt="{{ $profile->username }}" 
            class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-dark-bg bg-gray-50 dark:bg-dark-surface" 
        />

        <!-- 2. Username (Colored + Emoji) -->
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-bold leading-tight lowercase flex items-center gap-1"
                style="color: {{ $user->credTier->color ?? 'currentColor' }}">
                {{ '@' . $profile->username }}
                @if($user->credTier)
                    <span class="text-2xl">{{ $user->credTier->emoji }}</span>
                @endif
            </h1>
        </div>
        
        <!-- 3. University Acronym -->
        <div class="text-sm font-bold text-gray-500 uppercase tracking-wide">
            {{ $profile->university->acronym ?? 'Unknown' }}
            @if($profile->graduation_year)
                <span class="font-medium text-gray-400">'{{ substr($profile->graduation_year, -2) }}</span>
            @endif
        </div>

        <!-- 4. Bio -->
        @if($profile->bio)
             <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed max-w-sm">
                {{ $profile->bio }}
             </p>
        @endif

        <!-- 5. Add / Edit Button -->
        <div class="mt-2">
            @if($isOwnProfile)
                <x-profile.edit-button />
            @else
                <x-profile.add-button :user="$user" :isAdded="$isAdded" />
            @endif
        </div>

        <!-- Stats (Kept at bottom) -->
        <x-profile.stats :user="$user" :profile="$profile" />
    </div>
</div>
