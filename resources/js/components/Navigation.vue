<script setup>
import {authStore} from "../store";
import {useRouter} from "vue-router";

const auth = authStore()
const router = useRouter()


async function logout(){
    auth.logout().then(res => {
        if (res) {
            return router.go({name: 'Login'})
        }
    })
}
</script>

<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container align-content-center">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item" v-if="auth.user?.token">
                    <router-link to="/" class="nav-link">Список</router-link>
                </li>
                <li class="nav-item" v-if="auth.user?.token">
                    <router-link to="/add" class="nav-link">Добваить</router-link>
                </li>
            </ul>
            <div class="d-flex">
                <span class="d-flex" v-if="!auth.user?.token">
                    <router-link to="/register" class="text-decoration-none">Регистрация</router-link>&nbsp;|&nbsp;
                    <router-link to="/login" class="text-decoration-none">Вход</router-link>
                </span>
                <span v-if="auth.user?.token">{{auth.user.name}} <small class="text-black-50">({{auth.user.email}})</small></span>
                <button v-if="auth.user?.token" class="btn btn-link nav-link" @click="logout">&nbsp;Выход</button>
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
