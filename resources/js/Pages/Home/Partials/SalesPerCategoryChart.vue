<script setup lang="ts">
import { useDarkModeChart } from '@/composables/useDarkModeChart'
import { SalesPerCategory } from '@/types/home'
import { usePage } from '@inertiajs/vue3'
import { ArcElement, Chart, DoughnutController } from 'chart.js'
import { onMounted, ref, toRef } from 'vue'

Chart.register({
  ArcElement,
  DoughnutController,
})

const page = usePage()

let chart: Chart<'doughnut'>
const { textColor, backgroundColors } = useDarkModeChart(toRef(() => chart))

const canvas = ref<HTMLCanvasElement>()
const salesPerCategory = ref(page.props.salesPerCategory as SalesPerCategory[])

onMounted(() => {
  chart = new Chart(canvas.value!, {
    type: 'doughnut',
    data: {
      labels: salesPerCategory.value.map((sales) => sales.name),
      datasets: [
        {
          data: salesPerCategory.value.map((sales) => +sales.total_sales),
          backgroundColor: backgroundColors.value,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      borderColor: 'transparent',
      spacing: 8,
    },
  })
})
</script>

<template>
  <div
    class="flex flex-col rounded-md border border-light-outline-variant p-1 dark:border-dark-outline-variant"
  >
    <span class="text-on-background text-center font-medium">
      Ventas por categoría
    </span>
    <div class="relative h-full w-full">
      <canvas ref="canvas"></canvas>
    </div>
  </div>
</template>
