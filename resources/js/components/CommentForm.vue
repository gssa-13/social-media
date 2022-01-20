<template>
    <form @submit.prevent="addNewComment()" v-if="userIsAuthenticated" class="mb-3">
        <div class="d-flex align-items-center">
            <img width="30px" class="rounded shadow-sm mr-2"
                 :src="userAuthenticated.avatar"
                 :alt="userAuthenticated.name">
            <div class="input-group">
                <textarea v-model="newComment" name="comment"
                    placeholder="Write a comment" rows="1" required
                    class="form-control border-0 shadow-sm"
                ></textarea>
                <div class="input-group-append">
                    <button dusk="comment-btn" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </form>
    <div v-else class="mb-3 text-center">
        You need <a href="/login">login</a> to comment
    </div>
</template>

<script>
export default {
    props: {
        statusId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            newComment: ''
        }
    },
    methods: {
        addNewComment() {
            axios.post(`/statuses/${this.statusId}/comments`, {body: this.newComment})
                .then(response => {
                    this.newComment = '';
                    EventBus.$emit(`statuses.${this.statusId}.comments`, response.data.data);
                })
                .catch(errors => {
                    console.log(errors.response.data)
                });
        }
    },
}
</script>
