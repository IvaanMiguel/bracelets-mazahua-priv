<script setup lang="ts">
import SearchBar from '@/Components/SearchBar.vue'
import Table from '@/Components/Table.vue'
// import { store } from '@/store/orderProducts'
import { Pagination } from '@/types'
import {
  AvailableChangedProduct,
  AvailableProduct,
  IdOrder,
} from '@/types/orders'
import { usePage } from '@inertiajs/vue3'
import { computed, inject, onMounted, reactive, ref, watch } from 'vue'
import ModalProductItem from './ModalProductItem.vue'

const defaultProducts = inject('defaultProducts') as {
  notChanged: AvailableChangedProduct[]
  changed: AvailableChangedProduct[]
}

const page = usePage()

const isDirty = ref(false)
const order = ref(page.props.order as IdOrder)
const savedIds = reactive<number[]>([])
const checkedProducts = reactive<AvailableProduct[]>([])
const products = computed(() => {
  return page.props.products as Pagination<AvailableProduct>
})

const onCheckedProduct = (product: AvailableProduct, checked: boolean) => {
  const index = checkedProducts.findIndex(
    (_product) => _product.id === product.id
  )

  if (checked && index === -1) {
    checkedProducts.push({ ...product })
  } else if (!checked) {
    checkedProducts.splice(index, 1)
  }

  isDirty.value = checkedProducts.length > 0
}

const clearCheckedProducts = () => {
  isDirty.value = false
  checkedProducts.splice(0, checkedProducts.length)
}

const clearSavedIds = () => {
  savedIds.splice(0, savedIds.length)
}

const removeSavedId = (id: number) => {
  const index = savedIds.findIndex((_id) => _id === id)
  savedIds.splice(index, 1)
}

// const removeCheckedProduct = (id: number) => {
//   const index = checkedProducts.findIndex((product) => product.id === id)
//   checkedProducts.splice(index, 1)
// }

const saveIds = () => {
  for (const checkedProduct of checkedProducts) {
    savedIds.push(checkedProduct.id)
  }

  clearCheckedProducts()
}

watch(
  () => defaultProducts.notChanged,
  () => {
    clearSavedIds()

    for (const product of defaultProducts.notChanged) {
      savedIds.push(product.id)
    }
  }
)

onMounted(() => {
  clearSavedIds()

  for (const product of defaultProducts.notChanged) {
    savedIds.push(product.id)
  }
})

defineExpose({
  isDirty,
  // savedIds,
  checkedProducts,
  saveIds,
  clearCheckedProducts,
  // clearSavedIds,
  removeSavedId,
  // removeCheckedProduct,
})
</script>

<template>
  <div class="flex h-full flex-col">
    <SearchBar
      class="mb-3 w-full"
      placeholder="Buscar producto por nombre..."
      :base-url="route(`${route().current()}`, { order })"
    />
    <Table
      class="flex h-full flex-col overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant"
      :headers="['', 'Producto', 'Precio', 'Stock']"
      grid-cols="grid-template-columns: 18px 3fr 2fr 1fr;"
      :pagination="products"
    >
      <template v-for="(product, i) in products?.data">
        <ModalProductItem
          :product
          :checked="
            Boolean(
              checkedProducts.find((_product) => _product.id === product.id)
            )
          "
          :disabled="Boolean(savedIds.find((id) => id === product.id))"
          @checked-product="onCheckedProduct"
        />
        <md-divider
          v-if="i !== products!.data.length - 1"
          class="min-h-[1px]"
          inset
        />
      </template>
    </Table>
  </div>
</template>
