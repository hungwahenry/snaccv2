<div 
    x-data="{ 
        show: false, 
        message: '{{ session('error') ?? session('success') ?? session('status') ?? session('message') ?? '' }}', 
        type: '{{ session('error') ? 'error' : 'success' }}',
        init() {
            if (this.message) {
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            }
            window.addEventListener('notify', event => {
                this.message = event.detail.message || event.detail;
                this.type = event.detail.type || 'success';
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            });
        }
    }"
    class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"
>
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-2 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-2 opacity-0 scale-95"
        class="pointer-events-auto px-4 py-3 bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-xl shadow-lg flex items-center gap-3 min-w-[300px]"
    >
        <!-- Icon based on type -->
        <template x-if="type === 'success'">
            <div class="rounded-full bg-green-100 dark:bg-green-900/30 p-1">
                <x-solar-check-circle-bold class="w-5 h-5 text-green-600 dark:text-green-400" />
            </div>
        </template>
        
        <template x-if="type === 'error'">
            <div class="rounded-full bg-red-100 dark:bg-red-900/30 p-1">
                <x-solar-danger-triangle-bold class="w-5 h-5 text-red-600 dark:text-red-400" />
            </div>
        </template>

        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="message"></p>
        
        <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
            <x-solar-close-circle-linear class="w-5 h-5" />
        </button>
    </div>
</div>
