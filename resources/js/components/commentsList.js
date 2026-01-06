export default (initialComments, initialHasMore, initialPage, commentsIndexUrl) => ({
    comments: initialComments,
    hasMorePages: initialHasMore,
    currentPage: initialPage,
    emptyState: initialComments.length === 0,
    loadingMore: false,

    init() {
        // Listen for new top-level comments
        window.addEventListener('comment-posted', (e) => {
            if (e.detail.comment.parent_comment_id === null) {
                this.comments.unshift(e.detail.comment);
                this.emptyState = false;
            }
        });
    },

    async loadMoreComments() {
        if (this.loadingMore || !this.hasMorePages) {
            return;
        }

        const nextPage = this.currentPage + 1;
        const url = commentsIndexUrl + '?page=' + nextPage;

        this.loadingMore = true;

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.comments.push(...data.comments);
                this.hasMorePages = data.has_more;
                this.currentPage = nextPage;
            }
        } catch (error) {
            console.error('Error loading comments:', error);
        } finally {
            this.loadingMore = false;
        }
    }
});
