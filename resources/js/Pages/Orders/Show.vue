<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { IdOrder } from '@/types/orders'
import '@material/web/button/filled-button'
import '@material/web/button/filled-tonal-button'
import '@material/web/button/outlined-button'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list-item'
import { ref } from 'vue'
import CompleteOrderModal from './Partials/Show/CompleteOrderModal.vue'
import CustomerInfo from './Partials/Show/CustomerInfo.vue'
import DeleteOrderModal from './Partials/Show/DeleteOrderModal.vue'
import Delivery from './Partials/Show/Delivery.vue'
import Payment from './Partials/Show/Payment.vue'
import Products from './Partials/Show/Products.vue'

defineOptions({ layout: MainLayout })
defineProps<{ order: IdOrder }>()

const completeOrderModal = ref<InstanceType<typeof CompleteOrderModal>>()
const deleteOrderModal = ref<InstanceType<typeof DeleteOrderModal>>()
</script>

<template>
  <div class="p-4">
    <div
      class="md-elevation-1 relative mx-auto w-full max-w-6xl rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="mb-4 flex items-center justify-between gap-4">
        <Link
          as="button"
          :href="route('orders')"
          tabindex="-1"
        >
          <md-icon-button>
            <Icon>arrow_back</Icon>
          </md-icon-button>
        </Link>
        <md-filled-button
          v-if="!order.completed"
          @click="completeOrderModal?.modal?.show"
          :disabled="
            completeOrderModal?.processing || deleteOrderModal?.processing
          "
        >
          Completar pedido
          <Icon slot="icon">fact_check</Icon>
        </md-filled-button>
      </div>

      <CustomerInfo
        class="rounded-lg border border-light-outline-variant p-4 dark:border-dark-outline-variant"
        :customer-info="order.customer"
      />

      <Products
        class="mt-6 flex flex-col rounded-lg border border-light-outline-variant p-4 dark:border-dark-outline-variant"
        :products="order.products"
        :advance="+order.advance"
        :total="+order.total"
        :productsTotal="+order.products_total"
        :completed="!!order.completed"
        :processing="
          completeOrderModal?.processing || deleteOrderModal?.processing
        "
      />

      <Delivery
        class="mt-6 rounded-lg border border-light-outline-variant p-4 dark:border-dark-outline-variant"
        :delivery="{ id: +order.delivery, ...order.delivery }"
        :completed="!!order.completed"
        :processing="
          completeOrderModal?.processing || deleteOrderModal?.processing
        "
      />

      <Payment
        class="mt-6 rounded-lg border border-light-outline-variant p-4 dark:border-dark-outline-variant"
        :payment="{ ...order.payment_type, details: order.details }"
      />

      <div class="mt-6 text-end">
        <md-outlined-button
          v-if="!order.completed"
          :disabled="
            completeOrderModal?.processing || deleteOrderModal?.processing
          "
          @click="deleteOrderModal?.modal?.show"
        >
          Eliminar pedido
          <Icon slot="icon">delete_forever</Icon>
        </md-outlined-button>
      </div>
    </div>
  </div>

  <CompleteOrderModal ref="completeOrderModal" />

  <DeleteOrderModal ref="deleteOrderModal" />
</template>

<style scoped>
.dark md-outlined-button {
  --md-sys-color-primary: theme(colors.dark.error);
}
md-outlined-button {
  --md-sys-color-primary: theme(colors.light.error);
}
</style>
