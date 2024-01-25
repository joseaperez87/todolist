<script setup>
import {useRoute, useRouter} from "vue-router";
import TodoListServices from "../services/TodoListServices.js";
import {ref} from "vue";

const route = useRoute()
const router = useRouter()

const params = route.params
const info = ref({})
const working = ref(true)

if (params.id) {
    TodoListServices.get(params.id).then(res => {
        working.value = false
        if(res.id) {
            info.value = res
        } else {
            router.push('/')
        }
    })
}else{
    router.push('/')
}
function remove(id){
    working.value = true
    TodoListServices.remove(id).then(res => {
        working.value = false
        if(res){
            router.push('/');
        }else{
            remError.value = "Задачу не удалось удалить"
        }
    }).catch(e => {
        working.value = false
        console.log(e)
    })
}

</script>

<template>
    <section class="container">
        <h1>Информация о задаче <span v-if="info.id">{{ info.id }}</span><span v-else>...</span></h1>
        <div class="container mt-5">
            <div v-if="info.id">
                <router-link :class="{'disabled' : working }" class="btn btn-success btn-sm" :to="{name: 'Edit', params: {id:info.id}}">Редактировать</router-link>&nbsp;
                <button :disabled="working" class="btn btn-danger btn-sm" @click="remove(info.id)">Удалить</button>
            </div>
            <div><strong>Заголовок:</strong><p><span v-if="info.id">{{ info.title }}</span><span v-else>...</span></p></div>
            <div><strong>Описание:</strong><p><span v-if="info.id">{{ info.description }}</span><span v-else>...</span></p></div>
            <div><strong>Дата создания:</strong>&nbsp;<span v-if="info.id">{{ info.end_date }}</span><span v-else>...</span></div>
            <div><strong>Дата окончания:</strong>&nbsp;<span v-if="info.id">{{ info.created_at }}</span><span v-else>...</span></div>
        </div>
    </section>
</template>

<style scoped class="sass">

</style>
