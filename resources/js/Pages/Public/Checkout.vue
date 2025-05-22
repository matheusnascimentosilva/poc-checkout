<template>
  <div class="max-w-2xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Finalizar Compra</h1>

    <div class="bg-white p-4 rounded shadow mb-6">
      <img :src="product.photo" class="w-full h-64 object-cover rounded mb-4" />
      <h2 class="text-xl font-semibold">{{ product.name }}</h2>
      <p class="text-gray-700">{{ product.description }}</p>
      <p class="text-lg font-bold mt-2">R$ {{ product.price.toFixed(2) }}</p>
    </div>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1">Nome</label>
        <input v-model="form.name" type="text" class="w-full border p-2 rounded" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input v-model="form.email" type="email" class="w-full border p-2 rounded" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Endereço</label>
        <textarea v-model="form.address" class="w-full border p-2 rounded"></textarea>
      </div>

      <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
        Finalizar Pedido
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { usePage } from '@inertiajs/inertia-vue3'

const { props } = usePage()
const product = props.product

const form = useForm({
  name: '',
  email: '',
  address: ''
})

const submit = () => {
  form.post(`/buy/${product.uuid}`)
}
</script>
