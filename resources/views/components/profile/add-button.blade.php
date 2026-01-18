@props(['user', 'isAdded'])

<div x-data="{
    isAdded: {{ $isAdded ? 'true' : 'false' }},
    isLoading: false,
    async toggleAdd() {
        if (this.isLoading) return;
        this.isLoading = true;

        const previousState = this.isAdded;
        // Optimistic toggle
        this.isAdded = !this.isAdded;

        try {
            const url = this.isAdded 
                ? '{{ route('users.add', $user) }}' 
                : '{{ route('users.remove', $user) }}';
            
            const method = this.isAdded ? 'POST' : 'DELETE';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            
            if (data.success) {
                this.isAdded = data.is_added;
                // Dispatch event to update global counts if needed
                window.dispatchEvent(new CustomEvent('user-stats-updated', { 
                    detail: { userId: {{ $user->id }}, addedByCount: data.added_by_count } 
                }));
            } else {
                this.isAdded = previousState;
            }
        } catch (error) {
            console.error('Error toggling add status:', error);
            this.isAdded = previousState;
        } finally {
            this.isLoading = false;
        }
    }
}">
    <!-- Added State -->
    <button 
        x-show="isAdded"
        @click="toggleAdd()"
        :disabled="isLoading"
        class="inline-flex items-center px-4 py-2 bg-white dark:bg-dark-surface border border-gray-300 dark:border-dark-border rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-800 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg disabled:opacity-50 disabled:cursor-not-allowed ease-in-out duration-150"
        x-cloak
    >
        <div class="flex items-center justify-center gap-2 min-w-[3rem]">
            <span x-show="!isLoading" class="flex items-center gap-2">
                <x-solar-minus-circle-linear class="w-5 h-5" />
                Remove
            </span>
            <span x-show="isLoading" class="flex items-center justify-center">
                <x-loading-dots size="sm" />
            </span>
        </div>
    </button>

    <!-- Not Added State -->
    <button 
        x-show="!isAdded"
        @click="toggleAdd()"
        :disabled="isLoading"
        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <div class="flex items-center justify-center gap-2 min-w-[4rem]">
            <span x-show="!isLoading" class="flex items-center gap-2">
                <x-solar-add-circle-linear class="w-5 h-5 text-white" />
                Add
            </span>
            <span x-show="isLoading" class="flex items-center justify-center">
                <x-loading-dots size="sm" class="text-white" />
            </span>
        </div>
    </button>
</div>
