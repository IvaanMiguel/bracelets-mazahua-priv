<script setup lang="ts">
import DeliveryForm from '@/Components/Forms/Delivery/DeliveryForm.vue'
import PaymentForm from '@/Components/Forms/PaymentForm.vue'
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import StepsForm from '@/Components/StepsForm.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { IdAddress } from '@/types/customers'
import { Catalog } from '@/types/orders'
import { router } from '@inertiajs/vue3'
import '@material/web/button/filled-tonal-button'
import '@material/web/checkbox/checkbox'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/progress/linear-progress'
import '@material/web/radio/radio'
import '@material/web/textfield/outlined-text-field'
import useVuelidate, { Validation } from '@vuelidate/core'
import axios from 'axios'
import { computed, reactive, ref, watch } from 'vue'
import Checkout from './Partials/Checkout.vue'
import SelectCustomer from './Partials/CreateForm/Customer/SelectCustomer.vue'
import SelectedProducts from './Partials/CreateForm/Products/SelectedProducts.vue'

defineOptions({ layout: MainLayout })
defineProps<{
  deliveryTypes: Catalog[]
  deliveryApps: Catalog[]
  paymentTypes: Catalog[]
}>()

const v = useVuelidate()

const storedOrderSnackbar = ref<InstanceType<typeof Snackbar>>()
const stepsForm = ref<InstanceType<typeof StepsForm>>()
const selectCustomer = ref<InstanceType<typeof SelectCustomer>>()
const selectedProducts = ref<InstanceType<typeof SelectedProducts>>()
const deliveryForm = ref<InstanceType<typeof DeliveryForm>>()
const paymentForm = ref<InstanceType<typeof PaymentForm>>()

const postProcessing = ref(false)
const stepsFormProcessing = ref(false)
const addresses = ref<IdAddress[]>([])
// Used to know when to refetch customer addresses.
const changedCustomer = ref(false)
const selectedCustomer = computed(() => {
  const customer = selectCustomer.value?.customer
  return customer ? `${customer?.name} ${customer?.last_name}` : null
})
const disableNext = computed(() => ({
  0: () => selectCustomer.value?.disableNext || false,
  1: () => selectedProducts.value?.disableNext || false,
  3: () => {
    switch (paymentForm.value?.form.payment_type_id) {
      case 1:
        return false
      case 2:
        return paymentForm.value.form.clabe === ''
      case 3:
        return (
          paymentForm.value.form.name === '' ||
          paymentForm.value.form.cardNumber === ''
        )
    }

    return true
  },
  4: () => postProcessing.value,
}))

const fetchAddresses = () => {
  stepsFormProcessing.value = true

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
      stepsFormProcessing.value = false
    })
}

const nextActions = reactive({
  0: () => {
    changedCustomer.value ? fetchAddresses() : stepsForm.value?.next()
  },
  3: () => {
    const paymentValidations = v.value.payment as Validation

    switch (paymentForm.value?.form.payment_type_id) {
      case 2:
        paymentValidations.clabe.$touch()
        break
      case 3:
        paymentValidations.name.$touch()
        paymentValidations.cardNumber.$touch()
        break
    }

    if (paymentValidations.$errors.length) return

    stepsForm.value?.next()
  },
})

const submit = () => {
  router.post(
    route('orders.store'),
    {
      customer_id: selectCustomer.value?.customer?.id,
      payment_type_id: paymentForm.value?.form.payment_type_id,
      details: paymentForm.value?.form.details,
      products: selectedProducts.value?.reducedList,
      ...deliveryForm.value?.form.data(),
    },
    {
      onStart: () => (postProcessing.value = true),
      onSuccess: () => {
        stepsForm.value!.activeIndex = 0
        selectCustomer.value!.customer = null
        addresses.value.splice(0, addresses.value.length)

        paymentForm.value?.form.reset()
        deliveryForm.value?.form.reset()
        selectedProducts.value?.reset()
        v.value.$reset()

        storedOrderSnackbar.value?.show(true)
      },
      onFinish: () => (postProcessing.value = false),
    }
  )
}

watch(
  () => paymentForm.value?.form.payment_type_id,
  () => {
    v.value.$reset()
  }
)

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
        :steps="5"
        :submit
        :disableNext
        :nextActions
        :processing="stepsFormProcessing"
      >
        <template #step>
          <SelectCustomer
            ref="selectCustomer"
            class="flex flex-col overflow-hidden p-4"
          />
          <SelectedProducts
            ref="selectedProducts"
            class="flex flex-col overflow-hidden p-4"
          />
          <div class="flex flex-col overflow-hidden">
            <h2 class="text-on-background p-4 pb-1 text-lg font-medium">
              Entrega
            </h2>
            <DeliveryForm
              ref="deliveryForm"
              class="flex h-full flex-col overflow-y-auto p-4"
              :delivery-types="deliveryTypes"
              :delivery-apps="deliveryApps"
              :addresses
            />
          </div>
          <div class="flex flex-col overflow-hidden">
            <h2 class="text-on-background p-4 pb-1 text-lg font-medium">
              Pago
            </h2>
            <PaymentForm
              ref="paymentForm"
              class="grid gap-6 overflow-y-auto p-4 md:grid-cols-2"
              :paymentTypes
              :customer-name="selectCustomer?.customer"
              :config="{ $registerAs: 'payment' }"
            />
          </div>
          <Checkout
            class="flex flex-col overflow-auto p-4"
            :customer="selectCustomer?.customer"
            :products="selectedProducts?.list"
            :advance="selectedProducts?.advance"
            :total="selectedProducts?.total"
            :delivery="deliveryForm?.form.data()"
            :delivery-address="
              addresses.find(
                (address) => address.id === deliveryForm?.form.address_id
              )
            "
            :payment="paymentForm?.form.data()"
          />
        </template>
      </StepsForm>
    </div>
  </div>

  <Snackbar
    ref="storedOrderSnackbar"
    text="El pedido ha sido realizado con éxito."
    close-button
  />
</template>
