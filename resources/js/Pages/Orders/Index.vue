<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Pedidos Recebidos</h1>

    <div v-if="orders.length === 0" class="text-gray-500">Nenhum pedido ainda.</div>

    <div v-else class="space-y-4">
      <div
        v-for="order in orders"
        :key="order.id"
        class="bg-white p-4 shadow rounded border"
      >
        <h2 class="text-lg font-semibold">{{ order.product.name }}</h2>
        <p>Cliente: {{ order.customer_name }}</p>
        <p>Email: {{ order.customer_email }}</p>
        <p>Endereço: {{ order.customer_address }}</p>
        <p class="text-sm text-gray-400 mt-1">Pedido em: {{ formatDate(order.created_at) }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'

const { props } = usePage()
const orders = props.orders

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleString('pt-BR')
}
</script>
