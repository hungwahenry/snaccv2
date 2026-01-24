<x-settings.layout>
    <form method="post" action="{{ route('settings.app.update') }}" class="space-y-10">
        @csrf
        @method('patch')

        <!-- Theme Section -->
        <section>
            <header>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase">appearance</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    customize how snacc looks on your device.
                </p>
            </header>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- System -->
                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="system" class="peer sr-only" {{ ($preferences['theme'] ?? 'system') === 'system' ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-dark-border peer-checked:border-primary-500 dark:peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 transition-all">
                        <div class="flex flex-col items-center gap-3 text-center">
                            <x-solar-monitor-smartphone-linear class="w-8 h-8 text-gray-600 dark:text-gray-400 sm:hidden" />
                            <span class="font-bold text-gray-900 dark:text-white lowercase">system</span>
                        </div>
                    </div>
                </label>

                <!-- Light -->
                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="light" class="peer sr-only" {{ ($preferences['theme'] ?? 'system') === 'light' ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-dark-border peer-checked:border-primary-500 dark:peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 transition-all">
                        <div class="flex flex-col items-center gap-3 text-center">
                            <x-solar-sun-2-linear class="w-8 h-8 text-gray-600 dark:text-gray-400 sm:hidden" />
                            <span class="font-bold text-gray-900 dark:text-white lowercase">light</span>
                        </div>
                    </div>
                </label>

                <!-- Dark -->
                <label class="cursor-pointer">
                    <input type="radio" name="theme" value="dark" class="peer sr-only" {{ ($preferences['theme'] ?? 'system') === 'dark' ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-dark-border peer-checked:border-primary-500 dark:peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 transition-all">
                        <div class="flex flex-col items-center gap-3 text-center">
                            <x-solar-moon-linear class="w-8 h-8 text-gray-600 dark:text-gray-400 sm:hidden" />
                            <span class="font-bold text-gray-900 dark:text-white lowercase">dark</span>
                        </div>
                    </div>
                </label>
            </div>
        </section>

        <div class="border-t border-gray-100 dark:border-dark-border"></div>

        <!-- Autoplay Section -->
        <section>
            <header>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase">media autoplay</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    control when gifs and videos play automatically.
                </p>
            </header>

            <div class="mt-4">
                <x-select name="autoplay_media" class="block w-full max-w-md">
                    <option value="always" {{ ($preferences['autoplay_media'] ?? 'always') === 'always' ? 'selected' : '' }}>always play</option>
                    <option value="wifi" {{ ($preferences['autoplay_media'] ?? 'always') === 'wifi' ? 'selected' : '' }}>wifi only</option>
                    <option value="never" {{ ($preferences['autoplay_media'] ?? 'always') === 'never' ? 'selected' : '' }}>never autoplay</option>
                </x-select>
            </div>
        </section>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('save') }}</x-primary-button>
        </div>
    </form>
</x-settings.layout>
