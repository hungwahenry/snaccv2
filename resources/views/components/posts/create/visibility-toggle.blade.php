<div>
    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 lowercase">
        who can see this?
    </label>

    <div class="grid grid-cols-2 gap-2">
        <!-- Global -->
        <label class="relative cursor-pointer">
            <input
                type="radio"
                name="visibility"
                value="global"
                x-model="visibility"
                checked
                class="peer sr-only"
            />
            <div class="px-3 py-2.5 bg-gray-50 dark:bg-dark-bg border border-gray-200 dark:border-dark-border peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 rounded-xl transition-all">
                <div class="flex items-center gap-2">
                    <x-solar-global-linear class="w-4 h-4 text-primary-500 flex-shrink-0" />
                    <div class="text-xs font-medium text-gray-900 dark:text-white lowercase">global</div>
                </div>
            </div>
        </label>

        <!-- Campus Only -->
        <label class="relative cursor-pointer">
            <input
                type="radio"
                name="visibility"
                value="campus"
                x-model="visibility"
                class="peer sr-only"
            />
            <div class="px-3 py-2.5 bg-gray-50 dark:bg-dark-bg border border-gray-200 dark:border-dark-border peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 rounded-xl transition-all">
                <div class="flex items-center gap-2">
                    <x-solar-buildings-2-linear class="w-4 h-4 text-primary-500 flex-shrink-0" />
                    <div class="text-xs font-medium text-gray-900 dark:text-white lowercase">campus only</div>
                </div>
            </div>
        </label>
    </div>
</div>
