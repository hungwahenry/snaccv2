<header 
    x-data="{ scrolled: false }" 
    @scroll.window="scrolled = (window.pageYOffset > 10)"
    class="fixed top-0 z-40 w-full transition-all duration-300 lg:hidden"
    :class="scrolled ? 'bg-white/80 dark:bg-dark-bg/80 backdrop-blur-md border-b border-gray-200 dark:border-dark-border' : 'bg-transparent border-b border-transparent'"
>
    <div class="flex items-center justify-center h-14 px-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <x-application-logo class="h-10 w-auto" />
        </a>
    </div>
</header>
