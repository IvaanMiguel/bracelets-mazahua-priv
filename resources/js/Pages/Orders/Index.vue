<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import SearchBar from '@/Components/SearchBar.vue'
import Table from '@/Components/Table.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Pagination } from '@/types'
import { OrderListItem } from '@/types/orders'
import '@material/web/button/filled-button'
import '@material/web/elevation/elevation'
import '@material/web/list/list'
import '@material/web/list/list-item'
import { format } from 'date-fns'
import { es } from 'date-fns/locale'

defineOptions({ layout: MainLayout })
defineProps<{ orders: Pagination<OrderListItem> }>()

const formatDate = (date: string) => {
  return format(date, 'dd/MMM/y', { locale: es })
}
</script>

<template>
  <div class="h-full p-4">
    <div
      class="md-elevation-1 relative mx-auto flex h-full w-full max-w-6xl flex-col rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex items-center gap-4 p-4 pb-2">
        <h1 class="text-on-background text-2xl font-medium">
          {{ `${orders.total} pedido${orders.total !== 1 ? 's' : ''}` }}
        </h1>

        <SearchBar
          class="ms-auto w-full max-w-96"
          placeholder="Buscar..."
          lder="Buscar por nombre..."
          :base-url="route('orders')"
        />

        <Link
          as="button"
          :href="route('orders.create')"
          tabindex="-1"
        >
          <md-filled-button>
            <Icon slot="icon">add</Icon>
            Nuevo pedido
          </md-filled-button>
        </Link>
      </div>

      <Table
        class="flex h-full flex-col overflow-hidden"
        :headers="[
          'Cliente',
          'Total',
          'Tipo de pago',
          'Tipo de entrega',
          'Fecha de entrega',
          'Estado',
        ]"
        :pagination="orders"
        :url="route('orders')"
      >
        <template v-for="(order, i) in orders.data">
          <Link
            :href="route('orders')"
            tabindex="-1"
          >
            <md-list-item type="button">
              <div class="grid grid-cols-6 gap-4">
                <span class="truncate">
                  {{ `${order.customer.name} ${order.customer.last_name}` }}
                </span>
                <span class="truncate">{{ `$${order.total}` }}</span>
                <span class="truncate">{{ order.payment_type.name }}</span>
                <span class="truncate">
                  {{ order.delivery.delivery_type.name }}
                </span>
                <span class="truncate">{{
                  formatDate(order.delivery.date)
                }}</span>
                <span class="truncate">
                  {{ order.completed ? 'Completado' : 'Pendiente' }}
                </span>
              </div>
            </md-list-item>
            <md-divider
              v-if="i !== orders.data.length - 1"
              inset
            />
          </Link>
        </template>
      </Table>
    </div>
  </div>
</template>
