<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Cadastrar Novo Produto</h1>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Nome</label>
        <input
          v-model="form.name"
          type="text"
          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2"
        />
        <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Descrição</label>
        <textarea
          v-model="form.description"
          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2"
        ></textarea>
        <p v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</p>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Preço (R$)</label>
        <input
          v-model="form.price"
          type="number"
          step="0.01"
          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2"
        />
        <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</p>
      </div>

      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Imagem (URL)</label>
        <input
          v-model="form.photo"
          type="text"
          placeholder="https://..."
          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2"
        />
        <p v-if="form.errors.photo" class="text-red-500 text-sm mt-1">{{ form.errors.photo }}</p>
      </div>

      <div class="flex justify-between items-center">
        <Link
          href="/products"
          class="text-gray-600 hover:underline"
        >
          ← Voltar
        </Link>

        <button
          type="submit"
          :disabled="form.processing"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Salvar
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  description: '',
  price: '',
  photo: '',
})

function submit() {
  form.post('/products')
}
</script>
