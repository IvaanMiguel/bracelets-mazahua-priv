<script setup lang="ts">
import SelectProductsForm from '@/Components/Forms/SelectProducts/SelectProductsForm.vue'
import Icon from '@/Components/Icon.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { IdOrderEdit } from '@/types/orders'
import '@material/web/button/filled-button'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'

defineOptions({ layout: MainLayout })
const props = defineProps<{
  order: IdOrderEdit
  customer: { name: string; last_name: string }
}>()

const getProducts = () => {
  const products = []

  for (const product of props.order.products) {
    /*
     * If since the product was added to the order, it price has changed, it will
     * remain with the exact same price, and also you will be able to add more of that
     * product, but with the new price.
     */
    const priceHasChanged =
      +product.price !== +(product.pivot.subtotal / product.pivot.amount)

    products.push({
      id: product.pivot.product_id,
      name: product.name,
      amount: product.pivot.amount,
      subtotal: product.pivot.subtotal,
      price: priceHasChanged
        ? product.pivot.subtotal / product.pivot.amount
        : product.price,
      stock: priceHasChanged
        ? product.pivot.amount
        : product.pivot.amount + product.stock,
    })
  }

  return products
}
</script>

<template>
  <div class="h-full p-4">
    <div
      class="md-elevation-1 relative mx-auto flex h-full w-full max-w-6xl flex-col rounded-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <md-elevation />
      <div class="flex h-full flex-col overflow-auto">
        <div class="flex items-center gap-4 p-4 pb-0">
          <Link
            :href="route('orders.show', { id: order.id })"
            tabindex="-1"
          >
            <md-icon-button>
              <Icon>arrow_back</Icon>
            </md-icon-button>
          </Link>
          <h1 class="text-on-background text-xl font-medium">
            Editando productos de {{ `${customer.name} ${customer.last_name}` }}
          </h1>
        </div>
        <SelectProductsForm
          class="h-full overflow-auto px-4 pt-4"
          :defaults="getProducts()"
        />
        <div class="p-4 text-end">
          <md-filled-button>
            <Icon slot="icon">save</Icon>
            Guardar cambios
          </md-filled-button>
        </div>
      </div>
    </div>
  </div>
</template>
