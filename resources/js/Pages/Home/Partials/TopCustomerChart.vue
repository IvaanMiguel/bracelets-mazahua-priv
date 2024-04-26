<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import { TopCustomer } from '@/types/home'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const topCustomer = ref(page.props.topCustomer as TopCustomer)
</script>

<template>
  <div
    class="text-on-background flex flex-col items-center justify-center rounded-md border border-light-outline-variant p-2 text-center dark:border-dark-outline-variant"
  >
    <Icon size="3rem">star</Icon>
    <span class="text-xs">Cliente estrella</span>
    <span class="w-full truncate text-lg font-medium">{{
      topCustomer.name
    }}</span>
    <span class="text-xs">Ingresos generados</span>
    <span class="text-lg font-medium">
      {{ `$${topCustomer.total} MXN` }}
    </span>
  </div>
</template>
