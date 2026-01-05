export default (length) => ({
    value: '',

    handleInput(index, event) {
        const input = event.target;
        const value = input.value.replace(/[^0-9]/g, '');

        if (value) {
            input.value = value[0];
            this.updateValue();

            if (index < length - 1) {
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
        const digits = paste.replace(/[^0-9]/g, '').split('').slice(0, length);

        digits.forEach((digit, index) => {
            if (this.$refs['digit' + index]) {
                this.$refs['digit' + index].value = digit;
            }
        });

        this.updateValue();

        const lastFilledIndex = Math.min(digits.length, length) - 1;
        if (this.$refs['digit' + lastFilledIndex]) {
            this.$refs['digit' + lastFilledIndex].focus();
        }
    },

    updateValue() {
        let code = '';
        for (let i = 0; i < length; i++) {
            code += this.$refs['digit' + i].value || '';
        }
        this.value = code;
    }
});
