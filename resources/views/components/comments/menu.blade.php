@props(['comment'])

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button
        @click="open = !open"
        class="p-1 text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-950/20 rounded-full transition-colors"
    >
        <x-solar-menu-dots-bold class="w-4 h-4" />
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
        class="absolute right-0 mt-1 w-36 bg-white dark:bg-dark-surface rounded-lg shadow-lg border border-gray-200 dark:border-dark-border overflow-hidden z-10"
        style="display: none;"
    >
        @can('delete', $comment)
            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('are you sure you want to delete this comment?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-3 py-2 text-left text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors flex items-center gap-2 lowercase">
                    <x-solar-trash-bin-trash-linear class="w-3.5 h-3.5 flex-shrink-0" />
                    <span>delete</span>
                </button>
            </form>
        @endcan

        @auth
            @cannot('delete', $comment)
                <button
                    @click="$dispatch('open-modal', 'report-comment-{{ $comment->slug }}')"
                    class="w-full px-3 py-2 text-left text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-2 lowercase">
                    <x-solar-danger-triangle-linear class="w-3.5 h-3.5 flex-shrink-0" />
                    <span>report</span>
                </button>
            @endcannot
        @endauth
    </div>
</div>

<!-- Report Modal -->
<x-reports.modal
    reportableType="comment"
    :reportableSlug="$comment->slug"
    :modalName="'report-comment-' . $comment->slug"
/>
