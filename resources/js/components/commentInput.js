export default (storeUrl, csrfToken) => ({
    content: '',
    gifUrl: '',
    selectedGif: null,
    parentCommentSlug: null,
    repliedToUsername: null,
    submitting: false,
    maxLength: 1000,

    init() {
        // Listen for reply-to-comment event
        window.addEventListener('reply-to-comment', (e) => {
            this.parentCommentSlug = e.detail.parentCommentSlug;
            this.repliedToUsername = e.detail.username;
            this.$refs.commentInput.focus();
        });
    },

    get remainingChars() {
        return this.maxLength - this.content.length;
    },

    clearReply() {
        this.parentCommentSlug = null;
        this.repliedToUsername = null;
    },

    removeGif() {
        this.selectedGif = null;
        this.gifUrl = '';
    },

    resetForm() {
        this.content = '';
        this.gifUrl = '';
        this.selectedGif = null;
        this.clearReply();
        this.$refs.commentInput.style.height = 'auto';
    },

    async postComment() {
        if (this.content.trim() === '' && this.gifUrl === '') {
            return;
        }

        if (this.submitting) {
            return;
        }

        this.submitting = true;

        const formData = {
            content: this.content || null,
            gif_url: this.gifUrl || null,
            parent_comment_slug: this.parentCommentSlug,
        };

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            const data = await response.json();

            if (data.success) {
                // Dispatch event to add comment to list
                window.dispatchEvent(new CustomEvent('comment-posted', {
                    detail: { comment: data.comment }
                }));

                // Reset form
                this.resetForm();
            }
        } catch (error) {
            console.error('Error posting comment:', error);
        } finally {
            this.submitting = false;
        }
    }
});
