<script setup lang="ts">
import DisplayField from '@/Components/DisplayField.vue';
import Icon from '@/Components/Icon.vue';

defineProps<{
  products: {
    name: string
    pivot: {
      order_id: number
      product_id: number
      amount: number
      subtotal: number
    }
    price: number
  }[]
  advance: number
  total: number
  productsTotal: number
  completed: boolean
  processing?: boolean
}>()
</script>

<template>
  <div>
    <div class="mb-4 flex items-center justify-between gap-4">
      <h1 class="text-on-background text-xl font-medium">Productos pedidos</h1>
      <md-filled-tonal-button v-if="!completed" :disabled="processing">
        Editar productos
        <Icon slot="icon">edit</Icon>
      </md-filled-tonal-button>
    </div>
    <md-list
      class="max-h-[344px] overflow-auto rounded-md border border-light-outline-variant bg-light-surface-container-lowest py-0 dark:border-dark-outline-variant dark:bg-dark-surface-container"
    >
      <div class="table-cols grid">
        <div
          class="sticky top-0 z-[2] col-span-full grid grid-cols-subgrid items-center gap-4 border-b border-light-outline-variant bg-light-surface-container-highest px-4 py-3 font-medium dark:border-dark-outline-variant dark:bg-dark-surface-container-highest"
        >
          <span class="truncate">Producto</span>
          <span class="truncate">Cantidad</span>
          <span class="truncate">Subtotal</span>
        </div>
        <template v-for="(product, i) in products">
          <md-divider
            v-if="i !== 0"
            class="col-span-full"
            inset
          />
          <div class="col-span-full grid grid-cols-subgrid gap-4 px-4 py-3">
            <span class="truncate">{{ product.name }}</span>
            <span class="truncate">{{ product.pivot.amount }}</span>
            <span class="truncate">{{ `$${product.pivot.subtotal} MXN` }}</span>
          </div>
        </template>
        <div
          class="sticky bottom-0 col-span-full grid grid-cols-subgrid gap-4 border-t border-light-outline-variant bg-light-surface-container-lowest px-4 py-3 dark:border-dark-outline-variant dark:bg-dark-surface-container"
        >
          <span class="truncate font-medium">Total del productos</span>
          <span class="truncate">{{ productsTotal }}</span>
        </div>
      </div>
    </md-list>
    <div class="mt-4 grid gap-4 sm:grid-cols-2">
      <DisplayField
        label="Anticipo"
        :text="`$${advance.toFixed(2)} MXN`"
      >
        <template #icon>
          <Icon>toll</Icon>
        </template>
      </DisplayField>
      <DisplayField
        label="Total"
        :text="`$${total.toFixed(2)} MXN`"
      >
        <template #icon>
          <Icon>paid</Icon>
        </template>
      </DisplayField>
    </div>
  </div>
</template>

<style scoped>
.table-cols {
  grid-template-columns: 3fr 1fr 2fr;
}
</style>
