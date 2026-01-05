@props(['name' => 'otp', 'length' => 6])

<div class="flex gap-2 justify-center" x-data="otpInput()">
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

<script>
function otpInput() {
    return {
        value: '',

        handleInput(index, event) {
            const input = event.target;
            const value = input.value.replace(/[^0-9]/g, '');

            if (value) {
                input.value = value[0];
                this.updateValue();

                if (index < {{ $length - 1 }}) {
                    this.$refs['digit' + (index + 1)].focus();
                }
            } else {
                input.value = '';
                this.updateValue();
            }
        },

        handleBackspace(index, event) {
            if (!event.target.value && index > 0) {
                this.$refs['digit' + (index - 1)].focus();
            }
            this.updateValue();
        },

        handlePaste(event) {
            event.preventDefault();
            const paste = (event.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/[^0-9]/g, '').split('').slice(0, {{ $length }});

            digits.forEach((digit, index) => {
                if (this.$refs['digit' + index]) {
                    this.$refs['digit' + index].value = digit;
                }
            });

            this.updateValue();

            const lastFilledIndex = Math.min(digits.length, {{ $length }}) - 1;
            if (this.$refs['digit' + lastFilledIndex]) {
                this.$refs['digit' + lastFilledIndex].focus();
            }
        },

        updateValue() {
            let code = '';
            for (let i = 0; i < {{ $length }}; i++) {
                code += this.$refs['digit' + i].value || '';
            }
            this.value = code;
        }
    }
}
</script>
