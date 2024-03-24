<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Paginator from '@/Components/Paginator.vue'
import SearchBar from '@/Components/SearchBar.vue'
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
const props = defineProps<{ orders: Pagination<OrderListItem> }>()

const formatDate = (date: string) => {
  return format(date, 'dd/MMM/y', { locale: es })
}

console.log(props.orders.data)
</script>

<template>
  <div class="grid h-full grid-cols-1 overflow-y-auto p-4">
    <div
      class="md-elevation-1 relative mx-auto h-fit w-full max-w-6xl rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex items-center gap-4 p-4 pb-2">
        <h1 class="text-on-background text-2xl font-medium">
          {{ `${orders.total} pedido${orders.total !== 1 ? 's' : ''}` }}
        </h1>

        <SearchBar
          class="ms-auto"
          placeholder="Buscar..."
          lder="Buscar por nombre..."
          :base-url="route('orders')"
        />

        <md-filled-button @click="">
          <Icon slot="icon">add</Icon>
          Agregar
        </md-filled-button>
      </div>

      <md-list
        v-if="orders.data.length"
        class="bg-light-surface-container-lowest py-0 dark:bg-dark-surface-container"
      >
        <div
          class="text-on-background grid h-14 grid-cols-6 items-center gap-4 px-4 font-medium"
        >
          <span class="truncate">Cliente</span>
          <span class="truncate">Total</span>
          <span class="truncate">Tipo de pago</span>
          <span class="truncate">Tipo de entrega</span>
          <span class="truncate">Fecha de entrega</span>
          <span class="truncate">Estado</span>
        </div>
        <md-divider inset />

        <template v-for="order in orders.data">
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
                  {{ order.paid ? 'Pagado' : 'Sin pagar' }}
                </span>
              </div>
            </md-list-item>
          </Link>
          <md-divider inset />
        </template>
      </md-list>

      <template v-else>
        <md-divider inset />
        <div class="p-4 text-center">
          <Icon
            class="text-on-background"
            size="3rem"
          >
            search_off
          </Icon>
          <span
            class="block text-xl font-medium text-light-on-background dark:text-dark-on-background"
          >
            {{
              `No se han encontrado coincidencias para "${$page.props.filters.search}".`
            }}
          </span>
        </div>
        <md-divider inset />
      </template>

      <Paginator
        class="justify-end px-4 py-2"
        :pagination="orders"
        :selected-results="$page.props.filters.results"
        :base-url="route('orders')"
      />
    </div>
  </div>
</template>
