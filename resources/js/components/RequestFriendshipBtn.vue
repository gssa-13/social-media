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
        }
    },
    data: () => ({
        friendshipStatus: ''
    }),
    created() {
        axios.get(`/friendships/${this.recipient.name}`)
        .then(response => {
            this.friendshipStatus = response.data.friendship_status;
        })
        .catch(errors => {
            console.log(errors.data)
        });
    },
    methods: {
        toggleFriendshipRequest() {
            this.redirectIfIsAGuest();

            let method = this.getMethod();

            axios[method](`/friendships/${this.recipient.name}`)
            .then(response => {
                this.friendshipStatus = response.data.friendship_status;
            })
            .catch(error => {
                console.log(error.response.data);
            });
        },
        getMethod() {
            if (this.friendshipStatus === 'pending' || this.friendshipStatus === 'accepted') {
                return 'delete';
            }
            return 'post';
        }
    },
    computed: {
        getText() {
            if (this.friendshipStatus === 'pending') {
                return 'Cancel request';
            }
            if (this.friendshipStatus === 'accepted') {
                return 'Remove from my friends';
            }
            if (this.friendshipStatus === 'denied') {
                return 'Request denied';
            }
            return 'Send friend request';
        }
    }
}
</script>

<style scoped>

</style>
