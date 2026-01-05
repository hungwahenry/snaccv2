<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-dark-surface border-t-2 border-gray-200 dark:border-dark-border lg:top-0 lg:bottom-auto lg:w-20 lg:h-screen lg:border-t-0 lg:border-r-2">
    <div class="flex justify-around items-center h-16 lg:flex-col lg:h-full lg:justify-start lg:py-6 lg:gap-6">
        <!-- Logo - Desktop Only -->
        <a href="{{ route('home') }}" class="hidden lg:flex items-center justify-center w-12 h-12 mb-4">
            <x-application-logo class="w-10 h-10 fill-current text-primary-500" />
        </a>

        <!-- Home -->
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center flex-1 lg:flex-initial lg:w-12 lg:h-12 {{ request()->routeIs('home') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors">
            @if(request()->routeIs('home'))
                <x-solar-home-2-bold class="w-6 h-6" />
            @else
                <x-solar-home-2-linear class="w-6 h-6" />
            @endif
            <span class="text-xs mt-1 lowercase lg:hidden">home</span>
        </a>

        <!-- Explore -->
        <a href="#" class="flex flex-col items-center justify-center flex-1 lg:flex-initial lg:w-12 lg:h-12 text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
            <x-solar-compass-linear class="w-6 h-6" />
            <span class="text-xs mt-1 lowercase lg:hidden">explore</span>
        </a>

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center flex-1 lg:flex-initial lg:w-12 lg:h-12 {{ request()->routeIs('profile.*') ? 'text-primary-500' : 'text-gray-600 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400' }} transition-colors">
            @if(auth()->user()->profile?->profile_photo)
                <img src="{{ Storage::url(auth()->user()->profile->profile_photo) }}" alt="Profile" class="w-6 h-6 rounded-full object-cover" />
            @else
                @if(request()->routeIs('profile.*'))
                    <x-solar-user-circle-bold class="w-6 h-6" />
                @else
                    <x-solar-user-circle-linear class="w-6 h-6" />
                @endif
            @endif
            <span class="text-xs mt-1 lowercase lg:hidden">profile</span>
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
