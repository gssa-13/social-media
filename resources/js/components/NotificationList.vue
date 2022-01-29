<template>
    <li class="nav-item dropdown">
        <a href="#"
           dusk="notifications"
           :class="count ? 'text-primary font-weight-bold' : ''"
           role="button"
           aria-expanded="false"
           data-toggle="dropdown"
           class="nav-link dropdown-toggle"
           id="dropdownNotifications">
            <i class="fas fa-bell"></i>
            <span dusk="notifications-count">{{ count }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotifications">
            <div class="dropdown-header">Notifications</div>
            <notification-list-item
                v-for="notification in notifications"
                :notification="notification"
                :key="notification.id"
            />
        </ul>
    </li>
</template>

<script>
export default {
    data() {
        return {
            notifications: [],
            count: ''
        }
    },
    created() {
        axios.get('/notifications')
        .then( response => {
            this.notifications = response.data;
            this.unreadNotifications();
        })
        .catch( error => {
            console.log(error.data)
        });

        EventBus.$on('notification-read', () => {
            if ( this.count === 1)
            {
                return this.count = '';
            }
            this.count --;
        });

        EventBus.$on('notification-unread', () => {
            this.count ++;
        });
    },
    methods: {
        unreadNotifications() {
            this.count = this.notifications.filter(notification => {
                return notification.read_at === null;
            }).length || ''
        }
    }
}
</script>

<style scoped>

</style>
