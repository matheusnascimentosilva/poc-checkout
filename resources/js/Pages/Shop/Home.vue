<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-4">
    <div v-for="product in products" :key="product.id" class="bg-white p-4 rounded shadow">
      <img :src="product.image_url" alt="" class="w-full h-40 object-cover mb-2 rounded" />
      <h2 class="text-lg font-bold">{{ product.name }}</h2>
      <p class="text-gray-600 mb-2">{{ product.description }}</p>
      <p class="font-bold mb-2">R$ {{ product.price }}</p>
      <router-link :href="`/product/${product.id}`" class="btn btn-primary w-full">Ver Produto</router-link>
    </div>
  </div>
</template>

<script setup>
defineProps({ products: Array })
</script>
