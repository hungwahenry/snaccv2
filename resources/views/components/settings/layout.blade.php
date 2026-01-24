<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 px-4 sm:px-0 lowercase" style="font-family: 'Boldonse', sans-serif;">settings</h1>

            <!-- Top Navigation -->
            <nav class="mb-8 px-4 sm:px-0">
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                    @php
                        $navItems = [
                            ['label' => 'profile', 'route' => 'settings.profile', 'icon' => 'solar-user-circle-linear'],
                            ['label' => 'account', 'route' => 'settings.account', 'icon' => 'solar-shield-user-linear'],
                            ['label' => 'app', 'route' => 'settings.app', 'icon' => 'solar-smartphone-linear'],
                            ['label' => 'privacy', 'route' => 'settings.privacy', 'icon' => 'solar-lock-keyhole-linear'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center gap-2 px-4 py-2.5 rounded-full transition-colors whitespace-nowrap {{ request()->routeIs($item['route']) ? 'bg-primary-500 text-white font-bold' : 'bg-gray-100 dark:bg-dark-surface text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-dark-border hover:text-gray-900 dark:hover:text-white' }}">
                            <x-dynamic-component :component="$item['icon']" class="w-5 h-5" />
                            <span class="lowercase">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </nav>

            <!-- Content Area -->
            <div class="bg-white dark:bg-dark-surface rounded-3xl border-2 border-gray-100 dark:border-dark-border p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
