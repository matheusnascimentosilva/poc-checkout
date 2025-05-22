<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Meus Produtos</h1>
    <router-link href="/dashboard/products/create" class="btn btn-primary mb-4">Novo Produto</router-link>

    <table class="w-full table-auto">
      <thead>
        <tr><th>Nome</th><th>Preço</th><th>Ações</th></tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.name }}</td>
          <td>R$ {{ product.price }}</td>
          <td>
            <router-link :href="`/dashboard/products/${product.id}/edit`">Editar</router-link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({ products: Array })
</script>
