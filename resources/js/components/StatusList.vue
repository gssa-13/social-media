<template>
    <div @click="redirectIfIsAGuest">
        <transition-group name="status-list-transition">
            <status-list-item
                v-for="status in statuses"
                :status="status"
                :key="status.id"/>
        </transition-group>
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
    .status-list-transition-move {
        transition: all .9s;
    }
</style>
