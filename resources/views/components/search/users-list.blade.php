@props(['results'])

<div>
    @foreach($results as $profile)
        <div class="border-b border-gray-200 dark:border-dark-border hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-colors px-4 py-4">
            <div class="flex items-start gap-3 sm:gap-4">
                <!-- Profile Photo -->
                <a href="{{ route('profile.show', $profile->username) }}" class="flex-shrink-0">
                    <img 
                        src="{{ $profile->profile_photo ? Storage::url($profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($profile->username) }}" 
                        alt="{{ $profile->username }}" 
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover"
                    />
                </a>

                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <a href="{{ route('profile.show', $profile->username) }}" class="group">
                            <h3 class="font-bold text-sm sm:text-base group-hover:opacity-80 transition-colors leading-tight"
                                style="color: {{ $profile->user->credTier->color ?? 'currentColor' }}">
                                {{ '@' . $profile->username }}
                                @if($profile->user->credTier)
                                    <span class="text-sm sm:text-base">{{ $profile->user->credTier->emoji }}</span>
                                @endif
                            </h3>
                        </a>

                        <!-- Add Button - Desktop -->
                        @auth
                            @if(auth()->id() !== $profile->user_id)
                                <div class="hidden sm:block flex-shrink-0">
                                    <x-profile.add-button 
                                        :user="$profile->user" 
                                        :isAdded="$profile->user->isAddedBy(auth()->user())" 
                                    />
                                </div>
                            @endif
                        @endauth
                    </div>
                    
                    @if($profile->bio)
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2 leading-relaxed">
                            {{ $profile->bio }}
                        </p>
                    @endif

                    <div class="flex items-center flex-wrap gap-2 sm:gap-3 text-xs text-gray-500 dark:text-gray-400">
                        @if($profile->university)
                            <span class="flex items-center gap-1">
                                <x-solar-buildings-2-linear class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                                <span class="truncate max-w-[120px] sm:max-w-none">{{ $profile->university->acronym ?? $profile->university->name }}</span>
                            </span>
                        @endif
                        
                        @if($profile->user)
                            <span class="flex items-center gap-1">
                                <x-solar-star-linear class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                                {{ number_format($profile->user->cred_score) }} cred
                            </span>
                            <span class="flex items-center gap-1">
                                <x-solar-users-group-rounded-linear class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                                {{ number_format($profile->user->added_by_count) }} adds
                            </span>
                        @endif
                    </div>

                    <!-- Add Button - Mobile (Full Width) -->
                    @auth
                        @if(auth()->id() !== $profile->user_id)
                            <div class="sm:hidden mt-3">
                                <x-profile.add-button 
                                    :user="$profile->user" 
                                    :isAdded="$profile->user->isAddedBy(auth()->user())" 
                                />
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @endforeach
</div>
