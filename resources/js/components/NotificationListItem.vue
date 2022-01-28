<template>
    <li class="d-flex align-items-center"
        :class="isRead ? '' : 'bg-light'">
        <a class="dropdown-item"
           :dusk="notification.id"
           :href="notification.data.link">
            {{ notification.data.message }}
        </a>
        <button v-if="isRead"
                :dusk="`mark-as-unread-${notification.id}`"
                @click.stop="markAsUnread"
                class="btn btn-link mr-2">
            <i class="far fa-circle"></i>
            <span class="position-absolute bg-dark text-white ml-2 py-1 px-2 rounded">Mark as unread</span>
        </button>
        <button v-else
                :dusk="`mark-as-read-${notification.id}`"
                @click.stop="markAsRead"
                class="btn btn-link mr-2">
            <i class="fas fa-circle"></i>
            <span class="position-absolute bg-dark text-white ml-2 py-1 px-2 rounded">Mark as read</span>
        </button>

    </li>
</template>

<script>
export default {
    props: {
        notification : {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            isRead: !! this.notification.read_at
        }
    },
    methods: {
        markAsRead() {
            axios.post(`/read-notifications/${this.notification.id}`)
            .then(response => {
                this.isRead = true;
                EventBus.$emit('notification-read');
            })
            .catch(error => {
                console.log(error.data);
            })
        },
        markAsUnread() {
            axios.delete(`/read-notifications/${this.notification.id}`)
                .then(response => {
                    this.isRead = false;
                    EventBus.$emit('notification-unread');
                })
                .catch(error => {
                    console.log(error.data);
                })
        }
    }
}
</script>

<style lang="scss" scoped>
    button > span {
        display: none;
    }
    button i {
        &:hover {
            & + span {
                display: inline;
            }
        }
    }
</style>
