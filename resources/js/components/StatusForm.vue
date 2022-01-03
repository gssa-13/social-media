<template>
    <div>
        <form @submit.prevent="submit()" v-if="userIsAuthenticated">
            <div class="card-body">
                <textarea v-model="body" class="form-control border-0 bg-light" name="body"
                          id="body" :placeholder="`What's happening ${userAuthenticated.name} ?`"
                          required
                ></textarea>
            </div>
            <div class="card-footer">
                <button id="create-status" class="btn btn-primary">
                    Publish
                    <i class="fab fa-telegram-plane ml-1"></i>
                </button>
            </div>
        </form>
        <div class="card-body" v-else>
            You need <a href="/login">login</a> to post something
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            body: ''
        }
    },
    methods: {
        submit() {
            axios.post('/statuses', {body: this.body})
            .then(response => {
                EventBus.$emit('status-created', response.data.data);
                this.body = '';
            })
            .catch(errors => {
                console.log(errors.response.data);
            });
        }
    }
}
</script>

<style scoped>

</style>
