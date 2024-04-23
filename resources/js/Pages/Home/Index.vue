<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import '@material/web/elevation/elevation'
import { Chart, Tooltip } from 'chart.js'
import CompletedOrdersChart from './Partials/CompletedOrdersChart.vue'
import SalesPerCategoryChart from './Partials/SalesPerCategoryChart.vue'

Chart.register({
  Tooltip,
})

defineOptions({
  layout: MainLayout,
})
</script>

<template>
  <div class="h-full p-4">
    <div
      class="md-elevation-1 relative mx-auto grid h-full w-full grid-cols-1 grid-rows-4 rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container sm:grid-cols-2 md:grid-cols-4"
    >
      <md-elevation />
      <CompletedOrdersChart class="row-span-2" />
      <SalesPerCategoryChart class="col-span-2 col-start-3 row-span-4" />
    </div>
  </div>
</template>
