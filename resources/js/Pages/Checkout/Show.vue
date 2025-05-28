<template>
    <div class="max-w-7xl mx-auto p-4 md:p-6">
        <!-- Cabeçalho -->
        <header class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 text-primary">Finalizar Compra</h1>
            <div class="w-20 h-1 bg-primary rounded-full mb-4"></div>
        </header>

        <!-- Detalhes do produto -->
        <div class="bg-card rounded-xl shadow-sm p-6 flex flex-col md:flex-row gap-6">
            <div class="md:w-1/3">
                <img
                    :src="product.photo"
                    :alt="product.name"
                    class="w-full h-64 object-cover rounded-lg"
                />
            </div>
            <div class="md:w-2/3">
                <h2 class="text-2xl font-semibold text-foreground mb-2">{{ product.name }}</h2>
                <p class="text-muted-foreground mb-4">{{ product.description }}</p>
                <div class="flex items-center gap-1 mb-4">
                    <StarIcon
                        v-for="i in 5"
                        :key="i"
                        class="w-4 h-4"
                        :class="i <= (product.rating || 4) ? 'text-yellow-400 fill-yellow-400' : 'text-muted'"
                    />
                    <span class="text-sm text-muted-foreground ml-1">{{ product.reviews || 0 }} avaliações</span>
                </div>
                <div class="flex flex-col mb-4">
                    <span v-if="product.oldPrice" class="text-xs text-muted-foreground line-through">
                        R$ {{ product.oldPrice.toFixed(2) }}
                    </span>
                    <span class="text-2xl font-bold text-primary">
                        R$ {{ parseFloat(product.price).toFixed(2) }}
                    </span>
                </div>

                <!-- Formulário de checkout manual -->
                <form @submit.prevent="submitCheckout" class="mb-4">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-foreground mb-1">
                            Nome
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none"
                            required
                        />
                        <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-foreground mb-1">
                            E-mail
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none"
                            required
                        />
                        <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-foreground mb-1">
                            Endereço
                        </label>
                        <input
                            v-model="form.address"
                            type="text"
                            id="address"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none"
                            required
                        />
                        <span v-if="form.errors.address" class="text-red-500 text-sm">{{ form.errors.address }}</span>
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-foreground mb-1">
                            Quantidade
                        </label>
                        <input
                            v-model.number="form.quantity"
                            type="number"
                            id="quantity"
                            min="1"
                            class="w-20 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground font-medium px-4 py-2 rounded-lg transition-colors"
                        :disabled="form.processing"
                    >
                        <ShoppingCartIcon class="w-4 h-4" />
                        Confirmar Pedido
                    </button>
                </form>

                <!-- Botão para pagamento com Stripe -->
                <button
                    @click="startStripeCheckout"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors"
                >
                    Pagar com Stripe
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { ShoppingCartIcon, StarIcon } from 'lucide-vue-next';

defineProps({
    product: Object,
});

const form = useForm({
    name: '',
    email: '',
    address: '',
    quantity: 1,
    product_id: null,
});

function submitCheckout() {
    form.product_id = product.id;
    form.post(route('checkout.store', product.id), {
        onSuccess: () => {
            router.visit(route('checkout.success', product.id));
        },
        onError: (errors) => {
            console.error('Erro no checkout:', errors);
        },
    });
}

function startStripeCheckout() {
    router.post(route('checkout.create', product.id), {}, {
        onSuccess: (page) => {
            // A resposta do servidor já redireciona para a URL do Stripe
        },
        onError: (errors) => {
            console.error('Erro ao iniciar o checkout com Stripe:', errors);
        },
    });
}
</script>

<style scoped>
:root {
    --color-primary: #ff6b6b;
    --color-primary-foreground: white;
    --color-muted: #94a3b8;
    --color-muted-foreground: #64748b;
    --color-card: white;
    --color-foreground: #1e293b;
    --color-background: #f8fafc;
}

.text-primary { color: var(--color-primary); }
.text-primary-foreground { color: var(--color-primary-foreground); }
.text-muted { color: var(--color-muted); }
.text-muted-foreground { color: var(--color-muted-foreground); }
.text-foreground { color: var(--color-foreground); }
.bg-primary { background-color: var(--color-primary); }
.bg-primary\/10 { background-color: rgba(255, 107, 107, 0.1); }
.bg-primary\/90 { background-color: rgba(255, 107, 107, 0.9); }
.bg-muted { background-color: var(--color-muted); }
.bg-muted\/20 { background-color: rgba(148, 163, 184, 0.2); }
.bg-card { background-color: var(--color-card); }
.fill-yellow-400 { fill: #facc15; }
</style>
