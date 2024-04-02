<script setup lang="ts">
import SearchBar from '@/Components/SearchBar.vue'
import Table from '@/Components/Table.vue'
import { Pagination } from '@/types'
import { Customer } from '@/types/orders'
import { usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import SelectCustomerItem from './SelectCustomerItem.vue'

const page = usePage()
const selectedCustomer = ref<Customer | null>(null)
const customers = computed(() => {
  return page.props.customers as Pagination<Customer>
})

const onSelectedCustomer = (customer: Customer) => {
  selectedCustomer.value = customer
}

defineExpose({ customer: selectedCustomer })
</script>

<template>
  <div>
    <h2 class="text-on-background text-lg font-medium">Clientes disponibles</h2>
    <SearchBar
      class="my-3 w-full"
      placeholder="Buscar cliente por nombre..."
      :base-url="route('orders.create')"
      name="customer"
    />

    <Table
      class="flex h-full flex-col overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant"
      no-headers
      :pagination="customers"
      :url="route('orders.create')"
      name="customer"
    >
      <template v-for="(customer, i) in customers.data">
        <SelectCustomerItem
          :customer
          :selected="customer.id === selectedCustomer?.id"
          @selected-customer="onSelectedCustomer"
        />
        <md-divider
          v-if="i !== customers.data.length - 1"
          class="min-h-[1px]"
          inset
        />
      </template>
    </Table>
  </div>
</template>
