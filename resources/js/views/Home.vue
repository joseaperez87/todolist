<script setup>
import {ref} from "vue";
import TodoListServices from "../services/TodoListServices";
import {authStore} from "../store/index.js";

const auth = authStore()
const list = ref([])
const working = ref(true)
const remError = ref("")

TodoListServices.getUserList().then(res => {
    working.value = false
    list.value = res
}).catch(e => {
    working.value = false
    console.log(e)
})

function remove(id, index){
    working.value = true
    TodoListServices.remove(id).then(res => {
        working.value = false
        if(res){
            list.value.splice(index, 1)
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
        <h1>TODO List</h1>
        <div class="alert alert-danger" v-if="remError">
            {{ remError }}
        </div>
        <div class="todo__list">
            <table class="table">
                <thead>
                <tr>
                    <th class="text-center">№</th>
                    <th class="text-center">Заголовок</th>
                    <th class="text-center">Описание</th>
                    <th class="text-center">Дата создания</th>
                    <th class="text-center">Дата окончания</th>
                    <th class="text-center">Действя</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="working && list.length == 0">
                    <td colspan="6" class="text-center text-black-50">
                        <strong>Поиск ...</strong>
                    </td>
                </tr>
                <tr v-for="(todo, index) in list" :id="'el'+index">
                    <th class="text-center">{{ index + 1 }}</th>
                    <td class="text-center"><router-link :to="{name: 'Info', params: {id:todo.id}}">{{ todo.title }}</router-link></td>
                    <td class="text-center">{{ todo.description }}</td>
                    <td class="text-center">{{ todo.created_at }}</td>
                    <td class="text-center">{{ todo.end_date }}</td>
                    <td class="text-center">
                        <router-link :disabled="working" class="btn btn-success btn-sm" :to="{name: 'Edit', params: {id:todo.id}}">
                            Изменить
                        </router-link>&nbsp;
                        <button :disabled="working" class="btn btn-danger btn-sm" @click="remove(todo.id, index)">Удалить</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<style scoped>

</style>
