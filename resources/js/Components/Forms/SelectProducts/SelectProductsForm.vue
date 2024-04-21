<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
// import { store } from '@/store/orderProducts'
import {
  AvailableChangedProduct,
  AvailableProduct,
  SelectedChangedProduct,
  SelectedProduct,
} from '@/types/orders'
import { useForm } from '@inertiajs/vue3'
import '@material/web/button/filled-tonal-button'
import '@material/web/divider/divider'
import '@material/web/list/list'
import '@material/web/list/list-item'
import { computed, onMounted, provide, reactive, ref, watch } from 'vue'
import ModalSearch from './ModalSearch.vue'
import SelectProductsItem from './SelectProductsItem.vue'
import SelectProductsRemove from './SelectProductsRemove.vue'

const props = defineProps<{ defaults?: SelectedChangedProduct[] }>()

const defaultProducts = reactive<{
  notChanged: AvailableChangedProduct[]
  changed: AvailableChangedProduct[]
}>({
  notChanged: [],
  changed: []
})

provide('defaultProducts', defaultProducts)

const form = useForm<{
  products: SelectedChangedProduct[]
}>({
  products: props.defaults || [],
})

const modalSearch = ref<InstanceType<typeof ModalSearch>>()
const selectProductsRemove = ref<InstanceType<typeof SelectProductsRemove>>()
const productToRemove = ref<
  (SelectedProduct & { priceHasChanged?: boolean }) | null
>(null)
const total = computed(() => {
  return form.products.reduce((acc, product) => {
    return product.amount * product.price + acc
  }, 0)
})

const onSaveChecked = (
  checkedProducts: (AvailableProduct & { priceHasChanged?: boolean })[]
) => {
  for (const checkedProduct of checkedProducts) {
    form.products.push({
      ...checkedProduct,
      amount: 1,
      subtotal: +checkedProduct.price,
      priceHasChanged: checkedProduct.priceHasChanged || false,
    })
  }
}

const onRemovingProduct = (
  product: SelectedProduct & { priceHasChanged?: boolean }
) => {
  productToRemove.value = product
  selectProductsRemove.value?.modal?.show()
}

const onRemoveProduct = () => {
  if (productToRemove.value?.priceHasChanged) {
    modalSearch.value?.changedProductsList?.removeSavedId(
      productToRemove.value.id,
      productToRemove.value.price
    )
  } else {
    modalSearch.value?.productsList?.removeSavedId(productToRemove.value!.id)
  }

  const index = form.products.findIndex(
    (product) =>
      product.id === productToRemove.value?.id &&
      product.priceHasChanged === productToRemove.value.priceHasChanged
  )
  form.products.splice(index, 1)
  productToRemove.value = null
}

const updateDefaultProducts = () => {
  const changed = []
  const notChanged = []

  for (const product of props.defaults || []) {
    const _product = {
      id: product.id,
      name: product.name,
      price: product.price,
      stock: product.stock,
      amount: product.amount,
      priceHasChanged: product.priceHasChanged,
    }

    if (product.priceHasChanged === false) {
      notChanged.push(_product)
    } else {
      changed.push(_product)
    }
  }

  defaultProducts.changed = changed
  defaultProducts.notChanged = notChanged
}

watch(
  () => props.defaults,
  () => {
    updateDefaultProducts()
  }
)

onMounted(() => {
  updateDefaultProducts()
})

defineExpose({ form })
</script>

<template>
  <div
    v-bind="$attrs"
    class="flex flex-col gap-4"
  >
    <div class="flex items-center justify-end gap-4">
      <md-filled-tonal-button @click="modalSearch?.modal?.show">
        <Icon slot="icon">search</Icon>
        Buscar producto
      </md-filled-tonal-button>
    </div>

    <div
      class="min-h-[337px] flex-1 overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant"
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
          <SelectProductsItem
            :product="product"
            @removing-product="onRemovingProduct"
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

  <ModalSearch
    ref="modalSearch"
    @save-checked="onSaveChecked"
  />

  <SelectProductsRemove
    ref="selectProductsRemove"
    :productToRemove
    @remove-product="onRemoveProduct"
  />
</template>

<style scoped>
.table-cols {
  grid-template-columns: repeat(4, minmax(0, 1fr)) 2.5rem;
}
</style>
