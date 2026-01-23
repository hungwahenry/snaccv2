<div class="mb-6 px-6">
    <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">who to add</h3>

    @if($suggestedUsers->isNotEmpty())
        <div class="space-y-4">
            @foreach($suggestedUsers as $user)
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('profile.show', $user->profile->username) }}" class="flex items-center gap-3 min-w-0 group">
                        <img 
                            src="{{ $user->profile->profile_photo ? Storage::url($user->profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($user->profile->username) }}" 
                            alt="{{ $user->profile->username }}" 
                            class="w-8 h-8 rounded-full object-cover ring-2 ring-transparent group-hover:ring-primary-500 transition-all"
                        >
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-primary-500 transition-colors">
                                {{ $user->profile->username }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ number_format($user->cred_score) }} cred
                            </p>
                        </div>
                    </a>
                    
                    <!-- Simplified Add Button -->
                    <x-profile.add-button :user="$user" :is-added="false" />
                </div>
            @endforeach
        </div>
    @else
        <p class="text-xs text-gray-400 dark:text-gray-500 italic">no suggestions</p>
    @endif
</div>