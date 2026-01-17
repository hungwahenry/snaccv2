<x-app-layout>
    <div class="max-w-2xl mx-auto w-full">
        <!-- Profile Header -->
        <div class="px-4 py-8 bg-white dark:bg-dark-bg border-b border-gray-200 dark:border-dark-border">
            <div class="flex items-start justify-between">
                <div class="flex gap-4">
                    <!-- Avatar -->
                    <img 
                        src="{{ $profile->profile_photo ? Storage::url($profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($user->name) }}" 
                        alt="{{ $user->name }}" 
                        class="w-20 h-20 rounded-full object-cover border-2 border-primary-500" 
                    />
                    
                    <div class="flex flex-col justify-center">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                            {{ $user->name }}
                        </h1>
                        <p class="text-sm text-gray-500 font-medium lowercase">
                            {{ '@' . $profile->username }}
                        </p>
                        
                        <!-- University Badge -->
                        <div class="flex items-center gap-2 mt-2">
                             <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400 uppercase tracking-wide">
                                {{ $profile->university->acronym ?? 'Unknown' }}
                             </span>
                        </div>
                        
                        <!-- Bio -->
                        @if($profile->bio)
                             <p class="mt-3 text-sm text-gray-700 dark:text-gray-300 leading-relaxed max-w-md">
                                {{ $profile->bio }}
                             </p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div>
                    @if($isOwnProfile)
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white dark:bg-dark-surface border border-gray-300 dark:border-dark-border rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-dark-bg transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg disabled:opacity-25 ease-in-out duration-150">
                            Edit
                        </a>
                    @else
                        @if($isAdded)
                            <!-- Remove Button -->
                            <form action="{{ route('users.remove', $user) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-dark-surface border border-gray-300 dark:border-dark-border rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-800 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg disabled:opacity-25 ease-in-out duration-150">
                                    Added
                                </button>
                            </form>
                        @else
                            <!-- Add Button -->
                             <form action="{{ route('users.add', $user) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg transition ease-in-out duration-150 shadow-lg shadow-primary-500/20">
                                    Add User
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Feed Content -->
        <div class="mt-0">
             <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-border">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Posts</h3>
             </div>

             <div id="user-feed-container">
                 <x-posts.feed-list :snaccs="$snaccs" />
             </div>

             <!-- Infinite Scroll -->
             @if($snaccs->hasMorePages())
                <x-infinite-scroll 
                    nextUrl="{{ $snaccs->nextPageUrl() }}" 
                    container="#user-feed-container" 
                />
             @endif
        </div>
    </div>
</x-app-layout>
