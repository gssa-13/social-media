require('./bootstrap');

import Vue from 'vue';

Vue.component('status-form', require('./components/StatusForm.vue').default);

const app = new Vue({

}).$mount('#app');
