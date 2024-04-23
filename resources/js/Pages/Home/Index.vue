<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import '@material/web/elevation/elevation'
import CompletedOrdersChart from './Partials/CompletedOrdersChart.vue'

defineOptions({
  layout: MainLayout,
})
</script>

<template>
  <div class="p-4 h-full">
    <div
      class="md-elevation-1 relative mx-auto grid w-full max-w-6xl grid-cols-1 grid-rows-4 rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container sm:grid-cols-2 md:grid-cols-4 h-full"
    >
      <md-elevation />
      <CompletedOrdersChart class="row-span-2" />
    </div>
  </div>
</template>
