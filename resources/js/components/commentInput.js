export default (storeUrl, csrfToken) => ({
    content: '',
    gifUrl: '',
    selectedGif: null,
    parentCommentId: null,
    repliedToUserId: null,
    replyingToUsername: null,
    submitting: false,
    maxLength: 1000,

    init() {
        // Listen for reply-to-comment event
        window.addEventListener('reply-to-comment', (e) => {
            this.parentCommentId = e.detail.parentCommentId;
            this.repliedToUserId = e.detail.userId;
            this.replyingToUsername = e.detail.username;
            this.$refs.commentInput.focus();
        });

        // Listen for gif-selected event
        window.addEventListener('gif-selected', (e) => {
            this.selectedGif = e.detail;
            this.gifUrl = e.detail.original_url;
        });
    },

    get remainingChars() {
        return this.maxLength - this.content.length;
    },

    clearReply() {
        this.parentCommentId = null;
        this.repliedToUserId = null;
        this.replyingToUsername = null;
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
            parent_comment_id: this.parentCommentId,
            replied_to_user_id: this.repliedToUserId,
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
