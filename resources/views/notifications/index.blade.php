<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
            
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-brand-500 hover:text-brand-600 font-medium">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <div class="space-y-2">
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $actorName = $data['actor_name'] ?? 'System';
                    $actorAvatar = $data['actor_avatar'] ?? null;
                    $message = $data['message'] ?? 'New notification';
                    $url = $data['url'] ?? '#';
                    $isRead = !is_null($notification->read_at);
                @endphp

                <div 
                    class="group relative flex items-start gap-4 p-4 rounded-xl transition-all duration-200 border 
                           {{ $isRead ? 'bg-white dark:bg-dark-elem border-gray-100 dark:border-dark-border' : 'bg-brand-50/30 dark:bg-brand-900/10 border-brand-100 dark:border-brand-900/30' }}"
                >
                    <!-- Actor Avatar -->
                    <div class="flex-shrink-0 relative">
                        <img 
                            src="{{ $actorAvatar ? Storage::url($actorAvatar) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($actorName) }}" 
                            alt="{{ $actorName }}" 
                            class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                        >
                        <!-- Type Icon Badge -->
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-[10px] border border-white dark:border-dark-elem
                            @if($data['type'] === 'like') bg-red-100 text-red-600
                            @elseif($data['type'] === 'comment') bg-blue-100 text-blue-600
                            @elseif($data['type'] === 'quote') bg-purple-100 text-purple-600
                            @elseif($data['type'] === 'add') bg-green-100 text-green-600
                            @elseif($data['type'] === 'viral') bg-orange-100 text-orange-600
                            @else bg-gray-100 text-gray-600
                            @endif
                        ">
                            @if($data['type'] === 'like') <i class="solar:heart-bold"></i>
                            @elseif($data['type'] === 'comment') <i class="solar:chat-round-dots-bold"></i>
                            @elseif($data['type'] === 'quote') <i class="solar:quote-up-square-bold"></i>
                            @elseif($data['type'] === 'add') <i class="solar:user-plus-bold"></i>
                            @elseif($data['type'] === 'viral') <i class="solar:fire-bold"></i>
                            @else <i class="solar:bell-bold"></i>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <a href="{{ $url }}" class="block focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $message }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </a>
                    </div>

                    @if(!$isRead)
                        <div class="flex-shrink-0 self-center">
                            <div class="w-2.5 h-2.5 bg-brand-500 rounded-full"></div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-dark-bg rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="solar:bell-bing-bold-duotone text-3xl"></i>
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
