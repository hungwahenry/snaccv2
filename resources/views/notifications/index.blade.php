<x-app-layout>
    <div>
        <!-- Header -->
        <div class="sticky top-14 lg:top-0 z-30 bg-white dark:bg-dark-bg border-b border-gray-200 dark:border-dark-border px-4 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white lowercase">notifications</h1>
                
                <div class="flex items-center gap-2 sm:gap-3">
                    <button 
                        x-data
                        x-on:click="$dispatch('open-modal', 'notification-settings')"
                        class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-surface rounded-lg transition-colors"
                        title="Notification Settings"
                    >
                        <x-solar-settings-linear class="w-5 h-5" />
                    </button>

                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs sm:text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
                                mark all read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        @forelse ($notifications as $notification)
            <x-notifications.notification-card :notification="$notification" />
        @empty
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 bg-gray-100 dark:bg-dark-surface rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 dark:text-gray-600">
                    <x-solar-bell-bing-bold-duotone class="w-8 h-8" />
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 lowercase">no notifications yet</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm lowercase">when people interact with your snaccs, you'll see it here.</p>
            </div>
        @endforelse

        @if($notifications->hasPages())
            <div class="px-4 py-6 border-t border-gray-200 dark:border-dark-border">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
    
    <x-notifications.settings-modal :types="$types" :channels="$channels" />
</x-app-layout>
