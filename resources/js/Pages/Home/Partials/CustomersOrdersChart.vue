<script setup lang="ts">
import { useDarkModeChart } from '@/composables/useDarkModeChart'
import { CustomerOrders } from '@/types/home'
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
const customersOrders = ref(page.props.customersOrders as CustomerOrders[])
const data = computed(() => {
  const data: {
    labels: string[]
    totalOrders: number[]
    completedOrders: number[]
  } = { labels: [], totalOrders: [], completedOrders: [] }

  for (const customerOrder of customersOrders.value) {
    data.labels.push(customerOrder.name)
    data.totalOrders.push(+customerOrder.total_orders)
    data.completedOrders.push(+customerOrder.completed_orders)
  }

  return data
})

onMounted(() => {
  chart = new Chart(canvas.value!, {
    type: 'bar',
    data: {
      labels: data.value.labels,
      datasets: [
        {
          label: 'Pedidos hechos',
          data: data.value.totalOrders,
          backgroundColor: backgroundColors.value,
          categoryPercentage: 0.5,
          borderRadius: 6,
        },
        {
          label: 'Pedidos completados',
          data: data.value.completedOrders,
          backgroundColor: backgroundColors.value,
          categoryPercentage: 0.5,
          borderRadius: 6,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
      scales: {
        x: {
          ticks: {
            precision: 0,
            color: textColor.value,
          },
          grid: {
            color: textColor.value,
          },
        },
        y: {
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
          text: 'Clientes con más pedidos hechos',
          font: {
            size: 16,
            family: 'Roboto',
            weight: 'bold',
          },
          color: textColor.value,
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
    class="relative rounded-md border border-light-outline-variant p-2 dark:border-dark-outline-variant"
  >
    <canvas ref="canvas"></canvas>
  </div>
</template>
