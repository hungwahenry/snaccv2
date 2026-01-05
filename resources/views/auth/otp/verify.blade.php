<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Enter Code</h2>
        <p class="mt-2 text-sm text-gray-600">
            We sent a 6-digit code to<br>
            <span class="font-medium text-gray-900">{{ session('otp_email') }}</span>
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('auth.verify') }}">
        @csrf

        <div>
            <x-input-label for="otp" :value="__('Verification Code')" />
            <x-text-input
                id="otp"
                class="block mt-1 w-full text-center text-2xl tracking-widest font-mono"
                type="text"
                name="otp"
                required
                autofocus
                maxlength="6"
                pattern="[0-9]{6}"
                placeholder="000000"
                autocomplete="one-time-code"
            />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('auth.resend') }}">
            @csrf
            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-500">
                {{ __('Didn\'t receive the code? Resend') }}
            </button>
        </form>
    </div>

    <div class="mt-2 text-center">
        <a href="{{ route('auth') }}" class="text-sm text-gray-600 hover:text-gray-900">
            {{ __('Use a different email') }}
        </a>
    </div>

    <script>
        // Auto-submit when 6 digits are entered
        document.getElementById('otp').addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');

            // Auto-submit when 6 digits entered
            if (this.value.length === 6) {
                this.form.submit();
            }
        });
    </script>
</x-guest-layout>
