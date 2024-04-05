<script setup lang="ts">
import DisplayField from '@/Components/DisplayField.vue'
import Icon from '@/Components/Icon.vue'
import { useAddressDetails } from '@/composables/useAddressDetails'
import { IdAddress } from '@/types/customers'
import {
  Catalog,
  Customer,
  Delivery,
  Payment,
  SelectedProduct,
} from '@/types/orders'
import { usePage } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { es } from 'date-fns/locale'
import { computed, ref, toRef } from 'vue'

const props = defineProps<{
  customer?: Customer | null
  products?: SelectedProduct[] | null
  advance?: number | null
  total?: number | null
  delivery?: Delivery
  deliveryAddress?: IdAddress | null
  payment?: Payment | null
}>()

const page = usePage()
const addressDetails = useAddressDetails(toRef(() => props.deliveryAddress))

const deliveryTypes = ref(page.props.deliveryTypes as Catalog[])
const deliveryApps = ref(page.props.deliveryApps as Catalog[])
const paymentTypes = ref(page.props.paymentTypes as Catalog[])
const deliveryType = computed(() => {
  const _deliveryType = deliveryTypes.value.find((deliveryType) => {
    return deliveryType.id === props.delivery?.delivery_type_id
  })

  return _deliveryType?.name
})
const deliveryApp = computed(() => {
  const _deliveryApp = deliveryApps.value.find((deliveryApp) => {
    return deliveryApp.id === props.delivery?.delivery_app_id
  })

  return _deliveryApp?.name
})
const paymentType = computed(() => {
  const _paymentType = paymentTypes.value.find((paymentType) => {
    return paymentType.id === props.payment?.payment_type_id
  })

  return _paymentType?.name
})
const deliveryDate = computed(() => {
  if (!props.delivery) return

  return format(`${props.delivery.date}`, "iiii, dd 'de' MMMM 'de' y", {
    locale: es,
  })
})
const deliveryTime = computed(() => {
  const time = props.delivery?.time.split(':')

  if (!time) return props.delivery?.time

  return format(new Date(0, 0, 0, +time[0], +time[1]), "'a las' HH:mm", {
    locale: es,
  })
})
</script>

<template>
  <div>
    <h2 class="text-on-background mb-3 text-lg font-medium">
      Resumen del pedido
    </h2>
    <div>
      <div class="py-4">
        <DisplayField
          label="Nombre del cliente"
          :text="`${customer?.name} ${customer?.last_name}`"
        >
          <template #icon>
            <Icon>person</Icon>
          </template>
        </DisplayField>
      </div>

      <md-divider inset />

      <div class="py-4">
        <md-list
          class="max-h-[295px] overflow-auto rounded-md border border-light-outline-variant bg-light-surface-container-lowest py-0 dark:border-dark-outline-variant dark:bg-dark-surface-container"
        >
          <div class="table-cols grid">
            <div
              class="sticky top-0 z-[2] col-span-full grid grid-cols-subgrid items-center gap-4 border-b border-light-outline-variant bg-light-surface-container-highest px-4 py-3 font-medium dark:border-dark-outline-variant dark:bg-dark-surface-container-highest"
            >
              <span class="truncate">Producto</span>
              <span class="truncate">Cantidad</span>
              <span class="truncate">Subtotal</span>
            </div>
            <template v-for="(product, i) in products">
              <md-divider
                v-if="i !== 0"
                class="col-span-full"
                inset
              />
              <div class="col-span-full grid grid-cols-subgrid gap-4 px-4 py-3">
                <span class="truncate">{{ product.name }}</span>
                <span class="truncate">{{ product.amount }}</span>
                <span class="truncate">{{
                  `$${product.subtotal.toFixed(2)} MXN`
                }}</span>
              </div>
            </template>
          </div>
        </md-list>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
          <DisplayField
            label="Anticipo"
            :text="`$${advance?.toFixed(2)} MXN`"
          >
            <template #icon>
              <Icon>toll</Icon>
            </template>
          </DisplayField>
          <DisplayField
            label="Total"
            :text="`$${total?.toFixed(2)} MXN`"
          >
            <template #icon>
              <Icon>paid</Icon>
            </template>
          </DisplayField>
        </div>
      </div>

      <md-divider inset />

      <div class="grid gap-4 py-4 sm:grid-cols-2">
        <DisplayField
          label="Tipo de entrega"
          :text="`${deliveryType}`"
        >
          <template #icon>
            <Icon>quick_reorder</Icon>
          </template>
        </DisplayField>
        <DisplayField
          v-if="delivery?.delivery_type_id === 3"
          label="Aplicación de entrega"
          :text="`${deliveryApp}`"
        >
          <template #icon>
            <Icon>local_shipping</Icon>
          </template>
        </DisplayField>
        <DisplayField
          label="Fecha de entrega"
          :text="`${deliveryDate}`"
        >
          <template #icon>
            <Icon>event</Icon>
          </template>
        </DisplayField>
        <DisplayField
          label="Hora de entrega"
          :text="`${deliveryTime}`"
        >
          <template #icon>
            <Icon>alarm</Icon>
          </template>
        </DisplayField>
        <div
          v-if="delivery?.delivery_type_id !== 1"
          class="col-span-full"
        >
          <div class="flex gap-4">
            <Icon>explore</Icon>
            <div class="text-on-background flex flex-col gap-2">
              <span class="text-xs font-medium">Dirección de entrega</span>
              <div>
                <span class="block">{{ addressDetails.headline }}</span>
                <span
                  v-if="addressDetails.supportingText"
                  class="block text-sm"
                >
                  {{ addressDetails.supportingText }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <md-divider inset />

      <div class="grid gap-4 py-4 sm:grid-cols-2">
        <DisplayField
          label="Tipo de pago"
          :text="`${paymentType}`"
        >
          <template #icon>
            <Icon>payments</Icon>
          </template>
        </DisplayField>
        <DisplayField
          v-if="payment?.payment_type_id === 3"
          label="Titular de la tarjeta"
          :text="`${payment?.name}`"
        >
          <template #icon>
            <Icon>id_card</Icon>
          </template>
        </DisplayField>
        <DisplayField
          v-if="payment?.payment_type_id === 3"
          label="Número de tarjeta"
          :text="`${payment?.cardNumber}`"
        >
          <template #icon>
            <Icon>credit_card</Icon>
          </template>
        </DisplayField>
        <DisplayField
          v-if="payment?.payment_type_id === 2"
          label="CLABE de cuenta"
          :text="`${payment?.clabe}`"
        >
          <template #icon>
            <Icon>account_balance_wallet</Icon>
          </template>
        </DisplayField>
      </div>
    </div>
  </div>
</template>

<style scoped>
.table-cols {
  grid-template-columns: 3fr 1fr 2fr;
}
</style>
