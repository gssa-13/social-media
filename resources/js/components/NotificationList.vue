<template>
    <li class="nav-item dropdown">
        <a href="#"
           dusk="notifications"
           role="button"
           aria-expanded="false"
           data-toggle="dropdown"
           class="nav-link dropdown-toggle"
           id="dropdownNotifications">
            <i class="fas fa-bell"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotifications">
            <li v-for="notification in notifications">
                <a class="dropdown-item"
                   :dusk="notification.id"
                   :href="notification.data.link">
                    {{ notification.data.message }}
                </a>
            </li>
        </ul>
    </li>
</template>

<script>
export default {
    data() {
        return {
            notifications: []
        }
    },
    created() {
        axios.get('/notifications')
        .then( response => {
            this.notifications = response.data;
        })
        .catch( error => {

        });
    }
}
</script>

<style scoped>

</style>
