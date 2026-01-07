@props(['reportableType', 'reportableSlug', 'modalName'])

<x-modal :name="$modalName" maxWidth="md" focusable>
    <div class="flex flex-col" x-data="reportModal('{{ $reportableType }}', '{{ $reportableSlug }}', '{{ $modalName }}')">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-dark-border">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase" style="font-family: 'Boldonse', sans-serif;">
                report {{ $reportableType }}
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

        <!-- Loading categories -->
        <div x-show="loading" class="flex items-center justify-center py-12 px-4 text-primary-600 dark:text-primary-400">
            <x-loading-dots size="lg" />
        </div>

        <!-- Report form -->
        <form x-show="!loading && !submitted" @submit.prevent="submitReport" class="px-4 py-4 space-y-4">
            <!-- Category selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 lowercase">
                    reason for reporting
                </label>
                <select
                    x-model="selectedCategory"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-dark-border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-dark-surface text-gray-900 dark:text-white text-sm lowercase"
                    :class="!selectedCategory && attemptedSubmit ? 'border-red-500 dark:border-red-500' : ''"
                >
                    <option value="" disabled selected>select a reason...</option>
                    <template x-for="category in categories" :key="category.slug">
                        <option :value="category.slug" x-text="category.name"></option>
                    </template>
                </select>
                <p x-show="!selectedCategory && attemptedSubmit"
                   class="mt-1 text-sm text-red-600 dark:text-red-400 lowercase">
                    please select a reason
                </p>
            </div>

            <!-- Optional description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 lowercase">
                    additional details (optional)
                </label>
                <textarea
                    x-model="description"
                    rows="3"
                    maxlength="500"
                    placeholder="provide more context about this report..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-dark-border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-dark-surface text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm lowercase"
                ></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 lowercase">
                    <span x-text="description.length"></span>/500 characters
                </p>
            </div>

            <!-- Error message -->
            <p x-show="error" x-text="error"
               class="text-sm text-red-600 dark:text-red-400 lowercase">
            </p>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-2">
                <button
                    type="button"
                    @click="$dispatch('close')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-bg rounded-lg transition-colors lowercase"
                >
                    cancel
                </button>
                <button
                    type="submit"
                    :disabled="submitting"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed lowercase"
                >
                    <span x-show="!submitting">submit report</span>
                    <span x-show="submitting">submitting...</span>
                </button>
            </div>
        </form>

        <!-- Success message -->
        <div x-show="submitted" class="text-center px-4 py-8">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 lowercase">
                report submitted
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 lowercase">
                we'll review your report and take appropriate action
            </p>
            <button
                @click="$dispatch('close'); resetForm();"
                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors lowercase"
            >
                close
            </button>
        </div>
    </div>
</x-modal>
