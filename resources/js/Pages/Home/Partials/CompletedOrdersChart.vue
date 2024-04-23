<script setup lang="ts">
import { useDarkModeChart } from '@/composables/useDarkModeChart.js'
import { completedOrder } from '@/types/home'
import { usePage } from '@inertiajs/vue3'
import {
  BarController,
  BarElement,
  CategoryScale,
  Chart,
  LinearScale,
  Tooltip,
} from 'chart.js'
import { computed, onMounted, ref, toRef } from 'vue'

Chart.register({
  Tooltip,
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
})

const page = usePage()

let chart: Chart
const { textColor, backgroundColors } = useDarkModeChart(toRef(() => chart))
const canvas = ref<HTMLCanvasElement>()
const completedOrders = ref(page.props.completedOrders as completedOrder[])
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
          barThickness: 50,
          backgroundColor: backgroundColors.value,
          borderRadius: 6,
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
    },
  })
})
</script>

<template>
  <div
    class="flex max-h-full flex-col rounded-md border border-light-outline-variant p-1 dark:border-dark-outline-variant"
  >
    <span class="text-on-background text-center font-medium">Pedidos</span>
    <span class="text-on-background text-center text-xs">
      {{ `El ${ordersPercentage}% de los pedidos han sido completados.` }}
    </span>
    <div class="relative h-full w-full">
      <canvas ref="canvas"></canvas>
    </div>
  </div>
</template>
