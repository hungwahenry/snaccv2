@props(['name', 'options' => [], 'placeholder' => 'Select an option', 'searchPlaceholder' => 'Search...', 'value' => null, 'required' => false])

<div x-data="searchableSelect({{ json_encode($options) }}, '{{ $value }}')" class="relative">
    <!-- Display Input -->
    <button
        type="button"
        @click="open = !open"
        @click.away="open = false"
        class="w-full px-4 py-4 bg-gray-50 dark:bg-dark-surface border-2 border-gray-200 dark:border-dark-border focus:border-primary-500 dark:focus:border-primary-500 rounded-2xl text-base text-gray-900 dark:text-gray-100 text-left transition-colors duration-200 flex items-center justify-between"
    >
        <span x-text="selectedLabel || '{{ $placeholder }}'" x-bind:class="!selectedLabel ? 'text-gray-400 dark:text-gray-500' : ''"></span>
        <svg class="w-5 h-5 text-gray-400 transition-transform" x-bind:class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" x-model="selectedValue" {{ $required ? 'required' : '' }} />

    <!-- Dropdown -->
    <div
        x-show="open"
        x-transition
        class="absolute z-50 w-full mt-2 bg-white dark:bg-dark-surface border-2 border-gray-200 dark:border-dark-border rounded-2xl shadow-soft overflow-hidden"
    >
        <!-- Search Input -->
        <div class="p-3 border-b-2 border-gray-200 dark:border-dark-border">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="text"
                    x-model="search"
                    placeholder="{{ $searchPlaceholder }}"
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-dark-bg border-0 rounded-xl text-sm focus:ring-0 focus:outline-none"
                    @click.stop
                />
            </div>
        </div>

        <!-- Options List -->
        <div class="max-h-60 overflow-y-auto">
            <template x-for="option in filteredOptions" x-bind:key="option.value">
                <button
                    type="button"
                    @click="selectOption(option)"
                    class="w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-dark-bg transition-colors"
                    x-bind:class="selectedValue == option.value ? 'bg-primary-50 dark:bg-primary-900/20' : ''"
                >
                    <span class="text-sm text-gray-900 dark:text-gray-100" x-text="option.label"></span>
                </button>
            </template>

            <div x-show="filteredOptions.length === 0" class="px-4 py-8 text-center text-sm text-gray-400">
                no results found
            </div>
        </div>
    </div>
</div>

<script>
function searchableSelect(options, initialValue = null) {
    return {
        open: false,
        search: '',
        selectedValue: initialValue,
        selectedLabel: '',
        options: options,

        init() {
            if (this.selectedValue) {
                const option = this.options.find(opt => opt.value == this.selectedValue);
                if (option) {
                    this.selectedLabel = option.label;
                }
            }
        },

        get filteredOptions() {
            if (!this.search) return this.options;
            const searchLower = this.search.toLowerCase();
            return this.options.filter(option =>
                option.label.toLowerCase().includes(searchLower)
            );
        },

        selectOption(option) {
            this.selectedValue = option.value;
            this.selectedLabel = option.label;
            this.open = false;
            this.search = '';
        }
    }
}
</script>
