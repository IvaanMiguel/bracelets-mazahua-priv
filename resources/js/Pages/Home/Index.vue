<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import '@material/web/elevation/elevation'
import { Chart, SubTitle, Title, Tooltip } from 'chart.js'
import ChartDataLabels from 'chartjs-plugin-datalabels'
import BestSellerChart from './Partials/BestSellerChart.vue'
import CompletedOrdersChart from './Partials/CompletedOrdersChart.vue'
import SalesPerCategoryChart from './Partials/SalesPerCategoryChart.vue'
import TopCustomerChart from './Partials/TopCustomerChart.vue'

Chart.register({
  ChartDataLabels,
  SubTitle,
  Title,
  Tooltip,
})

defineOptions({
  layout: MainLayout,
})
</script>

<template>
  <div class="p-4">
    <div
      class="md-elevation-1 relative mx-auto grid h-full w-full grid-cols-1 grid-rows-[repeat(7,160px)] gap-1 rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container md:grid-cols-2 lg:grid-cols-4"
    >
      <md-elevation />
      <CompletedOrdersChart class="row-span-2" />
      <TopCustomerChart />
      <SalesPerCategoryChart class="row-span-3 md:col-span-2 md:row-span-4" />
      <BestSellerChart />
    </div>
  </div>
</template>
