<script setup lang="ts">
import { CustomerListItem } from '@/types/customers'
import { Pagination } from '@/types'

import '@material/web/elevation/elevation'
import '@material/web/tabs/primary-tab'
import '@material/web/textfield/outlined-text-field'
import '@material/web/textfield/filled-text-field'

import Icon from '@/Components/Icon.vue'
import List from './Partials/List.vue'
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
        id="add-tab"
        aria-controls="add-panel"
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
        <List
          class="mx-auto w-full max-w-7xl"
          :customers="customers"
        />
      </div>

      <div
        id="add-panel"
        class="overflow-y-auto p-4"
        aria-labelledby="add-tab"
        role="tabpanel"
      >
        Agregar cliente nuevo
      </div>
    </template>
  </Tabs>
</template>
