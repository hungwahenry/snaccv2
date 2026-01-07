export default (reportableType, reportableSlug, modalName) => ({
    reportableType: reportableType,
    reportableSlug: reportableSlug,
    modalName: modalName,
    categories: [],
    selectedCategory: '',
    description: '',
    loading: false,
    submitting: false,
    submitted: false,
    attemptedSubmit: false,
    error: '',
    categoriesLoaded: false,

    init() {
        // Listen for modal open event
        window.addEventListener('open-modal', (event) => {
            if (event.detail === this.modalName && !this.categoriesLoaded) {
                this.loadCategories();
            }
        });
    },

    async loadCategories() {
        this.loading = true;
        this.error = '';

        try {
            const response = await fetch(`/reports/categories/${this.reportableType}`);
            const data = await response.json();

            if (response.ok) {
                this.categories = data.categories;
                this.categoriesLoaded = true;
            } else {
                this.error = 'failed to load report categories';
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            this.error = 'failed to load report categories';
        } finally {
            this.loading = false;
        }
    },

    async submitReport() {
        this.attemptedSubmit = true;

        if (!this.selectedCategory) {
            return;
        }

        this.submitting = true;
        this.error = '';

        try {
            const response = await fetch('/reports', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    reportable_type: this.reportableType,
                    reportable_slug: this.reportableSlug,
                    category_slug: this.selectedCategory,
                    description: this.description || null,
                }),
            });

            const data = await response.json();

            if (response.ok) {
                this.submitted = true;
            } else {
                this.error = data.message || 'failed to submit report';
            }
        } catch (error) {
            console.error('Error submitting report:', error);
            this.error = 'failed to submit report';
        } finally {
            this.submitting = false;
        }
    },

    resetForm() {
        this.selectedCategory = '';
        this.description = '';
        this.submitted = false;
        this.attemptedSubmit = false;
        this.error = '';
    },
});
