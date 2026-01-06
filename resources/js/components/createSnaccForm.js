export default () => ({
    content: '',
    images: [],
    previews: [],
    selectedGif: null,
    visibility: 'campus',
    loading: false,
    error: '',
    quotedSnaccId: null,
    quotedSnaccData: null,

    init() {
        // Listen for gif-selected event
        this.$watch('selectedGif', (gif) => {
            if (gif) {
                // Clear images if a GIF is selected
                this.images = [];
                this.previews = [];
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            }
        });

        // Listen for the gif-selected event from the GIF picker
        window.addEventListener('gif-selected', (e) => {
            this.selectedGif = e.detail;
        });

        // Listen for quote-snacc event
        window.addEventListener('quote-snacc', (e) => {
            this.quotedSnaccId = e.detail.id;
            this.quotedSnaccData = e.detail;
        });
    },

    removeQuotedSnacc() {
        this.quotedSnaccId = null;
        this.quotedSnaccData = null;
    },

    get vibetags() {
        const matches = this.content.match(/~(\w+)/g);
        return matches ? [...new Set(matches)] : [];
    },

    handleFiles(event) {
        const selectedFiles = Array.from(event.target.files);
        const totalImages = this.images.length + selectedFiles.length;

        if (totalImages > 10) {
            this.error = 'you can only upload up to 10 images';
            return;
        }

        // Clear GIF if images are being added
        if (selectedFiles.length > 0 && this.selectedGif) {
            this.selectedGif = null;
        }

        selectedFiles.forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                this.error = 'each image must be less than 5mb';
                return;
            }

            this.images.push(file);

            const reader = new FileReader();
            reader.onload = (e) => {
                this.previews.push(e.target.result);
            };
            reader.readAsDataURL(file);
        });

        this.error = '';
    },

    removeImage(index) {
        this.previews.splice(index, 1);
        this.images.splice(index, 1);

        const dataTransfer = new DataTransfer();
        this.images.forEach(file => dataTransfer.items.add(file));
        if (this.$refs.fileInput) {
            this.$refs.fileInput.files = dataTransfer.files;
        }
    },

    handleSubmit(e) {
        if (this.loading) {
            e.preventDefault();
            return;
        }

        if (!this.content.trim() && this.images.length === 0 && !this.selectedGif) {
            e.preventDefault();
            alert('please add some content, images, or a gif to your snacc');
            return;
        }

        this.loading = true;
    }
});
