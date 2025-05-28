<template>
  <nav class="flex justify-center mt-4">
    <ul class="inline-flex items-center space-x-1">
      <li v-for="(link, i) in links" :key="i">
        <Component
          :is="link.url ? 'Link' : 'span'"
          :href="link.url"
          class="px-3 py-1 rounded-md text-sm"
          :class="{
            'bg-blue-600 text-white': link.active,
            'text-gray-700 hover:bg-gray-200': !link.active
          }"
          v-html="link.label"
        />
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ links: Array })
</script>
