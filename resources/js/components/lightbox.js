export default () => ({
    isOpen: false,
    images: [],
    currentIndex: 0,

    get currentImage() {
        return this.images[this.currentIndex] || '';
    },

    open(data) {
        this.images = data.images || [];
        this.currentIndex = data.index || 0;
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.isOpen = false;
        document.body.style.overflow = '';
    },

    next() {
        if (this.currentIndex < this.images.length - 1) {
            this.currentIndex++;
        }
    },

    previous() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
        }
    }
});
