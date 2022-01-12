require('./bootstrap');

import Vue from 'vue';
import auth from './mixins/auth';
Vue.mixin(auth);

window.EventBus = new Vue();
Vue.component('status-form', require('./components/StatusForm.vue').default);
Vue.component('status-list', require('./components/StatusList.vue').default);
Vue.component('request-friendship-btn', require('./components/RequestFriendshipBtn').default);
Vue.component('accept-friendship-btn', require('./components/AcceptFriendshipBtn').default);

const app = new Vue({

}).$mount('#app');
