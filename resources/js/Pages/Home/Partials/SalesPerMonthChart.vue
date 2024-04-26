<script setup lang="ts">
import { SalesPerMonth } from '@/types/home'
import { usePage } from '@inertiajs/vue3'
import { useDark } from '@vueuse/core'
import {
  CategoryScale,
  Chart,
  Filler,
  LineController,
  LineElement,
  LinearScale,
  PointElement,
} from 'chart.js'
import { computed, onMounted, ref, watch } from 'vue'
import colorPalette from '../../../../../tailwindcolorpalette.js'

Chart.register({
  CategoryScale,
  Filler,
  LineController,
  LineElement,
  LinearScale,
  PointElement,
})

const page = usePage()
const isDark = useDark()

let chart: Chart<'line', String[]>
// const { textColor, backgroundColors } = useDarkModeChart(toRef(() => chart))

const canvas = ref<HTMLCanvasElement>()
const textColor = computed(() =>
  isDark.value
    ? colorPalette['neutral-variant'][80]
    : colorPalette['neutral-variant'][30]
)
const backgroundColor = computed(() =>
  isDark.value
    ? [`${colorPalette.primary[80]}8C`, `${colorPalette.secondary[30]}8C`]
    : [`${colorPalette.primary[40]}8C`, `${colorPalette.secondary[40]}8C`]
)
const salesPerMonth = ref(page.props.salesPerMonth as SalesPerMonth[])
const data = computed(() => {
  const data: {
    labels: string[]
    sales: string[]
    productsAmount: string[]
  } = { labels: [], sales: [], productsAmount: [] }

  for (const monthSale of salesPerMonth.value) {
    const month =
      monthSale.month.charAt(0).toUpperCase() + monthSale.month.slice(1, 3)

    data.labels.push(`${month} ${monthSale.year}`)
    data.sales.push(`${monthSale.sales}`)
    data.productsAmount.push(`${monthSale.products_amount}`)
  }

  return data
})

watch(isDark, () => {
  chart.options.scales!.salesY!.ticks!.color = textColor.value
  chart.options.scales!.salesY!.grid!.color = textColor.value
  chart.options.scales!.productsY!.ticks!.color = textColor.value
  chart.options.scales!.productsY!.grid!.color = textColor.value
  chart.options.scales!.x!.ticks!.color = textColor.value
  chart.options.scales!.x!.grid!.color = textColor.value
  chart.options.plugins!.title!.color = textColor.value
  chart.data.datasets[0].backgroundColor = backgroundColor.value[0]
  chart.data.datasets[1].backgroundColor = backgroundColor.value[1]
  chart.data.datasets[0].borderColor = backgroundColor.value[0]
  chart.data.datasets[1].borderColor = backgroundColor.value[1]

  chart.update()
})

onMounted(() => {
  chart = new Chart(canvas.value!, {
    type: 'line',
    data: {
      labels: data.value.labels,
      datasets: [
        {
          label: 'Venta mensual',
          data: data.value.sales,
          fill: true,
          backgroundColor: backgroundColor.value[0],
          borderColor: backgroundColor.value[0],
          tension: 0.2,
          yAxisID: 'salesY',
        },
        {
          label: 'Cantidad de productos vendidos',
          data: data.value.productsAmount,
          backgroundColor: backgroundColor.value[1],
          borderColor: backgroundColor.value[1],
          fill: true,
          tension: 0.2,
          yAxisID: 'productsY',
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        salesY: {
          type: 'linear',
          display: true,
          position: 'left',
          ticks: {
            color: textColor.value,
            callback: (ctx) => `$${(+ctx).toFixed(2)}`,
          },
          grid: {
            color: textColor.value,
          },
        },
        productsY: {
          type: 'linear',
          display: true,
          position: 'right',
          grid: {
            drawOnChartArea: false,
          },
        },
        x: {
          ticks: {
            color: textColor.value,
          },
          grid: {
            color: textColor.value,
          },
        },
      },
      plugins: {
        datalabels: {
          display: false,
        },
        tooltip: {
          displayColors: false,
          mode: 'index',
          intersect: false,
          callbacks: {
            label: (ctx) => {
              if (ctx.datasetIndex === 0) {
                return `Venta mensual: $${ctx.parsed.y.toFixed(2)} MXN`
              }
            },
          },
        },
        title: {
          display: true,
          text: 'Ventas de Productos Mensuales',
          font: {
            size: 16,
            family: 'Roboto',
            weight: 'bold',
          },
          color: textColor.value,
        },
      },
    },
  })
})
</script>

<template>
  <div
    class="relative rounded-md border border-light-outline-variant p-2 dark:border-dark-outline-variant"
  >
    <canvas ref="canvas"></canvas>
  </div>
</template>
