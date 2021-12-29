<template>
    <div>
        <form @submit.prevent="submit()">
            <div class="card-body">
                <textarea v-model="body" class="form-control border-0 bg-light" name="body" id="body" placeholder="What's happening?"></textarea>
            </div>
            <div class="card-footer">
                <button id="create-status" class="btn btn-primary">Publish</button>
            </div>
        </form>
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
