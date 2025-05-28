<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Bem-vindo ao seu Dashboard
                        </h3>
                        <p class="text-gray-600">
                            Aqui você pode gerenciar seus produtos, pedidos e muito mais.
                        </p>
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-gray-200">
                        <button
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            @click="$inertia.visit('/products')"
                        >
                            Ver produtos
                        </button>
                        <button
                            class="ml-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                            @click="$inertia.visit('/orders')"
                        >
                            Ver pedidos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
