<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const totalSales = ref(
  page.props.totalSales as { total_sales: string | number }
)
</script>

<template>
  <div
    class="text-on-background flex flex-col items-center justify-center rounded-md border border-light-outline-variant p-2 text-center dark:border-dark-outline-variant"
  >
    <Icon size="3rem">wallet</Icon>
    <span class="text-xs">Ingresos totales generados</span>
    <span class="text-lg font-medium">
      {{ `$${totalSales.total_sales} MXN` }}
    </span>
  </div>
</template>
