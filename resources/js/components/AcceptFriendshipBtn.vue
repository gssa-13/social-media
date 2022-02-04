<template>
    <div class="d-flex justify-content-between bg-light p3 rounded mb-3 shadow-sm">
        <div>
            <div v-if="localFriendshipStatus === 'pending'">
                <span v-text="sender.name"></span> has sent you a friend request
            </div>
            <div v-if="localFriendshipStatus === 'accepted'" >
                <span v-text="sender.name"></span> is your friend
            </div>
            <div v-if="localFriendshipStatus === 'denied'" >
                <span v-text="sender.name"></span> request denied
            </div>
            <div v-if="localFriendshipStatus === 'deleted'">
                Request from <span v-text="sender.name"></span> is deleted
            </div>
        </div>
        <div>
            <button class="btn btn-sm btn-primary" dusk="accept-friendship" @click="acceptFriendshipRequest"
                    v-if="localFriendshipStatus === 'pending'">
                Accept Request
            </button>
            <button class="btn btn-sm btn-warning" dusk="deny-friendship" @click="denyFriendshipRequest"
                    v-if="localFriendshipStatus === 'pending'">
                Deny Request
            </button>
            <button class="btn btn-sm btn-danger" dusk="delete-friendship" @click="deleteFriendship"
                    v-if="localFriendshipStatus !== 'deleted'">
                Delete friendship
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        sender: {
            type: Object,
            required: true
        },
        friendshipStatus: {
            type: String,
            required: true
        }
    },
    data(){
        return {
            localFriendshipStatus: this.friendshipStatus
        }
    },
    methods: {
        acceptFriendshipRequest(){
            axios.post(`/accept-friendships/${this.sender.name}`)
                .then(res => {
                    this.localFriendshipStatus = res.data.friendship_status;
                })
                .catch(err => {
                    console.log(err.response.data);
                })
        },
        denyFriendshipRequest(){
            axios.delete(`/accept-friendships/${this.sender.name}`)
                .then(res => {
                    this.localFriendshipStatus = res.data.friendship_status;
                })
                .catch(err => {
                    console.log(err.response.data);
                })
        },
        deleteFriendship(){
            axios.delete(`/friendships/${this.sender.name}`)
                .then(res => {
                    this.localFriendshipStatus = res.data.friendship_status;
                })
                .catch(err => {
                    console.log(err.response.data);
                })
        },
    }
}
</script>

<style scoped>

</style>
