<template>
  <div class="max-w-2xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">Editar Produto</h1>

    <form @submit.prevent="updateProduct" class="space-y-4">
      <div>
        <label class="block font-medium mb-1">Nome</label>
        <input v-model="form.name" type="text" class="w-full border rounded p-2" />
        <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
      </div>

      <div>
        <label class="block font-medium mb-1">Descrição</label>
        <textarea v-model="form.description" class="w-full border rounded p-2"></textarea>
        <span v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</span>
      </div>

      <div>
        <label class="block font-medium mb-1">URL da Foto</label>
        <input v-model="form.photo" type="text" class="w-full border rounded p-2" />
        <span v-if="form.errors.photo" class="text-red-500 text-sm">{{ form.errors.photo }}</span>
      </div>

      <div>
        <label class="block font-medium mb-1">Preço</label>
        <input v-model="form.price" type="number" step="0.01" class="w-full border rounded p-2" />
        <span v-if="form.errors.price" class="text-red-500 text-sm">{{ form.errors.price }}</span>
      </div>

      <div class="flex gap-4">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Salvar alterações
        </button>

        <Link :href="route('products.index')" class="text-gray-600 hover:underline">
          Cancelar
        </Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
  product: Object
})

const form = useForm({
  name: props.product.name,
  description: props.product.description,
  photo: props.product.photo,
  price: props.product.price
})

function updateProduct() {
  form.put(route('products.update', props.product.id))
}
</script>
