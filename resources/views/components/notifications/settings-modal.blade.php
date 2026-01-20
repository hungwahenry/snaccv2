@props(['types', 'channels'])

<x-modal name="notification-settings" focusable maxWidth="md">
    <div class="flex flex-col max-h-[85vh]" x-data="{
        updateSetting(type, channel, enabled) {
            fetch('{{ route('notifications.settings.update') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type, channel, enabled })
            }).then(response => {
                if (!response.ok) {
                    console.error('Failed to update setting');
                }
            });
        }
    }">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-dark-border">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase" style="font-family: 'Boldonse', sans-serif;">
                notification settings
            </h2>
            <button
                type="button"
                @click="$dispatch('close')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto px-4 py-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-dark-border">
                            <th class="pb-3 font-medium text-gray-500 dark:text-gray-400 lowercase">type</th>
                            @foreach($channels as $channel)
                                <th class="pb-3 font-medium text-gray-500 dark:text-gray-400 text-center">{{ $channel->label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-elem/10">
                        @foreach($types as $type)
                            <tr>
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-500">
                                            <x-dynamic-component :component="$type->icon" class="w-5 h-5" />
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-200 lowercase">
                                            {{ str_replace('_', ' ', $type->type) }}
                                        </span>
                                    </div>
                                </td>
                                @foreach($channels as $channel)
                                    <td class="py-4 text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-dark-elem dark:border-dark-border"
                                                @checked(auth()->user()->wantsNotification($type->type, $channel->name))
                                                x-on:change="updateSetting('{{ $type->type }}', '{{ $channel->name }}', $event.target.checked)"
                                            >
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-dark-border flex justify-end">
            <x-primary-button x-on:click="$dispatch('close')">
                <span class="lowercase">done</span>
            </x-primary-button>
        </div>
    </div>
</x-modal>
