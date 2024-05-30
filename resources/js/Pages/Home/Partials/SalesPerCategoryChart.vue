<script setup lang="ts">
import { useDarkModeChart } from '@/composables/useDarkModeChart'
import { SalesPerCategory } from '@/types/home'
import { ArcElement, Chart, DoughnutController } from 'chart.js'
import { computed, onMounted, ref, toRef, watch } from 'vue'

Chart.register({
  ArcElement,
  DoughnutController,
})

const props = defineProps<{ salesPerCategory: SalesPerCategory[] }>()

let chart: Chart<'doughnut'>
const { textColor, backgroundColors } = useDarkModeChart(toRef(() => chart))

const canvas = ref<HTMLCanvasElement>()
const totalSales = computed(() => {
  return props.salesPerCategory.reduce((acc, sales) => {
    return (acc += +sales.total_sales)
  }, 0)
})

watch(
  () => props.salesPerCategory,
  () => {
    chart.data.labels = props.salesPerCategory.map((sales) => sales.name)
    chart.data.datasets[0].data = props.salesPerCategory.map(
      (sales) => +sales.total_sales
    )

    chart.update()
  }
)

onMounted(() => {
  chart = new Chart(canvas.value!, {
    type: 'doughnut',
    data: {
      labels: props.salesPerCategory.map((sales) => sales.name),
      datasets: [
        {
          data: props.salesPerCategory.map((sales) => +sales.total_sales),
          backgroundColor: backgroundColors.value,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      borderColor: 'transparent',
      spacing: 8,
      plugins: {
        title: {
          display: true,
          text: 'Ventas por categoría',
          font: {
            size: 16,
            weight: 'bold',
            family: 'Roboto',
          },
          color: textColor.value,
        },
        tooltip: {
          displayColors: false,
          position: 'nearest',
          callbacks: {
            label: (ctx) => `Ventas totales: $${ctx.parsed.toFixed(2)} MXN`,
          },
        },
        datalabels: {
          backgroundColor: 'white',
          borderRadius: 6,
          textAlign: 'center',
          font: {
            size: 12,
            family: 'Roboto',
          },
          formatter: function (value, ctx) {
            const percentage = ((value / totalSales.value) * 100).toFixed(1)
            const label = ctx.chart.data.labels
              ? ctx.chart.data.labels[ctx.dataIndex]
              : ''
            return [label, `${percentage}%`]
          },
        },
      },
    },
  })
})
</script>

<template>
  <div
    class="relative h-full w-full rounded-md border border-light-outline-variant p-2 dark:border-dark-outline-variant"
  >
    <canvas ref="canvas"></canvas>
  </div>
</template>
