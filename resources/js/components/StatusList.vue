<template>
    <div @click="redirectIfIsAGuest">
        <div class="card mb-3 border-0 shadow-sm" v-for="status in statuses">
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
                <div class="text-secondary mr-2">
                    <i class="far fa-thumbs-up"></i>
                    <span dusk="likes-count">{{ status.likes_count }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            statuses: []
        }
    },
    mounted() {
        axios.get('/statuses')
        .then(response => {
            this.statuses = response.data.data
        })
        .catch(errors => {
            console.log(errors.response.data)
        });
        EventBus.$on('status-created', status => {
            this.statuses.unshift(status);
        });
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
