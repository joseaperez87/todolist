<script setup>
import {ref} from "vue"
import {authStore} from "../store"
import {useRouter} from "vue-router";

const auth = authStore()
const router = useRouter()

const showPassword = ref('password')
const form = ref({name: "", email: "", password: "", password_confirmation: ""})
const registering = ref(false)
const regError = ref("")

async function register() {
    registering.value = true
    if (form.value.name.trim() == "") {
        registering.value = false
        return alert("Имя обязательно")
    }
    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form.value.email)) {
        registering.value = false
        return alert("Неправильная почта")
    }
    if (form.value.password.trim() == "") {
        registering.value = false
        return alert("Пароль обязательно")
    }
    if (form.value.password != form.value.password_confirmation) {
        registering.value = false
        return alert("Пароли не совпадают")
    }

    auth.register(form.value).then(data => {
        registering.value = false
        if (data.result) {
            return router.push('/');
        } else {
            regError.value = ""
            if (data.message)
                return regError.value = data.message
            if (data.password)
                regError.value += '- ' + data.password[0] + '\n'
            if (data.email)
                regError.value += '- ' + data.email[0] + '\n'
            if (data.name)
                regError.value += '- ' + data.name[0] + '\n'
            if (regError == "")
                regError.value = "Завершить регистрацию не удалось"
        }
    }).catch(e => {
        registering.value = false
        regError.value = e.Message
    })
}
</script>

<template>
    <section class="container">
        <form class="register__form" @submit.prevent="register">
            <h1 class="h3 mb-3 fw-normal">Регистрация</h1>
            <div class="alert alert-danger" v-if="regError">
                {{ regError }}
            </div>
            <div class="form-floating">
                <input type="text" v-model="form.name" required id="floatingName" class="form-control name"
                       placeholder="name@example.com">
                <label for="floatingName">Имя</label>
            </div>

            <div class="form-floating">
                <input type="email" required v-model="form.email" id="floatingEmail" class="form-control email"
                       placeholder="name@example.com" aria-describedby="email">
                <label for="floatingEmail">Email</label>
            </div>
            <div class="form-floating">
                <input :type="showPassword" required v-model="form.password" id="floatingPass" class="form-control pass"
                       placeholder="Password">
                <label for="floatingPass">Пароль</label>
            </div>
            <div class="form-floating">
                <input :type="showPassword" v-model="form.password_confirmation" id="floatingCPass"
                       class="form-control cpass" placeholder="Password">
                <label for="floatingCPass">Подтвердить пароль</label>
                <button
                    class="btn btn-link btn-sm text-decoration-none"
                    type="button" @click="showPassword = showPassword == 'text' ? 'password' : 'text'">
                    Показывать пароль
                </button>
            </div>
            <button :disabled="registering" class="btn btn-primary mt-1 w-100 py-2" type="submit">Зарегистрироваться
            </button>
            <div class="text-center mt-2">
                <router-link to="/login" class="text-decoration-none">Вход</router-link>
            </div>
        </form>
    </section>
</template>

<style scoped lang="sass">
.register
    &__form
        max-width: 400px
        margin-left: auto
        margin-right: auto

        .name
            margin-bottom: -1px
            border-bottom-right-radius: 0
            border-bottom-left-radius: 0

        .email, .pass
            border-radius: 0

        .cpass
            margin-bottom: 10px
            border-top-left-radius: 0
            border-top-right-radius: 0

</style>
