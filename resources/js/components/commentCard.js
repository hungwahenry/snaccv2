export default (commentSlug, initialRepliesCount) => ({
    replies: [],
    repliesCount: initialRepliesCount,
    showReplies: false,
    loadingReplies: false,
    repliesPage: 1,
    hasMoreReplies: initialRepliesCount > 0,
    repliesLoaded: false,

    init() {
        // Listen for new replies posted to this comment
        window.addEventListener('comment-posted', (e) => {
            if (e.detail.comment.parent_comment_slug == commentSlug) {
                this.replies.push(e.detail.comment);
                this.repliesCount++;
                this.showReplies = true;
                this.repliesLoaded = true;
            }
        });
    },

    async toggleReplies() {
        if (!this.showReplies) {
            this.showReplies = true;

            // Load replies on first expand if not already loaded
            if (!this.repliesLoaded && this.repliesCount > 0) {
                await this.loadMoreReplies();
                this.repliesLoaded = true;
            }
        } else {
            this.showReplies = false;
        }
    },

    async loadMoreReplies() {
        if (this.loadingReplies) {
            return;
        }

        this.loadingReplies = true;
        const url = '/comments/' + commentSlug + '/replies?page=' + this.repliesPage;

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.replies.push(...data.replies);
                this.hasMoreReplies = data.has_more;
                this.repliesPage = data.next_page;
            }
        } catch (error) {
            console.error('Error loading replies:', error);
        } finally {
            this.loadingReplies = false;
        }
    }
});
