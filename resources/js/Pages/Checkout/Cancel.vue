<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Pagamento Cancelado" />

  <div class="min-h-screen flex flex-col items-center justify-center bg-red-50 px-4 text-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
      <h1 class="text-3xl font-bold text-red-600 mb-4">❌ Pagamento cancelado</h1>
      <p class="text-gray-700 mb-6">Parece que algo deu errado ou você cancelou o pagamento.</p>

      <Link
        href="/"
        class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded"
      >
        Voltar para a Página Inicial
      </Link>
    </div>
  </div>
</template>
