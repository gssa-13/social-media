<template>
    <div class="card mb-3 border-0 shadow-sm" >
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
                <img class="rounded-circle mr-3 shadow-sm" width="40px" src="https://images.pexels.com/photos/3118694/pexels-photo-3118694.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" alt="">
                <div>
                    <h5 class="mb-1" v-text="status.user_name"></h5>
                    <div class="small text-muted" v-text="status.ago"></div>
                </div>
            </div>
            <p class="card-text text-secondary" v-text="status.body"></p>
        </div>
        <div class="card-footer p-2 d-flex justify-content-between align-items-center">
            <like-button :status="status"/>
            <div class="text-secondary mr-2">
                <i class="far fa-thumbs-up"></i>
                <span dusk="likes-count">{{ status.likes_count }}</span>
            </div>
        </div>
        <div class="card-footer">
            <div v-for="comment in comments" class="mb-2">
                <img width="30px" class="rounded shadow-sm float-left mr-2" :src="comment.user_avatar" :alt="comment.user_name">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-2 text-secondary">
                        <a href="javascript:void(0);">{{ comment.user_name }}</a>
                        {{ comment.body }}
                    </div>
                </div>
            </div>
            <form @submit.prevent="addNewComment()" v-if="userIsAuthenticated">
                <div class="d-flex align-items-center">
                    <img width="30px" class="rounded shadow-sm mr-2"
                         src="https://images.pexels.com/photos/3118694/pexels-photo-3118694.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
                         alt="currentUser.name">
                    <div class="input-group">
                        <textarea
                            v-model="newComment" name="comment" class="form-control border-0 shadow-sm"
                            placeholder="Write a comment" rows="1"
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
                console.log(errors.data)
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
