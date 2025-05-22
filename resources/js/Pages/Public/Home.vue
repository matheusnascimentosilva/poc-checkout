<template>
  <div class="max-w-7xl mx-auto p-4 md:p-6">
    <!-- Cabeçalho com animação -->
    <header class="mb-8">
      <h1 class="text-3xl md:text-4xl font-bold mb-2 text-primary">
        <span class="inline-block animate-fade-in" v-for="(char, index) in 'Produtos Disponíveis'" :key="index"
          :style="{ animationDelay: `${index * 0.05}s` }">{{ char === ' ' ? '\u00A0' : char }}</span>
      </h1>
      <div class="w-20 h-1 bg-primary rounded-full mb-4"></div>
      <div class="flex justify-between items-center">
        <p class="text-muted-foreground">Descubra nossa seleção exclusiva</p>
        <div class="flex items-center gap-2">
          <button @click="viewMode = 'grid'"
            :class="['p-2 rounded-md transition-colors', viewMode === 'grid' ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-primary/5']">
            <GridIcon class="w-5 h-5" />
          </button>
          <button @click="viewMode = 'list'"
            :class="['p-2 rounded-md transition-colors', viewMode === 'list' ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-primary/5']">
            <ListIcon class="w-5 h-5" />
          </button>
        </div>
      </div>
    </header>

    <!-- Filtros e pesquisa -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
      <div class="relative flex-grow">
        <SearchIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-5 h-5" />
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Buscar produtos..."
          class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all"
        />
      </div>
      <select
        v-model="sortOption"
        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all"
      >
        <option value="default">Ordenar por</option>
        <option value="price-asc">Menor preço</option>
        <option value="price-desc">Maior preço</option>
        <option value="name">Nome (A-Z)</option>
      </select>
    </div>

    <!-- Mensagem de carregamento ou sem produtos -->
    <div v-if="loading" class="flex justify-center items-center py-20">
      <LoaderIcon class="w-10 h-10 text-primary animate-spin" />
    </div>

    <div v-else-if="filteredProducts.length === 0" class="text-center py-20 bg-muted/20 rounded-lg">
      <PackageXIcon class="w-16 h-16 mx-auto text-muted-foreground mb-4" />
      <h3 class="text-xl font-medium mb-2">Nenhum produto encontrado</h3>
      <p class="text-muted-foreground">Tente ajustar seus filtros ou buscar por outro termo.</p>
    </div>

    <!-- Lista de produtos -->
    <transition-group
      :name="viewMode === 'grid' ? 'grid-fade' : 'list-fade'"
      tag="div"
      :class="[
        viewMode === 'grid'
          ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'
          : 'flex flex-col gap-4'
      ]"
    >
      <div
        v-for="product in filteredProducts"
        :key="product.id"
        class="group bg-card rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300"
        :class="viewMode === 'list' ? 'flex flex-col md:flex-row' : ''"
      >
        <!-- Imagem do produto -->
        <div
          class="relative overflow-hidden"
          :class="viewMode === 'list' ? 'md:w-1/3' : 'w-full'"
        >
          <img
            :src="product.photo"
            :alt="product.name"
            class="w-full object-cover transition-transform duration-500 group-hover:scale-105"
            :class="viewMode === 'list' ? 'h-64 md:h-full' : 'h-64'"
          />
          <div class="absolute top-3 right-3">
            <button
              @click.prevent="toggleFavorite(product)"
              class="bg-white/80 backdrop-blur-sm p-2 rounded-full shadow-sm hover:bg-white transition-colors"
            >
              <HeartIcon
                class="w-5 h-5 transition-colors"
                :class="favorites.includes(product.id) ? 'text-red-500 fill-red-500' : 'text-muted-foreground'"
              />
            </button>
          </div>
        </div>

        <!-- Conteúdo do produto -->
        <div class="p-5 flex flex-col flex-grow" :class="viewMode === 'list' ? 'md:w-2/3' : ''">
          <div class="flex-grow">
            <div class="flex justify-between items-start mb-2">
              <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">{{ product.name }}</h2>
              <span class="bg-primary/10 text-primary px-2 py-1 rounded-full text-sm font-medium">
                {{ product.category || 'Geral' }}
              </span>
            </div>

            <p class="text-muted-foreground mb-4 line-clamp-3">{{ product.description }}</p>

            <div class="flex items-center gap-1 mb-4">
              <StarIcon v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= (product.rating || 4) ? 'text-yellow-400 fill-yellow-400' : 'text-muted'" />
              <span class="text-sm text-muted-foreground ml-1">{{ product.reviews || 0 }} avaliações</span>
            </div>
          </div>

          <div class="flex items-center justify-between mt-2">
            <div class="flex flex-col">
              <span class="text-xs text-muted-foreground line-through" v-if="product.oldPrice">
                R$ {{ product.oldPrice?.toFixed(2) }}
              </span>
              <span class="text-2xl font-bold text-primary">
                R$ {{ product.price.toFixed(2) }}
              </span>
            </div>

            <inertia-link
              :href="`/buy/${product.uuid}`"
              class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground font-medium px-4 py-2 rounded-lg transition-colors"
            >
              <ShoppingCartIcon class="w-4 h-4" />
              Comprar
            </inertia-link>
          </div>
        </div>
      </div>
    </transition-group>

    <!-- Paginação -->
    <div class="mt-8 flex justify-center" v-if="filteredProducts.length > 0">
      <div class="flex gap-2">
        <button
          v-for="page in 3"
          :key="page"
          :class="[
            'w-10 h-10 rounded-full flex items-center justify-center transition-colors',
            currentPage === page
              ? 'bg-primary text-primary-foreground'
              : 'bg-muted hover:bg-muted-foreground/20 text-foreground'
          ]"
          @click="currentPage = page"
        >
          {{ page }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import {
  Search as SearchIcon,
  Grid as GridIcon,
  List as ListIcon,
  Heart as HeartIcon,
  Star as StarIcon,
  ShoppingCart as ShoppingCartIcon,
  Loader as LoaderIcon,
  PackageX as PackageXIcon
} from 'lucide-vue-next';

// Dados da página
const { props } = usePage();
const loading = ref(true);
const rawProducts = ref([]);

// Estados da UI
const viewMode = ref('grid');
const searchQuery = ref('');
const sortOption = ref('default');
const currentPage = ref(1);
const favorites = ref([]);

// Simular carregamento e adicionar dados extras para demonstração
onMounted(() => {
  setTimeout(() => {
    // Usar os produtos da props ou criar alguns de exemplo se não existirem
    rawProducts.value = props.products?.length
      ? enhanceProducts(props.products)
      : createDemoProducts();
    loading.value = false;
  }, 800);
});

// Adicionar propriedades extras aos produtos para demonstração
function enhanceProducts(products) {
  return products.map(product => ({
    ...product,
    rating: Math.floor(Math.random() * 5) + 1,
    reviews: Math.floor(Math.random() * 100),
    category: ['Eletrônicos', 'Moda', 'Casa', 'Esportes'][Math.floor(Math.random() * 4)],
    oldPrice: Math.random() > 0.5 ? product.price * 1.2 : null
  }));
}

// Criar produtos de demonstração se não houver dados
function createDemoProducts() {
  return Array.from({ length: 6 }, (_, i) => ({
    id: i + 1,
    uuid: `demo-${i + 1}`,
    name: ['Smartphone X', 'Notebook Pro', 'Fones de Ouvido', 'Smartwatch', 'Câmera Digital', 'Tênis Esportivo'][i],
    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    price: 100 + Math.floor(Math.random() * 900),
    photo: `/placeholder.svg?height=400&width=600&text=Produto ${i + 1}`,
    rating: Math.floor(Math.random() * 5) + 1,
    reviews: Math.floor(Math.random() * 100),
    category: ['Eletrônicos', 'Moda', 'Casa', 'Esportes'][Math.floor(Math.random() * 4)],
    oldPrice: Math.random() > 0.5 ? (100 + Math.floor(Math.random() * 900)) * 1.2 : null
  }));
}

// Produtos filtrados e ordenados
const filteredProducts = computed(() => {
  let result = [...rawProducts.value];

  // Aplicar busca
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(product =>
      product.name.toLowerCase().includes(query) ||
      product.description.toLowerCase().includes(query)
    );
  }

  // Aplicar ordenação
  if (sortOption.value === 'price-asc') {
    result.sort((a, b) => a.price - b.price);
  } else if (sortOption.value === 'price-desc') {
    result.sort((a, b) => b.price - a.price);
  } else if (sortOption.value === 'name') {
    result.sort((a, b) => a.name.localeCompare(b.name));
  }

  return result;
});

// Alternar favoritos
function toggleFavorite(product) {
  const index = favorites.value.indexOf(product.id);
  if (index === -1) {
    favorites.value.push(product.id);
  } else {
    favorites.value.splice(index, 1);
  }
}
</script>

<style scoped>
/* Animações */
.animate-fade-in {
  animation: fadeIn 0.5s both;
  display: inline-block;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Transições para os modos de visualização */
.grid-fade-enter-active,
.grid-fade-leave-active,
.list-fade-enter-active,
.list-fade-leave-active {
  transition: all 0.3s ease;
}

.grid-fade-enter-from,
.grid-fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

.list-fade-enter-from,
.list-fade-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

/* Cores personalizadas */
:root {
  --color-primary: #ff6b6b;
  --color-primary-foreground: white;
  --color-muted: #94a3b8;
  --color-muted-foreground: #64748b;
  --color-card: white;
  --color-foreground: #1e293b;
  --color-background: #f8fafc;
}

/* Aplicando as cores personalizadas */
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
.fill-red-500 { fill: #ef4444; }
.fill-yellow-400 { fill: #facc15; }

/* Responsividade para telas menores */
@media (max-width: 640px) {
  .grid-cols-1 > div {
    margin-bottom: 1rem;
  }
}
</style>
