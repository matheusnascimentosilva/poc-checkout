<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import Pagination from '@/Components/Pagination.vue'
import Modal from '@/Components/Modal.vue'
import {
  Clock, CheckCircle, Truck, Home, XCircle
} from 'lucide-vue-next'

const props = defineProps({
  orders: Object,
  filters: Object
})

const iconMap = {
  pending: Clock,
  paid: CheckCircle,
  shipped: Truck,
  delivered: Home,
  canceled: XCircle
}

const selectedStatus = ref(props.filters.status || '')
const selectedOrder = ref(null)

function applyFilters() {
  router.get(route('orders.index'), { status: selectedStatus.value }, { preserveScroll: true, preserveState: true })
}
</script>

<template>
  <Head title="Pedidos Recebidos" />

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold mb-6">📦 Pedidos Recebidos</h1>

    <!-- Filtros -->
    <div class="mb-6 flex items-center gap-4">
      <select v-model="selectedStatus" @change="applyFilters" class="border px-3 py-2 rounded-md">
        <option value="">Todos os status</option>
        <option value="pending">Pendente</option>
        <option value="paid">Pago</option>
        <option value="shipped">Enviado</option>
        <option value="delivered">Entregue</option>
        <option value="canceled">Cancelado</option>
      </select>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto bg-white rounded-xl shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produto</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Comprador</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Valor</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Data</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="order in orders.data" :key="order.id">
            <td class="px-6 py-4 text-sm">{{ order.product.name }}</td>
            <td class="px-6 py-4 text-sm">
              <div class="font-medium">{{ order.customer_name }}</div>
              <div class="text-xs text-gray-500">{{ order.customer_email }}</div>
            </td>
            <td class="px-6 py-4 text-sm">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium"
                :class="order.status.color"
              >
                <component :is="iconMap[order.status.value]" :class="order.status.iconColor" class="w-4 h-4" />
                {{ order.status.label }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-green-700 font-semibold">
              R$ {{ Number(order.total_amount).toFixed(2) }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
              {{ new Date(order.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 text-sm text-right">
              <button @click="selectedOrder = order" class="text-blue-600 hover:underline">Detalhes</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <Pagination :links="orders.links" class="mt-6" />

    <!-- Modal de Detalhes -->
    <Modal :show="selectedOrder !== null" @close="selectedOrder = null">
      <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Detalhes do Pedido</h2>
        <div v-if="selectedOrder">
          <p><strong>Produto:</strong> {{ selectedOrder.product.name }}</p>
          <p><strong>Nome:</strong> {{ selectedOrder.customer_name }}</p>
          <p><strong>Email:</strong> {{ selectedOrder.customer_email }}</p>
          <p><strong>Endereço:</strong> {{ selectedOrder.customer_address }}</p>
          <p><strong>Referência:</strong> {{ selectedOrder.reference }}</p>
          <p><strong>Status:</strong> {{ selectedOrder.status.label }}</p>
          <p><strong>Total:</strong> R$ {{ selectedOrder.total_amount.toFixed(2) }}</p>
          <p><strong>Data:</strong> {{ new Date(selectedOrder.created_at).toLocaleString() }}</p>
        </div>
      </div>
    </Modal>
  </div>
</template>
