<aside class="hidden lg:flex flex-col fixed top-0 right-0 z-40 w-80 h-screen bg-white dark:bg-dark-surface border-l-2 border-gray-200 dark:border-dark-border">
    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto py-6">
        <x-sidebar.trending-vibetags />
        <x-sidebar.suggested-users />
    </div>

    <!-- Pinned Footer -->
    <div class="p-6 border-t border-gray-100 dark:border-dark-border bg-white dark:bg-dark-surface">
        <nav class="flex flex-wrap gap-x-4 gap-y-2 mb-4">
            <a href="#" class="text-xs text-gray-500 dark:text-gray-400 hover:underline decoration-gray-500/50 lowercase">about</a>
            <a href="#" class="text-xs text-gray-500 dark:text-gray-400 hover:underline decoration-gray-500/50 lowercase">terms</a>
            <a href="#" class="text-xs text-gray-500 dark:text-gray-400 hover:underline decoration-gray-500/50 lowercase">privacy</a>
            <a href="#" class="text-xs text-gray-500 dark:text-gray-400 hover:underline decoration-gray-500/50 lowercase">rules</a>
            <a href="#" class="text-xs text-gray-500 dark:text-gray-400 hover:underline decoration-gray-500/50 lowercase">safety</a>
        </nav>
        
        <div class="text-xs text-gray-400 dark:text-gray-500">
            <p>&copy; {{ date('Y') }} snacc.</p>
        </div>
    </div>
</aside>
