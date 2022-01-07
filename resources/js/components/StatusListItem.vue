<template>
    <div class="card mb-3 border-0 shadow-sm" >
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
                <img class="rounded-circle mr-3 shadow-sm" width="40px"
                     :src="status.user.avatar"
                     :alt="status.user.name">
                <div>
                    <h5 class="mb-1">
                        <a :href="status.user.link" v-text="status.user.name"></a>
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
        <div class="card-footer">
            <div v-for="comment in comments" class="mb-2">
                <div class="d-flex">
                    <img width="30px" height="30px" class="rounded shadow-sm mr-2" :src="comment.user.avatar" :alt="comment.user.name">
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
                        <like-button dusk="comment-like-btn"
                             :model="comment" :url="`/comments/${comment.id}/likes`"
                             class="comment-like-btn"
                        />
                    </div>
                </div>
            </div>
            <form @submit.prevent="addNewComment()" v-if="userIsAuthenticated">
                <div class="d-flex align-items-center">
                    <img width="30px" class="rounded shadow-sm mr-2"
                         :src="userAuthenticated.avatar"
                         :alt="userAuthenticated.name">
                    <div class="input-group">
                        <textarea
                            v-model="newComment" name="comment" class="form-control border-0 shadow-sm"
                            placeholder="Write a comment" rows="1" required
                        ></textarea>
                        <div class="input-group-append">
                            <button dusk="comment-btn" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LikeButton from "./LikeButton";

export default {
    props: {
        status: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            newComment: '',
            comments: this.status.comments
        }
    },
    methods: {
        addNewComment() {
            axios.post(`/statuses/${this.status.id}/comments`, {body: this.newComment})
            .then(response => {
                this.comments.push(response.data.data);
                this.newComment = '';
            })
            .catch(errors => {
                console.log(errors.response.data)
            });
        }
    },
    components: {
        LikeButton
    }
}
</script>

<style scoped>

</style>
