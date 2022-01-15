<template>
    <div @click="redirectIfIsAGuest">
        <status-list-item
            v-for="status in statuses"
            :status="status"
            :key="status.id"/>
    </div>
</template>

<script>
import StatusListItem from "./StatusListItem";

export default {
    components: { StatusListItem },
    props: {
        url: String
    },
    data() {
        return {
            statuses: []
        }
    },
    mounted() {
        axios.get(this.getUrl)
        .then(response => {
            this.statuses = response.data.data
        })
        .catch(errors => {
            console.log(errors.response.data)
        });

        EventBus.$on('status-created', status => {
            this.statuses.unshift(status);
        });

        Echo.channel('statuses').listen('StatusCreated', ({status}) => {
            this.statuses.unshift(status);
        });
    },
    computed: {
        getUrl () {
            return this.url ? this.url : '/statuses';
        }
    }
}
</script>

<style scoped>

</style>
