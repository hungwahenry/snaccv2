<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-dark-surface border-t-2 border-gray-200 dark:border-dark-border lg:top-0 lg:bottom-auto lg:w-20 lg:h-screen lg:border-t-0 lg:border-r-2">
    <div class="flex justify-around items-center h-16 lg:flex-col lg:h-full lg:justify-start lg:py-6 lg:gap-6">
        <!-- Logo - Desktop Only -->
        <a href="{{ route('home') }}" class="hidden lg:flex items-center justify-center w-12 h-12 mb-4">
            <x-application-logo class="w-10 h-10 fill-current text-primary-500" />
        </a>

        <!-- Home -->
        <a href="{{ route('home') }}" class="flex items-center justify-center lg:w-12 lg:h-12 {{ request()->routeIs('home') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors">
            @if(request()->routeIs('home'))
                <x-solar-home-2-bold class="w-6 h-6" />
            @else
                <x-solar-home-2-linear class="w-6 h-6" />
            @endif
        </a>

        <!-- Explore -->
        <a href="#" class="flex items-center justify-center lg:w-12 lg:h-12 text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
            <x-solar-compass-linear class="w-6 h-6" />
        </a>

        <!-- Create Post -->
        <button
            type="button"
            x-data=""
            @click="$dispatch('open-modal', 'create-snacc')"
            class="flex items-center justify-center w-12 h-12 lg:w-12 lg:h-12 bg-primary-500 hover:bg-primary-600 text-white rounded-full transition-all"
        >
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>

        <!-- Notifications -->
        <a href="#" class="flex items-center justify-center lg:w-12 lg:h-12 text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors relative">
            <x-solar-bell-linear class="w-6 h-6" />
            <!-- Notification badge (future use) -->
            <span class="absolute top-0 right-0 lg:top-1 lg:right-1 w-2 h-2 bg-primary-500 rounded-full"></span>
        </a>

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" class="flex items-center justify-center lg:w-12 lg:h-12 {{ request()->routeIs('profile.*') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors">
            @if(auth()->user()->profile?->profile_photo)
                <img src="{{ Storage::url(auth()->user()->profile->profile_photo) }}" alt="Profile" class="w-7 h-7 rounded-full object-cover border-2 {{ request()->routeIs('profile.*') ? 'border-primary-500' : 'border-transparent' }}" />
            @else
                @if(request()->routeIs('profile.*'))
                    <x-solar-user-circle-bold class="w-6 h-6" />
                @else
                    <x-solar-user-circle-linear class="w-6 h-6" />
                @endif
            @endif
        </a>

        <!-- Logout - Desktop Only, at bottom -->
        <form method="POST" action="{{ route('logout') }}" class="hidden lg:block lg:mt-auto">
            @csrf
            <button type="submit" class="flex items-center justify-center w-12 h-12 text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
                <x-solar-logout-2-linear class="w-6 h-6" />
            </button>
        </form>
    </div>
</nav>
