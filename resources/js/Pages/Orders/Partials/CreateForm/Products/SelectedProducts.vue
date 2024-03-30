<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import { AvailableProduct, SelectedProduct } from '@/types/orders'
import { computed, reactive, ref } from 'vue'
import RemoveProductModal from './RemoveProductModal.vue'
import SearchProductModal from './SearchProductModal.vue'
import SelectedProductItem from './SelectedProductItem.vue'

const searchProductModal = ref<InstanceType<typeof SearchProductModal>>()
const removeProductModal = ref<InstanceType<typeof RemoveProductModal>>()
const selectedProducts = reactive<SelectedProduct[]>([])
const productToRemove = ref<SelectedProduct | null>(null)
const total = computed(() => {
  return selectedProducts.reduce((acc, product) => {
    return product.amount * product.price + acc
  }, 0)
})
const advance = computed(() => total.value / 2)

const onSaveChecked = (checkedProducts: AvailableProduct[]) => {
  for (const checkedProduct of checkedProducts) {
    selectedProducts.push({
      ...checkedProduct,
      amount: 1,
      subtotal: checkedProduct.price,
    })
  }
}

const onRemovingProduct = (product: SelectedProduct) => {
  removeProductModal.value?.modal?.show()
  productToRemove.value = product
}

const onRemoveProduct = () => {
  searchProductModal.value?.removeCheckedId(productToRemove.value!.id)
  const index = selectedProducts.findIndex(
    (_product) => _product.id === productToRemove.value?.id
  )
  selectedProducts.splice(index, 1)

  productToRemove.value = null
}

defineExpose({ list: selectedProducts })
</script>

<template>
  <div>
    <div class="mb-3 flex items-center justify-between gap-4">
      <h2 class="text-on-background text-lg font-medium">Productos</h2>
      <md-filled-tonal-button @click="searchProductModal?.modal?.show">
        <Icon slot="icon">search</Icon>
        Buscar producto
      </md-filled-tonal-button>
    </div>

    <div
      class="h-full overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant"
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
          v-if="selectedProducts.length"
          v-for="(selectedProduct, i) in selectedProducts"
        >
          <SelectedProductItem
            :product="selectedProduct"
            @removing-product="onRemovingProduct"
          />
          <md-divider
            v-if="i !== selectedProducts.length - 1"
            class="min-h-[1px]"
            inset
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
          class="table-cols table-cols sticky bottom-0 z-[2] mt-auto grid gap-4 border-t border-light-outline-variant bg-light-surface-container-lowest px-4 py-3 dark:border-dark-outline-variant dark:bg-dark-surface-container"
        >
          <div class="truncate font-medium">Anticipo</div>
          <div class="truncate">{{ `$${advance.toFixed(2)} MXN` }}</div>
          <div class="truncate font-medium">Total</div>
          <div class="truncate">{{ `$${total.toFixed(2)} MXN` }}</div>
        </div>
      </md-list>
    </div>

    <SearchProductModal
      ref="searchProductModal"
      @save-checked="onSaveChecked"
    />

    <RemoveProductModal
      ref="removeProductModal"
      :productToRemove
      @remove-product="onRemoveProduct"
    />
  </div>
</template>

<style scoped>
.table-cols {
  grid-template-columns: repeat(4, minmax(0, 1fr)) 2.5rem;
}
</style>
