<template>
    <button
        v-if="status.is_liked"
        dusk="unlike-btn"
        @click="unlike(status)"
        class="btn btn-link"
    ><strong>
        <i class="fas fa-thumbs-up"></i>
        You Like
    </strong></button>
    <button
        v-else
        dusk="like-btn"
        @click="like(status)"
        class="btn btn-link"
    >
        <i class="far fa-thumbs-up"></i>
        Like
    </button>
</template>

<script>

export default {
    props: {
        status: {
            type: Object,
            required: true
        }
    },
    methods: {
        like(status) {
            axios.post(`/statuses/${status.id}/likes`)
                .then(response => {
                    status.is_liked = true;
                    status.likes_count++;
                })
                .catch(errors => {
                    console.log(errors.data)
                });
        },
        unlike(status) {
            axios.delete(`/statuses/${status.id}/likes`)
                .then(response => {
                    status.is_liked = false;
                    status.likes_count--;
                })
                .catch(errors => {
                    console.log(errors.data)
                });
        }
    }
}
</script>

<style scoped>

</style>
