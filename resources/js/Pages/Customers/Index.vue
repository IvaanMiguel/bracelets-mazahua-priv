<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Tabs from '@/Components/Tabs.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Pagination } from '@/types'
import { CustomerListItem } from '@/types/customers'
import '@material/web/button/filled-button'
import '@material/web/button/filled-tonal-button'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/tabs/primary-tab'
import Create from './Partials/Create.vue'
import CustomersList from './Partials/CustomersList.vue'

defineOptions({ layout: MainLayout })
defineProps<{ customers: Pagination<CustomerListItem> }>()
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
