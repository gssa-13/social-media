let user = document.head.querySelector('meta[name="user"]');

module.exports = {
    computed: {
        userAuthenticated() {
            return JSON.parse(user.content);
        },
        userIsAuthenticated() {
            return !! user.content;
        },
        isAGuest() {
            return ! this.userIsAuthenticated;
        }
    },
    methods: {
        redirectIfIsAGuest() {
            if(this.isAGuest) {
                return window.location.href = '/login';
            }
        }
    }
}
