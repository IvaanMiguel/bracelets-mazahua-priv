<script setup lang="ts">
import SearchBar from '@/Components/SearchBar.vue'
import Table from '@/Components/Table.vue'
import { Pagination } from '@/types'
import { CustomerListItem } from '@/types/customers'
import { router } from '@inertiajs/vue3'
import { onMounted } from 'vue'

defineProps<{ customers: Pagination<CustomerListItem> }>()

onMounted(() => {
  router.reload({ only: ['customers', 'destroyed'] })
})
</script>

<template>
  <div
    class="md-elevation-1 relative flex h-full flex-col rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
  >
    <md-elevation />
    <div class="flex items-center justify-between gap-4 p-4 pb-2">
      <h1
        class="text-2xl font-medium text-light-on-background dark:text-dark-on-background"
      >
        {{ `${customers.total} cliente${customers.total !== 1 ? 's' : ''}` }}
      </h1>
      <SearchBar
        class="w-full max-w-96"
        placeholder="Buscar por nombre..."
        :base-url="route('customers')"
      />
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
              <span>{{ customer.name }}</span>
              <span>{{ customer.last_name }}</span>
              <span>{{ customer.phone_number }}</span>
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
</template>
