<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Pagamento Confirmado" />

  <div class="min-h-screen flex flex-col items-center justify-center bg-green-50 px-4 text-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
      <h1 class="text-3xl font-bold text-green-600 mb-4">✅ Pagamento confirmado!</h1>
      <p class="text-gray-700 mb-6">Obrigado pela sua compra. Seu pagamento foi processado com sucesso.</p>

      <Link
        href="/"
        class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded"
      >
        Voltar para a Página Inicial
      </Link>
    </div>
  </div>
</template>
