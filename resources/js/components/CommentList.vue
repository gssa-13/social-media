<template>
    <div>
        <div v-for="comment in comments" class="mb-2">
            <div class="d-flex">
                <img width="30px" height="30px" class="rounded shadow-sm mr-2" :src="comment.user.user_avatar" :alt="comment.user.name">
                <div class="flex-grow-1">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-2 text-secondary">
                            <a :href="comment.user.link">{{ comment.user.name }}</a>
                            {{ comment.body }}
                        </div>
                    </div>
                    <small class="float-right badge badge-primary py-1 px-2 mt-1"
                           dusk="comment-likes-count">
                        <i class="fas fa-thumbs-up mr-1"></i>
                        {{ comment.likes_count }}
                    </small>
                    <like-button
                        dusk="comment-like-btn"
                         :model="comment" :url="`/comments/${comment.id}/likes`"
                         class="comment-like-btn"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LikeButton from "./LikeButton";
    export default {
        props: {
            comments: {
                type: Array,
                required: true
            },
            statusId: {
                type: Number,
                required: true
            }
        },
        mounted() {
            Echo.channel(`statuses.${this.statusId}.comments`)
                .listen('CommentCreated', ({comment}) => {
                    this.comments.push(comment);
                });
            EventBus.$on(`statuses.${this.statusId}.comments`, comment => {
                this.comments.push(comment);
            });
        },
    }
</script>

<style scoped>

</style>
