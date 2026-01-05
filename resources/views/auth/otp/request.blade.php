<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-bold text-5xl sm:text-6xl text-gray-900 dark:text-white leading-tight lowercase">
            heyyy...
        </h1>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('auth.send') }}" class="space-y-6">
        @csrf

        <div class="space-y-2">
            <x-input-label for="email" value="email address" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <x-solar-letter-outline class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                </div>
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="you@university.edu"
                    class="pl-12"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>
            <span class="lowercase">continue</span>
            <x-solar-arrow-right-linear class="w-5 h-5 ml-2" />
        </x-primary-button>
    </form>

    <div class="mt-8 flex items-center justify-center gap-2 text-center">
        <x-solar-shield-check-outline class="w-4 h-4 text-gray-400 dark:text-gray-500" />
        <p class="text-sm text-gray-500 dark:text-gray-400 lowercase">
            we'll send a 6-digit code to verify your email
        </p>
    </div>
</x-guest-layout>
