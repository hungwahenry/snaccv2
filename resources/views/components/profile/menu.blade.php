@props(['user'])

@if(auth()->check() && auth()->id() !== $user->id)
    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
        <button
            @click="open = !open"
            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-surface rounded-full transition-colors"
        >
            <x-solar-menu-dots-bold class="w-5 h-5" />
        </button>

        <!-- Dropdown Menu -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-surface rounded-xl shadow-lg border border-gray-200 dark:border-dark-border overflow-hidden z-20 origin-top-right"
            style="display: none;"
            x-cloak
        >
            <button
                x-data="{ copied: false }"
                @click="
                    navigator.clipboard.writeText('{{ route('profile.show', $user->profile->username) }}');
                    copied = true;
                    setTimeout(() => { copied = false; open = false; }, 1000);
                "
                class="w-full px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors flex items-center gap-2 lowercase"
            >
                <template x-if="!copied">
                    <div class="flex items-center gap-2">
                        <x-solar-copy-linear class="w-5 h-5 flex-shrink-0" />
                        <span>copy link</span>
                    </div>
                </template>
                <template x-if="copied">
                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                        <x-solar-check-circle-linear class="w-5 h-5 flex-shrink-0" />
                        <span>copied!</span>
                    </div>
                </template>
            </button>
            
            <button
                @click="open = false; $dispatch('open-modal', 'report-user-modal')"
                class="w-full px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors flex items-center gap-2 lowercase"
            >
                <x-solar-danger-triangle-linear class="w-5 h-5 flex-shrink-0 text-red-500" />
                <span>report user</span>
            </button>
        </div>
    </div>
@endif
