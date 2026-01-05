<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Welcome to SNACC</h2>
        <p class="mt-2 text-sm text-gray-600">
            Enter your email to continue
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('auth.send') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="email"
                placeholder="your.email@university.edu"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Continue') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 text-center text-sm text-gray-600">
        <p>We'll send a 6-digit code to your email</p>
    </div>
</x-guest-layout>
