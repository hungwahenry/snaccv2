@props(['snacc'])

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button
        @click="open = !open"
        class="p-1 text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-950/20 rounded-full transition-colors"
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
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-surface rounded-xl shadow-xl border border-gray-200 dark:border-dark-border py-1 z-10"
        style="display: none;"
    >
        @auth
            @if(auth()->id() === $snacc->user_id)
                <button class="w-full px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg lowercase">
                    edit snacc
                </button>
                <button class="w-full px-4 py-2.5 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 lowercase">
                    delete snacc
                </button>
            @else
                <button
                    @click="$dispatch('open-modal', 'report-snacc-{{ $snacc->slug }}')"
                    class="w-full px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg lowercase">
                    report snacc
                </button>
            @endif
        @endauth
    </div>
</div>

<!-- Report Modal -->
<x-reports.modal
    reportableType="snacc"
    :reportableSlug="$snacc->slug"
    :modalName="'report-snacc-' . $snacc->slug"
/>
