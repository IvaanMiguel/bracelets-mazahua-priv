<script setup lang="ts">
import { useDarkModeChart } from '@/composables/useDarkModeChart.js'
import { CompletedOrder } from '@/types/home'
import {
  BarController,
  BarElement,
  CategoryScale,
  Chart,
  LinearScale,
} from 'chart.js'
import { computed, onMounted, ref, toRef, watch } from 'vue'

Chart.register({
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
})

const props = defineProps<{ completedOrders: CompletedOrder[] }>()

let chart: Chart<'bar'>
const { textColor, backgroundColors } = useDarkModeChart(toRef(() => chart))
const canvas = ref<HTMLCanvasElement>()
const ordersPercentage = computed(() => {
  const total = props.completedOrders[0].total + props.completedOrders[1].total

  return Math.round((props.completedOrders[0].total / total) * 100)
})

watch(
  () => props.completedOrders,
  () => {
    chart.data.datasets[0].data = props.completedOrders.map(
      (order) => order.total
    )
    chart.update()
  }
)

onMounted(() => {
  chart = new Chart(canvas.value!, {
    type: 'bar',
    data: {
      labels: props.completedOrders.map((order) => order.name),
      datasets: [
        {
          data: props.completedOrders.map((order) => order.total),
          backgroundColor: backgroundColors.value,
          borderRadius: 6,
          categoryPercentage: 0.5,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          ticks: {
            color: textColor.value,
          },
          grid: {
            color: textColor.value,
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
        title: {
          display: true,
          text: 'Pedidos',
          font: {
            size: 16,
            family: 'Roboto',
            weight: 'bold',
          },
          color: textColor.value,
          padding: { top: 8, bottom: 4 },
        },
        subtitle: {
          display: true,
          text: `El ${ordersPercentage.value}% de los pedidos han sido completados.`,
          font: {
            size: 12,
            family: 'Roboto',
          },
          color: textColor.value,
          padding: { top: 4, bottom: 8 },
        },
        datalabels: {
          display: false,
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
