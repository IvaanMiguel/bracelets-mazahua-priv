<script setup lang="ts">
import { useDarkModeChart } from '@/composables/useDarkModeChart.js'
import { CompletedOrder } from '@/types/home'
import { usePage } from '@inertiajs/vue3'
import {
  BarController,
  BarElement,
  CategoryScale,
  Chart,
  LinearScale,
} from 'chart.js'
import { computed, onMounted, ref, toRef } from 'vue'

Chart.register({
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
})

const page = usePage()

let chart: Chart<'bar'>
const { textColor, backgroundColors } = useDarkModeChart(toRef(() => chart))
const canvas = ref<HTMLCanvasElement>()
const completedOrders = ref(page.props.completedOrders as CompletedOrder[])
const ordersPercentage = computed(() => {
  const total = completedOrders.value[0].total + completedOrders.value[1].total

  return Math.round((completedOrders.value[0].total / total) * 100)
})

onMounted(() => {
  chart = new Chart(canvas.value!, {
    type: 'bar',
    data: {
      labels: completedOrders.value.map((order) => order.name),
      datasets: [
        {
          data: completedOrders.value.map((order) => order.total),
          backgroundColor: backgroundColors.value,
          borderRadius: 6,
          categoryPercentage: 0.5
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
