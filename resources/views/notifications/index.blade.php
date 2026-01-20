<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
            
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-primary-500 hover:text-primary-600 font-medium">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white dark:bg-dark-bg rounded-xl border border-gray-200 dark:border-dark-border overflow-hidden">
            @forelse ($notifications as $notification)
                <x-notifications.notification-card :notification="$notification" />
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-dark-bg rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <x-solar-bell-bing-bold-duotone class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No notifications yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">When people interact with your snaccs, you'll see it here.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>
