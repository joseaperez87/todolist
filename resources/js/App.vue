<script setup>
import Navigation from "./components/Navigation.vue";
import {authStore} from "./store";
import {useRoute} from "vue-router";

const route = useRoute()
const auth = authStore()

if (auth.user?.token) {
    auth.updateUserInfo()
}
</script>

<template>
    <Navigation/>
    <router-view :key="route.fullPath"></router-view>
</template>
