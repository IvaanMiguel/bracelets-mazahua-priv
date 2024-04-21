<script setup lang="ts">
import Table from '@/Components/Table.vue'
// import { store } from '@/store/orderProducts'
import { AvailableChangedProduct } from '@/types/orders'
import { inject, onMounted, reactive, ref } from 'vue'
import ModalProductItem from './ModalProductItem.vue'

const defaultProducts = inject('defaultProducts') as {
  notChanged: AvailableChangedProduct[]
  changed: AvailableChangedProduct[]
}

const isDirty = ref(false)
const savedIds = reactive<{ id: number; price: number }[]>([])
const checkedProducts = reactive<AvailableChangedProduct[]>([])

const onCheckedProduct = (
  product: AvailableChangedProduct,
  checked: boolean
) => {
  const index = checkedProducts.findIndex((_product) => {
    return _product.id === product.id && _product.price === product.price
  })

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

const removeSavedId = (id: number, price: number) => {
  const index = savedIds.findIndex(
    (product) => product.id === id && product.price === price
  )
  savedIds.splice(index, 1)
}

// const removeCheckedProduct = (id: number) => {
//   const index = checkedProducts.findIndex((product) => product.id === id)
//   checkedProducts.splice(index, 1)
// }

const saveIds = () => {
  for (const checkedProduct of checkedProducts) {
    savedIds.push({ id: checkedProduct.id, price: checkedProduct.price })
  }

  clearCheckedProducts()
}

onMounted(() => {
  clearSavedIds()

  for (const product of defaultProducts.changed) {
    savedIds.push({ id: product.id, price: product.price })
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
  <Table
    class="flex h-full flex-col overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant"
    :headers="['', 'Producto', 'Precio', 'Stock']"
    grid-cols="grid-template-columns: 18px 3fr 2fr 1fr;"
    no-paginator
  >
    <template v-for="(product, i) in defaultProducts.changed">
      <md-divider
        v-if="i !== 0"
        class="min-h-[1px]"
        inset
      />
      <ModalProductItem
        :product
        :checked="
          Boolean(
            checkedProducts.find(
              (_product) =>
                _product.id === product.id && _product.price === product.price
            )
          )
        "
        :disabled="
          Boolean(
            savedIds.find(
              (_product) =>
                _product.id === product.id && _product.price === product.price
            )
          )
        "
        @checked-product="onCheckedProduct"
      />
    </template>
  </Table>
</template>
