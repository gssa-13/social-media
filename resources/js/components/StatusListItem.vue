<template>
    <div class="card mb-3 border-0 shadow-sm" >
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
                <img class="rounded-circle mr-3 shadow-sm" width="40px"
                     :src="status.user.user_avatar"
                     :alt="status.user.user_name">
                <div>
                    <h5 class="mb-1">
                        <a :href="status.user.user_link" v-text="status.user.user_name"></a>
                    </h5>
                    <div class="small text-muted" v-text="status.ago"></div>
                </div>
            </div>
            <p class="card-text text-secondary" v-text="status.body"></p>
        </div>
        <div class="card-footer p-2 d-flex justify-content-between align-items-center">
            <like-button dusk="like-btn"
                :model="status" :url="`/statuses/${status.id}/likes`"
            />
            <div class="text-secondary mr-2">
                <i class="far fa-thumbs-up"></i>
                <span dusk="likes-count">{{ status.likes_count }}</span>
            </div>
        </div>
        <div class="card-footer pb-0" v-if="userIsAuthenticated || status.comments.length">
            <comment-list :comments="status.comments" :status-id="status.id"/>
            <comment-form :status-id="status.id" />
        </div>
    </div>
</template>

<script>
import LikeButton from "./LikeButton";
import CommentList from "./CommentList";
import CommentForm from "./CommentForm";

export default {
    props: {
        status: {
            type: Object,
            required: true
        }
    },
    components: {
        LikeButton,
        CommentList,
        CommentForm
    }
}
</script>

<style scoped>

</style>
