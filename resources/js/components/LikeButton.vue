<template>
    <button
        @click="toggle()"
        :class="getBtnClasses"
    >
        <i :class="getIconClasses"></i>
        {{ getText }}
    </button>
</template>

<script>

export default {
    props: {
        model: {
            type: Object,
            required: true
        },
        url: {
            type: String,
            required: true
        }
    },
    methods: {
        toggle() {
            let method = this.model.is_liked ? 'delete' : 'post';
            axios[method](this.url)
                .then(response => {
                    this.model.is_liked = ! this.model.is_liked;
                    if(method === 'post') {
                        this.model.likes_count++;
                    } else {
                        this.model.likes_count--;
                    }
                })
                .catch(errors => {
                    console.log(errors.response.data)
                });
        }
    },
    computed: {
        getText() {
            return this.model.is_liked ? 'You Like' : 'Like';
        },
        getBtnClasses() {
            return [
                this.model.is_liked ? 'font-weight-bold' : '',
                'btn btn-link ',
            ]
        },
        getIconClasses() {
            return [
                this.model.is_liked ? 'fas ' : 'far ',
                'fa-thumbs-up mr-1'
            ]
        }
    }
}
</script>

<style lang="scss" scoped>
.comment-like-btn {
    font-size: 0.8em;
    padding-left: 0;
    i { display: none}
}
</style>
