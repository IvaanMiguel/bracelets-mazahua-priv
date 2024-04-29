<script setup lang="ts">
import HighlightSearch from '@/Components/HighlightSearch.vue'
import Icon from '@/Components/Icon.vue'
import SearchBar from '@/Components/SearchBar.vue'
import Snackbar from '@/Components/Snackbar.vue'
import Table from '@/Components/Table.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Pagination } from '@/types'
import { CustomerListItem } from '@/types/customers'
import { router } from '@inertiajs/vue3'
import '@material/web/button/filled-button'
import '@material/web/button/filled-tonal-button'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import '@material/web/tabs/primary-tab'
import { onMounted, ref } from 'vue'

defineOptions({ layout: MainLayout })
const props = defineProps<{
  customers: Pagination<CustomerListItem>
  destroyed?: boolean
}>()

const destroyedCustomerSnackbar = ref<InstanceType<typeof Snackbar>>()

onMounted(() => {
  router.reload({ only: ['customers', 'destroyed'] })

  if (!props.destroyed) return

  destroyedCustomerSnackbar.value?.show(true)
})
</script>

<template>
  <div class="h-full p-4">
    <div
      class="md-elevation-1 relative mx-auto flex h-full w-full max-w-6xl flex-col rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex items-center justify-between gap-4 p-4 pb-2">
        <h1
          class="text-2xl font-medium text-light-on-background dark:text-dark-on-background"
        >
          {{ `${customers.total} cliente${customers.total !== 1 ? 's' : ''}` }}
        </h1>
        <SearchBar
          class="ms-auto w-full max-w-96"
          placeholder="Buscar..."
          :base-url="route('customers')"
        />

        <Link
          :href="route('customers.create')"
          tabindex="-1"
        >
          <md-filled-button>
            <Icon slot="icon">add</Icon>
            Agregar
          </md-filled-button>
        </Link>
      </div>
      <Table
        class="flex h-full flex-col overflow-hidden"
        :headers="['Nombre(s)', 'Apellido(s)', 'Número celular']"
        :pagination="customers"
        :url="route('customers')"
      >
        <template v-for="(customer, i) in customers.data">
          <Link
            :href="route('customers.show', { id: customer.id })"
            tabindex="-1"
          >
            <md-list-item type="button">
              <div class="grid grid-cols-3 gap-4">
                <HighlightSearch
                  class="truncate"
                  :text="`${customer.name}`"
                  :search="`${$page.props.filters.search}`"
                />
                <HighlightSearch
                  class="truncate"
                  :text="`${customer.last_name}`"
                  :search="`${$page.props.filters.search}`"
                />
                <HighlightSearch
                  class="truncate"
                  :text="`${customer.phone_number}`"
                  :search="`${$page.props.filters.search}`"
                />
              </div>
            </md-list-item>
            <md-divider
              v-if="i !== customers.data.length - 1"
              inset
            />
          </Link>
        </template>
      </Table>
    </div>
  </div>

  <Snackbar
    ref="destroyedCustomerSnackbar"
    text="Cliente eliminado con éxito."
    close-button
  />
</template>
