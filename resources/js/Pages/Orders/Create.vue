<script setup lang="ts">
import DeliveryForm from '@/Components/Forms/Delivery/DeliveryForm.vue'
import Icon from '@/Components/Icon.vue'
import StepsForm from '@/Components/StepsForm.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { IdAddress } from '@/types/customers'
import '@material/web/button/filled-tonal-button'
import '@material/web/checkbox/checkbox'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/progress/linear-progress'
import '@material/web/radio/radio'
import '@material/web/textfield/outlined-text-field'
import axios from 'axios'
import { computed, reactive, ref, watch } from 'vue'
import SelectCustomer from './Partials/CreateForm/Customer/SelectCustomer.vue'
import SelectedProducts from './Partials/CreateForm/Products/SelectedProducts.vue'

defineOptions({ layout: MainLayout })
defineProps<{
  deliveryTypes: { id: number; name: string }[]
  deliveryApps: { id: number; name: string }[]
}>()

const stepsForm = ref<InstanceType<typeof StepsForm>>()
const selectCustomer = ref<InstanceType<typeof SelectCustomer>>()
const selectedProducts = ref<InstanceType<typeof SelectedProducts>>()
const deliveryForm = ref<InstanceType<typeof DeliveryForm>>()
const processing = ref(false)
const addresses = ref<IdAddress[]>([])
// Used to know when to refetch customer addresses.
const changedCustomer = ref(false)
const selectedCustomer = computed(() => {
  const customer = selectCustomer.value?.customer
  return customer ? `${customer?.name} ${customer?.last_name}` : null
})
const nextValidations = computed(() => [
  selectedCustomer.value === null,
  (selectedProducts.value?.list.length || 0) <= 0,
])

const fetchAddresses = () => {
  processing.value = true

  axios
    .get(route('customers.addresses', { id: selectCustomer.value?.customer }))
    .then((res) => {
      addresses.value = res.data

      stepsForm.value?.next()
      changedCustomer.value = false
    })
    .catch(() => {
      console.log('Error padre.')
    })
    .finally(() => {
      processing.value = false
    })
}

const nextActions = reactive({
  0: () => {
    changedCustomer.value ? fetchAddresses() : stepsForm.value?.next()
  },
})

watch(
  () => selectCustomer.value?.customer,
  () => {
    changedCustomer.value = true
  }
)
</script>

<template>
  <div class="h-full p-4">
    <div
      class="md-elevation-1 relative mx-auto flex h-full w-full max-w-6xl flex-col rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex items-center gap-4 p-4">
        <Link
          as="button"
          :href="route('orders')"
          tabindex="-1"
        >
          <md-icon-button>
            <Icon>arrow_back</Icon>
          </md-icon-button>
        </Link>
        <h1 class="text-xl font-medium">
          {{
            selectCustomer?.customer
              ? `Pedido para: ${selectedCustomer}`
              : 'Pedido nuevo'
          }}
        </h1>
      </div>
      <StepsForm
        ref="stepsForm"
        class="flex h-full flex-col overflow-hidden"
        :steps="4"
        :submit="() => console.log('Submitting...')"
        :nextValidations
        :nextActions
        :processing
      >
        <template #step>
          <SelectCustomer
            ref="selectCustomer"
            class="flex flex-col overflow-hidden"
          />
          <SelectedProducts
            ref="selectedProducts"
            class="flex flex-col overflow-hidden"
          />
          <div class="flex flex-col overflow-hidden">
            <h2 class="text-on-background mb-4 text-lg font-medium">Entrega</h2>
            <DeliveryForm
              ref="deliveryForm"
              class="flex h-full flex-col overflow-y-auto"
              :delivery-types="deliveryTypes"
              :delivery-apps="deliveryApps"
              :addresses
            />
          </div>
          <div>Cuarto paso</div>
        </template>
      </StepsForm>
    </div>
  </div>
</template>
