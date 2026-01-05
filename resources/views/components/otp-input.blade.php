@props(['name' => 'otp', 'length' => 6])

<div class="flex gap-2 justify-center" x-data="otpInput({{ $length }})">
    @for ($i = 0; $i < $length; $i++)
        <input
            type="text"
            maxlength="1"
            pattern="[0-9]"
            inputmode="numeric"
            x-ref="digit{{ $i }}"
            @input="handleInput({{ $i }}, $event)"
            @keydown.backspace="handleBackspace({{ $i }}, $event)"
            @paste="handlePaste($event)"
            class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold bg-gray-50 dark:bg-dark-surface border-2 border-gray-200 dark:border-dark-border focus:border-primary-500 dark:focus:border-primary-500 focus:ring-0 rounded-2xl text-gray-900 dark:text-white transition-colors duration-200"
        />
    @endfor

    <input type="hidden" name="{{ $name }}" x-model="value" required />
</div>
