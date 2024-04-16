<script setup lang="ts">
import Table from '@/Components/Table.vue'
import { AvailableChangedProduct } from '@/types/orders'
import { inject, onMounted, reactive, ref } from 'vue'
import ModalProductItem from './ModalProductItem.vue'

const { changed: changedProducts } = inject('defaultProducts') as {
  changed: AvailableChangedProduct[]
}

const isDirty = ref(false)
const checkedIds = reactive<number[]>([])
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

const clearCheckedIds = () => {
  checkedIds.splice(0, checkedIds.length)
}

const removeCheckedId = (id: number) => {
  const index = checkedIds.findIndex((_id) => _id === id)
  checkedIds.splice(index, 1)
}

const removeCheckedProduct = (id: number) => {
  const index = checkedProducts.findIndex((product) => product.id === id)
  checkedProducts.splice(index, 1)
}

const saveCheckedIds = () => {
  for (const checkedProduct of checkedProducts) {
    checkedIds.push(checkedProduct.id)
  }

  clearCheckedProducts()
}

onMounted(() => {
  for (const product of changedProducts) {
    checkedIds.push(product.id)
  }
})

defineExpose({
  isDirty,
  checkedIds,
  checkedProducts,
  saveCheckedIds,
  clearCheckedProducts,
  clearCheckedIds,
  removeCheckedId,
  removeCheckedProduct,
})
</script>

<template>
  <Table
    class="flex h-full flex-col overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant"
    :headers="['', 'Producto', 'Precio', 'Stock']"
    grid-cols="grid-template-columns: 18px 3fr 2fr 1fr;"
    no-paginator
  >
    <template v-for="(product, i) in changedProducts">
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
        :disabled="Boolean(checkedIds.find((id) => id === product.id))"
        @checked-product="onCheckedProduct"
      />
      <md-divider
        v-if="i !== changedProducts!.length - 1"
        class="min-h-[1px]"
        inset
      />
    </template>
  </Table>
</template>
