<template>
    <div>
        <div v-if="localFriendshipStatus === 'pending'">
            <span v-text="sender.name"></span> has sent you a friend request
            <button dusk="accept-friendship" @click="acceptFriendshipRequest">Accept Request</button>
            <button dusk="deny-friendship" @click="denyFriendshipRequest">Deny Request</button>
        </div>
        <div v-else-if="localFriendshipStatus === 'accepted'" >
            <span v-text="sender.name"></span> is your friend
        </div>
        <div v-else-if="localFriendshipStatus === 'denied'" >
            <span v-text="sender.name"></span> request denied
        </div>
        <div v-if="localFriendshipStatus === 'deleted'">Request deleted</div>
        <button v-else dusk="delete-friendship" @click="deleteFriendship">Delete friendship</button>
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
