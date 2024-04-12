<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import { SelectedProduct } from '@/types/orders'
import { useForm } from '@inertiajs/vue3'
import '@material/web/button/filled-button'
import '@material/web/divider/divider'
import '@material/web/list/list'
import '@material/web/list/list-item'
import SelectProductItem from './SelectProductItem.vue'
import { computed } from 'vue'

const props = defineProps<{
  defaults?: SelectedProduct[]
}>()

const form = useForm({
  products: props.defaults || [],
})

const total = computed(() => {
  return form.products.reduce((acc, product) => {
    return (product.amount * product.price) + acc
  }, 0)
})
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-end gap-4">
      <md-filled-button @click="">
        <Icon slot="icon">search</Icon>
        Buscar producto
      </md-filled-button>
    </div>

    <div
      class="flex-1 overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant min-h-[337px]"
    >
      <md-list
        class="h-full overflow-y-auto bg-light-surface-container-lowest py-0 dark:bg-dark-surface-container"
      >
        <div
          class="table-cols sticky top-0 z-[2] grid min-h-14 items-center gap-4 border-b border-light-outline-variant bg-light-surface-container-highest px-4 py-0 font-medium dark:border-dark-outline-variant dark:bg-dark-surface-container-highest"
        >
          <span class="truncate">Producto</span>
          <span class="truncate">Precio</span>
          <span class="truncate">Cantidad</span>
          <span class="truncate">Subtotal</span>
        </div>
        <template
          v-if="form.products.length"
          v-for="(product, i) in form.products"
        >
          <md-divider
            v-if="i !== 0"
            class="min-h-[1px]"
            inset
          />
          <SelectProductItem
            :product="product"
            @removing-product=""
          />
        </template>
        <template v-else>
          <div class="mt-auto grid place-items-center p-4">
            <div class="text-center">
              <Icon
                class="text-light-on-background dark:text-dark-on-background"
                size="4rem"
              >
                shopping_cart_off
              </Icon>
              <span
                class="block text-xl font-medium text-light-on-background dark:text-dark-on-background"
              >
                Sin productos añadidos.
              </span>
            </div>
          </div>
        </template>
        <div
          class="table-cols sticky bottom-0 z-[2] mt-auto grid gap-4 border-t border-light-outline-variant bg-light-surface-container-lowest px-4 py-3 dark:border-dark-outline-variant dark:bg-dark-surface-container"
        >
          <div class="truncate font-medium">Anticipo</div>
          <div class="truncate">{{ `$${(total / 2).toFixed(2)} MXN` }}</div>
          <div class="truncate font-medium">Total</div>
          <div class="truncate">{{ `$${total.toFixed(2)} MXN` }}</div>
        </div>
      </md-list>
    </div>
  </div>
</template>

<style scoped>
.table-cols {
  grid-template-columns: repeat(4, minmax(0, 1fr)) 2.5rem;
}
</style>
