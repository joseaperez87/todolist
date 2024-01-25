<script setup>
    import {ref} from "vue";
    import TodoListServices from "../services/TodoListServices";
    import {useRouter, useRoute} from "vue-router";

    const router = useRouter()
    const route = useRoute()

    const params = route.params
    const form = ref({title: "", description: "", end_date: ""})
    const working = ref(false)
    const edit = ref(false)
    const addError = ref("")
    const addSuccess = ref("")

    if(params.id){
        working.value = true
        edit.value = true
        TodoListServices.get(params.id).then(res => {
            working.value = false
            form.value = res
        })
    }

    function create(){
        working.value = true
        if (form.value.title.trim() == ""){
            working.value = false
            return alert("Заголовок обязательно")
        }

        TodoListServices.create(form.value).then(data => {
            working.value = false
            addSuccess.value = ""
            addError.value = ""
            if (data.res) {
                if(edit.value){
                    router.push({name: 'Info', params: {id: params.id}})
                }
                addSuccess.value = "Задача добавлена"
                form.value = {title: "", description: "", end_date: ""}
            } else {
                if (data.message)
                    return addError.value = data.message
                if (data.title) addError.value += '- ' + data.title[0] + '\n'
                if (data.description)
                    addError.value += '- ' + data.description[0] + '\n'
                if (data.end_date)
                    addError.value += '- ' + data.end_date[0] + '\n'
                if (addError == "")
                    addError.value = "Завершить регистрацию не удалось"
            }
        }).catch(e => {
            working.value = false
            console.log(e)
        })
    }
</script>

<template>
    <section class="container">
        <h1 v-if="!edit" class="text-center">Добавить задачу</h1>
        <h1 v-if="edit" class="text-center">Редактировать задачу {{params.id}}</h1>
        <form class="todolist-add__form mt-3" @submit.prevent="create">
            <div class="alert alert-danger" v-if="addError">
                {{ addError }}
            </div>
            <div class="alert alert-success" v-if="addSuccess">
                {{ addSuccess }}
            </div>
            <div class="form-floating">
                <input type="text" v-model="form.title" required id="floatingTitle" class="form-control title">
                <label for="floatingTitle">Заголовок</label>
            </div>
            <div class="form-floating">
                <textarea v-model="form.description" id="floatingDescription" rows="5" class="form-control description"></textarea>
                <label for="floatingDescription">Описание</label>
            </div>
            <div class="form-floating">
                <input type="date" v-model="form.end_date" id="floatingDate" class="form-control date">
                <label for="floatingDate">Дата окончания</label>
            </div>
            <button v-if="!edit" :disabled="working" class="btn btn-primary mt-1 w-100 py-2" type="submit">Добавить</button>
            <button v-if="edit" :disabled="working" class="btn btn-primary mt-1 w-100 py-2" type="submit">Редактировать</button>
        </form>
    </section>
</template>

<style scoped lang="sass">
.todolist-add
    &__form
        max-width: 400px
        margin-left: auto
        margin-right: auto
        .title
            margin-bottom: -1px
            border-bottom-right-radius: 0
            border-bottom-left-radius: 0
        .description
            border-radius: 0
            height: auto
        .date
            margin-bottom: 10px
            border-top-left-radius: 0
            border-top-right-radius: 0
</style>
