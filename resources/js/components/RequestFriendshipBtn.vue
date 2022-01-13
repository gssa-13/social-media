<template>
    <button @click="toggleFriendshipRequest()"
            class="btn btn-primary"
    >
        {{ getText }}
    </button>
</template>

<script>
export default {
    props: {
        recipient: {
            type: Object,
            required: true
        },
        friendshipStatus: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            localFriendshipStatus: this.friendshipStatus
        }
    },
    methods: {
        toggleFriendshipRequest() {
            this.redirectIfIsAGuest();

            let method = this.getMethod();

            axios[method](`/friendships/${this.recipient.name}`)
            .then(response => {
                this.localFriendshipStatus = response.data.friendship_status;
            })
            .catch(error => {
                console.log(error.response.data);
            });
        },
        getMethod() {
            if (this.localFriendshipStatus === 'pending' || this.localFriendshipStatus === 'accepted') {
                return 'delete';
            }
            return 'post';
        }
    },
    computed: {
        getText() {
            if (this.localFriendshipStatus === 'pending') {
                return 'Cancel request';
            }
            if (this.localFriendshipStatus === 'accepted') {
                return 'Remove from my friends';
            }
            if (this.localFriendshipStatus === 'denied') {
                return 'Request denied';
            }
            return 'Send friend request';
        }
    }
}
</script>

<style scoped>

</style>
