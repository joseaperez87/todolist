<script setup>
import {ref} from "vue";
import {authStore} from "../store"
import {useRouter} from "vue-router"

const auth = authStore()
const router = useRouter()

const loginin = ref(false)
const loginError = ref("")
const form = ref({email: "", password: ""})
const showPassword = ref('password')

async function login() {
    loginin.value = true
    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form.value.email)) {
        loginin.value = false
        return alert("Неправильная почта")
    }
    if (form.value.password.trim() == "") {
        loginin.value = false
        return alert("Пароль обязательно")
    }

    auth.login(form.value).then((data) => {
        if (data.token) {
            auth.updateUserInfo()
            return router.push('/')
        } else {
            loginin.value = false
            loginError.value = ""
            if (data.message)
                return loginError.value = data.message
            if (data.error)
                return loginError.value = data.error

            if (data.password)
                loginError.value += '- ' + data.password[0] + '\n'
            if (data.email)
                loginError.value += '- ' + data.email[0] + '\n'

            if (loginError == "")
                loginError.value = "Завершить вход не удалось"
        }
    }).catch(e => {
        loginin.value = false
        console.log(e)
    })
}
</script>

<template>
    <section class="container">
        <form class="sign-in__form" @submit.prevent="login">
            <h1 class="h3 mb-3 fw-normal">Вход</h1>
            <div class="alert alert-danger" v-if="loginError">
                {{ loginError }}
            </div>
            <div class="form-floating">
                <input type="email" class="form-control" v-model="form.email" id="floatingInput"
                       placeholder="name@example.com">
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating">
                <input :type="showPassword" class="form-control" v-model="form.password" id="floatingPassword"
                       placeholder="Password">
                <label for="floatingPassword">Пароль</label>
                <button
                    class="btn btn-link btn-sm text-decoration-none"
                    type="button" @click="showPassword = showPassword == 'text' ? 'password' : 'text'">
                    Показывать пароль
                </button>
            </div>
            <button :disabled="loginin" class="btn btn-primary mt-1 w-100 py-2" type="submit">Войти</button>
            <div class="text-center mt-2">
                <router-link to="/register" class="text-decoration-none">Регистрация</router-link>
            </div>
        </form>
    </section>
</template>

<style scoped lang="sass">
.sign-in
    &__form
        max-width: 400px
        margin-left: auto
        margin-right: auto

        input[type="email"]
            margin-bottom: -1px
            border-bottom-right-radius: 0
            border-bottom-left-radius: 0

        input[type="password"]
            margin-bottom: 10px
            border-top-left-radius: 0
            border-top-right-radius: 0

</style>
