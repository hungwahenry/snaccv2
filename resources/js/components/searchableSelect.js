export default (options, initialValue = null) => ({
    open: false,
    search: '',
    selectedValue: initialValue,
    selectedLabel: '',
    options: options,

    init() {
        if (this.selectedValue) {
            const option = this.options.find(opt => opt.value == this.selectedValue);
            if (option) {
                this.selectedLabel = option.label;
            }
        }
    },

    get filteredOptions() {
        if (!this.search) return this.options;
        const searchLower = this.search.toLowerCase();
        return this.options.filter(option =>
            option.label.toLowerCase().includes(searchLower)
        );
    },

    selectOption(option) {
        this.selectedValue = option.value;
        this.selectedLabel = option.label;
        this.open = false;
        this.search = '';
    }
});
