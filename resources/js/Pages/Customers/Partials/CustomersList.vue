<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Paginator from '@/Components/Paginator.vue'
import SearchBar from '@/Components/SearchBar.vue'
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
    class="md-elevation-1 relative rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
  >
    <md-elevation />
    <div class="flex items-center justify-between gap-4 p-4 pb-2">
      <h1
        class="text-2xl font-medium text-light-on-background dark:text-dark-on-background"
      >
        {{ customers.total }} clientes
      </h1>
      <SearchBar
        placeholder="Buscar por nombre..."
        :base-url="route('customers')"
      />
    </div>
    <Paginator
      class="justify-end p-4 pt-2"
      :pagination="customers"
      :selected-results="$page.props.filters.results"
      :base-url="route('customers')"
    />
    <md-list
      v-if="customers.data.length"
      class="bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-divider inset />
      <template v-for="customer in customers.data">
        <Link
          :href="route('customers.show', { id: customer.id })"
          tabindex="-1"
        >
          <md-list-item type="button">
            {{ customer.name }} {{ customer.last_name }}
          </md-list-item>
        </Link>
        <md-divider inset />
      </template>
    </md-list>
    <template v-else>
      <md-divider inset />
      <div class="p-4 text-center">
        <Icon
          class="text-light-on-background dark:text-dark-on-background"
          size="3rem"
        >
          search_off
        </Icon>
        <span
          class="block text-xl font-medium text-light-on-background dark:text-dark-on-background"
        >
          No se han encontrado coincidencias para "{{
            $page.props.filters.search
          }}".
        </span>
      </div>
      <md-divider inset />
    </template>
    <Paginator
      class="justify-end p-4 pt-2"
      :pagination="customers"
      :selected-results="$page.props.filters.results"
      :base-url="route('customers')"
    />
  </div>
</template>
