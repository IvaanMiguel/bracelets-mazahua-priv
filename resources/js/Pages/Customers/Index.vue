<script setup lang="ts">
import { CustomerListItem } from '@/types/customers'
import { Pagination } from '@/types'

import '@material/web/tabs/primary-tab'

import Create from './Partials/Create.vue'
import Icon from '@/Components/Icon.vue'
import CustomersList from './Partials/CustomersList.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import Tabs from '@/Components/Tabs.vue'

defineOptions({
  layout: MainLayout,
})

defineProps<{
  customers: Pagination<CustomerListItem>
}>()
</script>

<template>
  <Tabs auto-activate>
    <template #tab>
      <md-primary-tab
        id="list-tab"
        aria-controls="list-panel"
      >
        <Icon slot="icon">list_alt</Icon>
        Clientes
      </md-primary-tab>
      <md-primary-tab
        id="create-tab"
        aria-controls="create-panel"
      >
        <Icon slot="icon">add</Icon>
        Agregar cliente nuevo
      </md-primary-tab>
    </template>

    <template #panel>
      <div
        id="list-panel"
        class="overflow-y-auto p-4"
        aria-labelledby="list-tab"
        role="tabpanel"
      >
        <CustomersList
          class="mx-auto w-full max-w-6xl"
          :customers="customers"
        />
      </div>

      <div
        id="create-panel"
        class="overflow-y-auto p-4"
        aria-labelledby="create-tab"
        role="tabpanel"
      >
        <Create class="mx-auto w-full max-w-6xl" />
      </div>
    </template>
  </Tabs>
</template>
