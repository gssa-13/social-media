<template>
    <div @click="redirectIfIsAGuest">
        <status-list-item v-for="status in statuses" :status="status" :key="status.id"/>
    </div>
</template>

<script>
import StatusListItem from "./StatusListItem";

export default {
    components:{
        StatusListItem
    },
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

}
</script>

<style scoped>

</style>
