export default (trendingUrl, searchUrl) => ({
    gifs: [],
    searchQuery: '',
    loading: false,
    offset: 0,
    hasMore: true,
    debounceTimer: null,

    init() {
        this.loadTrending();

        this.$watch('searchQuery', (query) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.performSearch(query);
            }, 400); // 400ms debounce
        });
    },

    reset() {
        this.gifs = [];
        this.offset = 0;
        this.hasMore = true;
    },

    async loadTrending() {
        this.loading = true;
        this.reset();

        try {
            const response = await fetch(`${trendingUrl}?limit=20&offset=${this.offset}`);
            const data = await response.json();

            this.gifs = data.data;
            this.hasMore = data.pagination && data.pagination.total_count > (this.offset + 20);
            this.offset += 20;
        } catch (error) {
            console.error('Failed to load trending GIFs:', error);
        } finally {
            this.loading = false;
        }
    },

    async performSearch(query) {
        this.reset();

        if (!query.trim()) {
            this.loadTrending();
            return;
        }

        this.loading = true;

        try {
            const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}&limit=20&offset=0`);
            const data = await response.json();

            if (query === this.searchQuery) {
                this.gifs = data.data;
                this.hasMore = data.pagination && data.pagination.total_count > 20;
                this.offset = 20;
            }
        } catch (error) {
            console.error('Failed to search GIFs:', error);
        } finally {
            if (query === this.searchQuery) {
                this.loading = false;
            }
        }
    },

    async loadMore() {
        if (!this.hasMore || this.loading) return;

        this.loading = true;
        try {
            const url = this.searchQuery.trim()
                ? `${searchUrl}?q=${encodeURIComponent(this.searchQuery)}&limit=20&offset=${this.offset}`
                : `${trendingUrl}?limit=20&offset=${this.offset}`;

            const response = await fetch(url);
            const data = await response.json();

            this.gifs = [...this.gifs, ...data.data];
            this.hasMore = data.pagination && data.pagination.total_count > (this.offset + 20);
            this.offset += 20;
        } catch (error) {
            console.error('Failed to load more GIFs:', error);
        } finally {
            this.loading = false;
        }
    },

    selectGif(gif) {
        // Dispatch event with the name from the global store
        const eventName = Alpine.store('gifPickerState').eventName || 'gif-selected';
        this.$dispatch(eventName, gif);

        // Reset the event name to default after use
        Alpine.store('gifPickerState').eventName = 'gif-selected';

        this.$dispatch('close-modal', 'gif-picker');
    }
});

