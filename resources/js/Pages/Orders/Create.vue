<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import StepsForm from '@/Components/StepsForm.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import '@material/web/button/filled-tonal-button'
import '@material/web/checkbox/checkbox'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/progress/linear-progress'
import '@material/web/radio/radio'
import '@material/web/textfield/outlined-text-field'
import { computed, ref } from 'vue'
import SelectCustomer from './Partials/CreateForm/Customer/SelectCustomer.vue'
import SelectedProducts from './Partials/CreateForm/Products/SelectedProducts.vue'

defineOptions({ layout: MainLayout })

const stepsForm = ref<InstanceType<typeof StepsForm>>()
const selectCustomer = ref<InstanceType<typeof SelectCustomer>>()
const selectedProducts = ref<InstanceType<typeof SelectedProducts>>()
const selectedCustomer = computed(() => {
  const customer = selectCustomer.value?.selectedCustomer
  return customer ? `${customer?.name} ${customer?.last_name}` : null
})
const validations = computed(() => [
  selectedCustomer.value === null,
  (selectedProducts.value?.list.length || 0) <= 0,
])
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
            selectCustomer?.selectedCustomer
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
        :nextValidations="validations"
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
          <div>Tercer paso</div>
          <div>Cuarto paso</div>
        </template>
      </StepsForm>
    </div>
  </div>
</template>
