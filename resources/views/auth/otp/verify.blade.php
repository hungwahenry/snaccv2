<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-bold text-5xl sm:text-6xl text-gray-900 dark:text-white leading-tight lowercase mb-4">
            enter code
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-400 lowercase">
            sent to {{ session('otp_email') }}
        </p>
    </div>

    <form method="POST" action="{{ route('auth.verify') }}" class="space-y-8">
        @csrf

        <div>
            <x-otp-input name="otp" />
            <x-input-error :messages="$errors->get('otp')" />
        </div>

        <x-primary-button loading-text="verifying">
            <span class="lowercase">verify</span>
            <x-solar-arrow-right-linear class="w-5 h-5 ml-2" />
        </x-primary-button>
    </form>

    <div class="mt-8 flex items-center justify-center gap-6 text-sm">
        <form method="POST" action="{{ route('auth.resend') }}" class="inline" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <button type="submit" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors lowercase font-medium disabled:opacity-50" x-bind:disabled="loading">
                <span x-show="!loading">resend code</span>
                <span x-show="loading" class="flex items-center gap-1.5">
                    <span>resending</span>
                    <x-loading-dots size="sm" />
                </span>
            </button>
        </form>

        <span class="text-gray-300 dark:text-gray-700">•</span>

        <a href="{{ route('auth') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors lowercase font-medium">
            change email
        </a>
    </div>
</x-guest-layout>
