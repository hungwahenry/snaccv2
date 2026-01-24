<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-dark-surface border-t-2 border-gray-200 dark:border-dark-border lg:top-0 lg:bottom-auto lg:w-64 lg:h-screen lg:border-t-0 lg:border-r-2">
    <div class="flex justify-around items-center h-16 lg:flex-col lg:h-full lg:justify-start lg:py-6 lg:px-4 lg:gap-2">
        <!-- Logo - Desktop Only -->
        <a href="{{ route('home') }}" class="hidden lg:flex items-center gap-3 px-4 py-3 mb-4 w-full text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-dark-bg rounded-xl transition-colors">
            <x-application-logo class="h-10 w-auto" />
        </a>

        <!-- Home -->
        <a href="{{ route('home') }}" class="flex items-center justify-center lg:justify-start lg:w-full lg:px-4 lg:py-3 lg:rounded-xl {{ request()->routeIs('home') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors group">
            <div class="relative">
                @if(request()->routeIs('home'))
                    <x-solar-home-smile-bold class="w-7 h-7" />
                @else
                    <x-solar-home-smile-linear class="w-7 h-7" />
                @endif
            </div>
            <span class="hidden lg:block ml-4 text-base {{ request()->routeIs('home') ? 'font-bold' : 'font-medium' }} capitalize">home</span>
        </a>

        <!-- Explore -->
        <a href="{{ route('explore') }}" class="flex items-center justify-center lg:justify-start lg:w-full lg:px-4 lg:py-3 lg:rounded-xl {{ request()->routeIs('explore*') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors group">
            <div class="relative">
                @if(request()->routeIs('explore*'))
                    <x-solar-compass-square-bold class="w-7 h-7" />
                @else
                    <x-solar-compass-square-linear class="w-7 h-7" />
                @endif
            </div>
            <span class="hidden lg:block ml-4 text-base {{ request()->routeIs('explore*') ? 'font-bold' : 'font-medium' }} capitalize">explore</span>
        </a>

        <!-- Search -->
        <a href="{{ route('search') }}" class="flex items-center justify-center lg:justify-start lg:w-full lg:px-4 lg:py-3 lg:rounded-xl {{ request()->routeIs('search') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors group">
            <div class="relative">
                @if(request()->routeIs('search'))
                    <x-solar-magnifer-bold class="w-7 h-7" />
                @else
                    <x-solar-magnifer-linear class="w-7 h-7" />
                @endif
            </div>
            <span class="hidden lg:block ml-4 text-base {{ request()->routeIs('search') ? 'font-bold' : 'font-medium' }} capitalize">search</span>
        </a>

        <!-- Notifications -->
        <a href="{{ route('notifications.index') }}" class="flex items-center justify-center lg:justify-start lg:w-full lg:px-4 lg:py-3 lg:rounded-xl {{ request()->routeIs('notifications*') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors group relative">
            <div class="relative">
                @if(request()->routeIs('notifications*'))
                    <x-solar-hand-shake-bold class="w-7 h-7" />
                @else
                    <x-solar-hand-shake-linear class="w-7 h-7" />
                @endif
                
                @if(auth()->user()->unreadNotifications()->exists())
                    <span class="absolute top-0 right-0 lg:top-0 lg:right-0 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-dark-surface"></span>
                @endif
            </div>
            <span class="hidden lg:block ml-4 text-base {{ request()->routeIs('notifications*') ? 'font-bold' : 'font-medium' }} capitalize">notifications</span>
        </a>

        <!-- Profile -->
        <a href="{{ route('profile.show', auth()->user()->profile->username) }}" class="flex items-center justify-center lg:justify-start lg:w-full lg:px-4 lg:py-3 lg:rounded-xl {{ request()->routeIs('profile.show') && request()->route('username') === auth()->user()->profile->username ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors group">
            <img 
                src="{{ auth()->user()->profile?->profile_photo ? Storage::url(auth()->user()->profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode(auth()->user()->profile->username) }}" 
                alt="Profile" 
                class="w-7 h-7 rounded-full object-cover border-2 {{ request()->routeIs('profile.show') && request()->route('username') === auth()->user()->profile->username ? 'border-primary-500' : 'border-transparent' }}" 
            />
            <span class="hidden lg:block ml-4 text-base {{ request()->routeIs('profile.show') && request()->route('username') === auth()->user()->profile->username ? 'font-bold' : 'font-medium' }} capitalize">profile</span>
        </a>

        <!-- Logout - Desktop Only, at bottom -->
        <form method="POST" action="{{ route('logout') }}" class="hidden lg:block lg:mt-auto w-full">
            @csrf
            <button type="submit" class="flex items-center justify-start w-full px-4 py-3 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg hover:text-primary-500 dark:hover:text-primary-400 transition-colors group">
                <x-solar-logout-2-linear class="w-7 h-7" />
                <span class="ml-4 text-base font-medium capitalize">logout</span>
            </button>
        </form>
    </div>
</nav>
