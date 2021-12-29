<template>
    <div>
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
    }
}
</script>

<style scoped>

</style>
