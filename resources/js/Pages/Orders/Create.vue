<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import SearchBar from '@/Components/SearchBar.vue'
import StepsForm from '@/Components/StepsForm.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import '@material/web/elevation/elevation'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/progress/linear-progress'
import '@material/web/radio/radio'
import { computed, ref } from 'vue'
import SelectCustomer from './Partials/CreateForm/SelectCustomer.vue'

defineOptions({ layout: MainLayout })

const stepsForm = ref<InstanceType<typeof StepsForm>>()
const selectCustomer = ref<InstanceType<typeof SelectCustomer> | null>(null)
const selectedCustomer = computed(() => {
  const customer = selectCustomer.value?.selectedCustomer

  return customer ? `${customer?.name} ${customer?.last_name}` : null
})
const validations = computed(() => [selectedCustomer.value === null])
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
        <h1 class="text-xl">
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
          <div>Segundo paso</div>
          <div>Tercer paso</div>
          <div>Cuarto paso</div>
        </template>
      </StepsForm>
    </div>
  </div>
</template>
