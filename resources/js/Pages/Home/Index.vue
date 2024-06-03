<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import {
  BestSeller,
  CompletedOrder,
  CustomerOrders,
  PopularPaymentType,
  SalesPerCategory,
  SalesPerMonth,
  TopCustomer,
} from '@/types/home'
import { usePage } from '@inertiajs/vue3'
import '@material/web/elevation/elevation'
import axios from 'axios'
import { Chart, SubTitle, Title, Tooltip } from 'chart.js'
import ChartDataLabels from 'chartjs-plugin-datalabels'
import { onMounted, ref } from 'vue'
import BestSellerChart from './Partials/BestSellerChart.vue'
import CompletedOrdersChart from './Partials/CompletedOrdersChart.vue'
import CustomersOrdersChart from './Partials/CustomersOrdersChart.vue'
import PopularPaymentTypeChart from './Partials/PopularPaymentTypeChart.vue'
import SalesPerCategoryChart from './Partials/SalesPerCategoryChart.vue'
import SalesPerMonthChart from './Partials/SalesPerMonthChart.vue'
import TopCustomerChart from './Partials/TopCustomerChart.vue'
import TotalSalesChart from './Partials/TotalSalesChart.vue'

Chart.register({
  ChartDataLabels,
  SubTitle,
  Title,
  Tooltip,
})

defineOptions({
  layout: MainLayout,
})

const page = usePage()
const completedOrders = ref(page.props.completedOrders as CompletedOrder[])
const topCustomer = ref(page.props.topCustomer as TopCustomer)
const salesPerCategory = ref(page.props.salesPerCategory as SalesPerCategory[])
const bestSeller = ref(page.props.bestSeller as BestSeller)
const customersOrders = ref(page.props.customersOrders as CustomerOrders[])
const popularPaymentType = ref(
  page.props.popularPaymentType as PopularPaymentType
)
const totalSales = ref(
  page.props.totalSales as { total_sales: string | number }
)
const salesPerMonth = ref(page.props.salesPerMonth as SalesPerMonth[])

onMounted(() => {
  setInterval(() => {
    axios.get(route('dashboard')).then((res) => {
      completedOrders.value = res.data.completedOrders
      topCustomer.value = res.data.topCustomer
      salesPerCategory.value = res.data.salesPerCategory
      bestSeller.value = res.data.bestSeller
      customersOrders.value = res.data.customersOrders
      popularPaymentType.value = res.data.popularPaymentType
      totalSales.value = res.data.totalSales
      salesPerMonth.value = res.data.salesPerMonth
    })
  }, 750)
})
</script>

<template>
  <div class="p-4">
    <div
      class="md-elevation-1 relative mx-auto grid h-full w-full grid-cols-1 grid-rows-[repeat(14,160px)] gap-1 rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container md:grid-cols-2 md:grid-rows-[repeat(12,160px)] lg:grid-cols-4 lg:grid-rows-[repeat(7,160px)]"
    >
      <md-elevation />
      <CompletedOrdersChart
        :completedOrders
        class="row-span-2"
      />
      <TopCustomerChart :topCustomer />
      <SalesPerCategoryChart
        :salesPerCategory
        class="row-span-3 md:col-span-2 md:row-span-4"
      />
      <BestSellerChart
        :bestSeller
        class="md:col-start-2 md:row-start-2"
      />
      <CustomersOrdersChart
        :customersOrders
        class="row-span-3 md:col-span-2"
      />
      <PopularPaymentTypeChart :popularPaymentType />
      <TotalSalesChart :totalSales />
      <SalesPerMonthChart
        :salesPerMonth
        class="col-span-full row-span-2"
      />
    </div>
  </div>
</template>
