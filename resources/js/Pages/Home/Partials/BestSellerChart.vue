<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import { BestSeller } from '@/types/home'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const bestSeller = ref(page.props.bestSeller as BestSeller)
</script>

<template>
  <div
    class="flex flex-col items-center justify-center rounded-md border border-light-outline-variant p-2 text-center dark:border-dark-outline-variant text-on-background"
  >
    <Icon size="2.5rem">sell</Icon>
    <span class="text-xs">Producto más vendido</span>
    <span class="text-lg font-medium">{{ bestSeller.name }}</span>
    <span class="text-xs">
      {{ `${bestSeller.units} unidades vendidas en` }}
    </span>
    <span class="text-lg font-medium">
      {{ `$${bestSeller.total_sales} MXN` }}
    </span>
  </div>
</template>
