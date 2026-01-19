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
                @inject('renderer', 'App\Services\NotificationRenderer')
                @php
                    $rendered = $renderer->render($notification);
                @endphp

                <div 
                    class="group relative flex items-start gap-4 p-4 rounded-xl transition-all duration-200 border 
                           {{ $rendered['is_read'] ? 'bg-white dark:bg-dark-elem border-gray-100 dark:border-dark-border' : 'bg-brand-50/30 dark:bg-brand-900/10 border-brand-100 dark:border-brand-900/30' }}"
                >
                    <!-- Actor Avatar(s) -->
                    <div class="flex-shrink-0 relative">
                        @if($rendered['is_grouped'])
                            {{-- Avatar Stack for grouped notifications --}}
                            <div class="flex -space-x-2">
                                @foreach(array_slice($rendered['actors'], 0, 3) as $index => $actor)
                                    <img 
                                        src="{{ $actor['avatar'] ? Storage::url($actor['avatar']) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($actor['name']) }}" 
                                        alt="{{ $actor['name'] }}" 
                                        class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-dark-elem {{ $index > 0 ? 'relative' : '' }}"
                                        style="z-index: {{ 3 - $index }}"
                                    >
                                @endforeach
                                @if($rendered['total_count'] > 3)
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-white dark:border-dark-elem flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                                        +{{ $rendered['total_count'] - 3 }}
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Single avatar --}}
                            @php $firstActor = $rendered['actors'][0] ?? ['name' => 'System', 'avatar' => null]; @endphp
                            <img 
                                src="{{ $firstActor['avatar'] ? Storage::url($firstActor['avatar']) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($firstActor['name']) }}" 
                                alt="{{ $firstActor['name'] }}" 
                                class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                            >
                        @endif
                        
                        <!-- Type Icon Badge -->
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-[10px] border border-white dark:border-dark-elem {{ $rendered['bg_class'] }} {{ $rendered['text_class'] }}">
                            <i class="{{ $rendered['icon'] }}"></i>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <a href="{{ $rendered['url'] }}" class="block focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $rendered['message'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $rendered['date'] }}
                            </p>
                        </a>
                    </div>

                    @if(!$rendered['is_read'])
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
