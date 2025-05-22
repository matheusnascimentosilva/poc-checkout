// template para tela de registro de vendedor

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <form @submit.prevent="register" class="bg-white p-6 rounded shadow-md w-full max-w-sm">
            <h2 class="text-xl font-bold mb-4">Registro do Vendedor</h2>
            <input v-model="name" type="text" placeholder="Nome" class="input mb-2 w-full" />
            <input v-model="email" type="email" placeholder="E-mail" class="input mb-2 w-full" />
            <input v-model="password" type="password" placeholder="Senha" class="input mb-4 w-full" />
            <button class="btn btn-primary w-full" type="submit">Registrar</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3'

const { data, post } = useForm({
    name: '',
    email: '',
    password: ''
})
const name = ref(data.name)
const email = ref(data.email)
const password = ref(data.password)

function register() {
    post('/register', {
        name: name.value,
        email: email.value,
        password: password.value
    })
}
</script>
