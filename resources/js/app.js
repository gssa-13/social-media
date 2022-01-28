require('./bootstrap');

import Vue from 'vue';
import auth from './mixins/auth';
Vue.mixin(auth);

window.EventBus = new Vue();
Vue.component('status-form', require('./components/StatusForm.vue').default);
Vue.component('status-list', require('./components/StatusList.vue').default);
Vue.component('status-list-item', require('./components/StatusListItem.vue').default);
Vue.component('request-friendship-btn', require('./components/RequestFriendshipBtn').default);
Vue.component('accept-friendship-btn', require('./components/AcceptFriendshipBtn').default);
Vue.component('notification-list', require('./components/NotificationList').default);
Vue.component('notification-list-item', require('./components/NotificationListItem').default);

const app = new Vue({

}).$mount('#app');
