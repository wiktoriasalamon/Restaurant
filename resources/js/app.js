/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import 'bootstrap';
import Vue from 'vue'
import Vuetify from 'vuetify';
import vuetifyPL from 'vuetify/lib/locale/pl';
import vuetifyEn from 'vuetify/lib/locale/en';
import 'vuetify/dist/vuetify.min.css'
import Toasted from 'vue-toasted';


require('./bootstrap');

window.Vue = require('vue');


const opts = {
    iconfont: 'md, mdi',
    breakpoint: {
        thresholds: {
            xs: 360,
            sm: 500,
            md: 839,
            lg: 1024,
            xl: 1199,
        },
        scrollbarWidth: 10,
    },
    lang: {
        locales: {vuetifyPL, vuetifyEn},
        current: 'vuetifyPL',
    },
};

Vue.use(Vuetify);
const Options = {
    position: 'top-center'
};
Vue.use(Toasted, Options);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('chat', require('./components/chat.vue').default);
Vue.component('homepage', require('./components/homepage.vue').default);

//PARTIALS
Vue.component('ui-header', require('./components/partials/ui-header').default);
Vue.component('ui-footer', require('./components/partials/ui-footer').default);

//AUTH
Vue.component('login-form', require('./components/auth/login-form').default);
Vue.component('reset-password-form', require('./components/auth/reset-password-form').default);
Vue.component('loginpage', require('./components/auth/loginpage').default);
Vue.component('registerpage', require('./components/auth/registerpage').default);
Vue.component('forgot-password-mail', require('./components/auth/forgot-password-mail').default);


//RESERVATION

Vue.component('user-create-reservation', require('./components/reservation/user-create-reservation').default);
Vue.component('user-index-reservation', require('./components/reservation/user-index-reservation').default);
Vue.component('waiter-create-reservation', require('./components/reservation/waiter-create-reservation').default);
Vue.component('waiter-index-reservation', require('./components/reservation/waiter-index-reservation').default);


//MENU

Vue.component('user-menu', require('./components/menu/user-menu').default);
Vue.component('admin-menu', require('./components/menu/admin-menu').default);

//TABLES
Vue.component('admin-tables-index', require('./components/tables/admin-tables-index').default);

//USERS
Vue.component('my-account', require('./components/users/myAccount').default);


//WORKERS
Vue.component('workers-index', require('./components/workers/workers-index').default);
Vue.component('workers-create', require('./components/workers/workers-create').default);
Vue.component('workers-edit', require('./components/workers/workers-edit').default);





const app = new Vue({
    el: '#app',
    data: {
        messages: []
    },

    created() {
        this.fetchMessages();
        Echo.channel('chat')
          .listen('MessageSent', (e) => {
              this.messages.push({
                  message: e.message.message,
                  user: e.user
              });
          });


    },

    methods: {
        fetchMessages: function () {
            axios.get('/messages').then(response => {
                this.messages = response.data;
            });
        },

        addMessage(message) {
            this.messages.push(message);

            axios.post('/messages', message).then(response => {
                console.log(response.data);
            });
        }
    },
    vuetify: new Vuetify(opts),
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
