<x-settings.layout>
    <div class="space-y-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white lowercase">blocked users</h3>
        
        @if($blockedUsers->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">
                you haven't blocked anyone yet.
            </p>
        @else
            <div class="space-y-4">
                @foreach($blockedUsers as $blockedUser)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-dark-bg rounded-2xl">
                        <div class="flex items-center gap-3">
                            <img 
                                src="{{ $blockedUser->profile->profile_photo ? Storage::url($blockedUser->profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($blockedUser->profile->username) }}" 
                                alt="{{ $blockedUser->profile->username }}" 
                                class="w-10 h-10 rounded-full object-cover"
                            >
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $blockedUser->profile->username }}
                                </p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('users.unblock', $blockedUser) }}">
                            @csrf
                            @method('DELETE')
                            <x-secondary-button type="submit" class="!px-3 !py-1 text-xs">
                                unblock
                            </x-secondary-button>
                        </form>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $blockedUsers->links() }}
            </div>
        @endif
    </div>
</x-settings.layout>
