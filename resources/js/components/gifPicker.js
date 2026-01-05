export default (trendingUrl, searchUrl) => ({
    gifs: [],
    searchQuery: '',
    loading: false,
    offset: 0,
    hasMore: true,

    init() {
        this.loadTrending();
    },

    async loadTrending() {
        this.loading = true;
        try {
            const response = await fetch(`${trendingUrl}?limit=20&offset=${this.offset}`);
            const data = await response.json();

            if (this.offset === 0) {
                this.gifs = data.data;
            } else {
                this.gifs = [...this.gifs, ...data.data];
            }

            this.hasMore = data.pagination && data.pagination.total_count > (this.offset + 20);
            this.offset += 20;
        } catch (error) {
            console.error('Failed to load trending GIFs:', error);
        }
        this.loading = false;
    },

    async search() {
        this.offset = 0;
        this.hasMore = true;

        if (!this.searchQuery.trim()) {
            this.loadTrending();
            return;
        }

        this.loading = true;
        try {
            const response = await fetch(`${searchUrl}?q=${encodeURIComponent(this.searchQuery)}&limit=20&offset=0`);
            const data = await response.json();

            this.gifs = data.data;
            this.hasMore = data.pagination && data.pagination.total_count > 20;
            this.offset = 20;
        } catch (error) {
            console.error('Failed to search GIFs:', error);
        }
        this.loading = false;
    },

    async loadMore() {
        if (this.searchQuery.trim()) {
            this.loading = true;
            try {
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(this.searchQuery)}&limit=20&offset=${this.offset}`);
                const data = await response.json();

                this.gifs = [...this.gifs, ...data.data];
                this.hasMore = data.pagination && data.pagination.total_count > (this.offset + 20);
                this.offset += 20;
            } catch (error) {
                console.error('Failed to load more GIFs:', error);
            }
            this.loading = false;
        } else {
            this.loadTrending();
        }
    },

    selectGif(gif) {
        // Dispatch custom event that the create form can listen to
        window.dispatchEvent(new CustomEvent('gif-selected', { detail: gif }));
        this.$dispatch('close-modal', 'gif-picker');
    }
});
