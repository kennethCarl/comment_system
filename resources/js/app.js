/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import VueRouter from 'vue-router';
import Vuex, {mapState} from 'vuex';
import 'es6-promise/auto';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import VuexPersistence from 'vuex-persist';
import UserComponent from './components/user';
import PostComponent from "../js/components/post";
import VueMoment from 'vue-moment';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueMoment);

Vue.component('user', UserComponent);
Vue.component('post', PostComponent);

const vuexLocal = new VuexPersistence({
    storage: window.sessionStorage,
    key: 'comment-system',
    supportCircular: true
})

const defaultState = {
    selectedRoute: '/user',
    user: {alias: '', avatar: 'male1.png'},
    commentsCount: 0,
};


const store = new Vuex.Store({
    plugins: [vuexLocal.plugin],
    state: {
        ...defaultState
    },
    getters: {
        selectedRoute: state => {
            return state.selectedRoute;
        },
        user: state => {
            return state.user;
        },
        commentsCount: state => {
            return state.commentsCount;
        }
    },
    mutations: {
        resetState: (state) => {
            Object.assign(state, defaultState);
        },
        setSelectedRoute: (state, payload) => {
            state.selectedRoute = payload;
        },
        setUser: (state, payload) => {
            state.user = payload;
        },
        setCommentsCount: (state, payload) => {
            state.commentsCount = payload;
        }
    },
    actions: {
        resetState: (context) => {
            context.commit("resetState");
        },
        setSelectedRoute: (context, payload) => {
            context.commit("setSelectedRoute", payload);
        },
        setUser: (context, payload) => {
            context.commit("setUser", payload);
        },
        setCommentsCount: (context, payload) => {
            context.commit("setCommentsCount", payload);
        }
    }
})

const routes = [
    {
        path: '/user',
        name: 'user',
        component: UserComponent
    },
    {
        path: '/post',
        name: 'management',
        component: PostComponent
    },
    {
        path: '*',
        name: 'notfound',
        component: UserComponent
    },
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});


const app = new Vue({
    el: '#app',
    router,
    store,
    mounted() {
        this.$router.push(this.$store.state.selectedRoute);
    }
});
