import {createRouter, createWebHistory} from "vue-router";

import Home from './views/Home.vue';
import Login from "./views/Login.vue";
import Register from "./views/Register.vue";
import AddTodo from "./views/AddTodo.vue";
import Info from "./views/Info.vue";
import NotFound from "./views/404.vue"

import {authStore} from "./store";

const routes = [
    {
        path: '/',
        name: 'MainPage',
        component: Home,
        meta: {requiresAuth: true}
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {requiresAuth: false}
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: {requiresAuth: false}
    },
    {
        path: '/add',
        name: 'Add',
        component: AddTodo,
        meta: {requiresAuth: true}
    },
    {
        path: '/edit/:id',
        name: 'Edit',
        component: AddTodo,
        meta: {requiresAuth: true}
    },
    {
        path: '/info/:id',
        name: 'Info',
        component: Info,
        meta: {requiresAuth: true}
    },
    {
        path: '/:catchAll(.*)',
        component: NotFound,
        hidden: true
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const store = authStore();
    if (to.meta.requiresAuth && (!store.user || !store.user?.token)) {
        next({name: 'Login'});
    } else if (store.user?.token && !to.meta.requiresAuth) {
        next({name: 'MainPage'});
    } else {
        next();
    }
});

export default router
