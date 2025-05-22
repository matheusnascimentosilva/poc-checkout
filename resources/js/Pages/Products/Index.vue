<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Meus Produtos</h1>

    <div class="mb-4">
      <Link
        href="/products/create"
        class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        + Novo Produto
      </Link>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full table-auto border border-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 text-left">Nome</th>
            <th class="p-2 text-left">Descrição</th>
            <th class="p-2 text-left">Preço</th>
            <th class="p-2 text-left">Imagem</th>
            <th class="p-2 text-left">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id" class="border-t">
            <td class="p-2">{{ product.name }}</td>
            <td class="p-2">{{ product.description }}</td>
            <td class="p-2">R$ {{ Number(product.price).toFixed(2) }}</td>
            <td class="p-2">
              <img :src="product.photo" alt="Imagem" class="w-16 h-16 object-cover rounded" />
            </td>
            <td class="p-2 space-x-2">
              <Link
                :href="`/products/${product.id}/edit`"
                class="text-blue-600 hover:underline"
              >
                Editar
              </Link>
              <button
                @click="deleteProduct(product.id)"
                class="text-red-600 hover:underline"
              >
                Excluir
              </button>
            </td>
          </tr>
          <tr v-if="products.length === 0">
            <td colspan="5" class="p-4 text-center text-gray-500">
              Nenhum produto encontrado.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  products: Array
})

function deleteProduct(id) {
  if (confirm('Tem certeza que deseja excluir este produto?')) {
    router.delete(`/products/${id}`)
  }
}
</script>
