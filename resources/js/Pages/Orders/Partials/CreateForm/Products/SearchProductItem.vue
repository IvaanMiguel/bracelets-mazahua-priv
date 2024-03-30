<script setup lang="ts">
import { AvailableProduct } from '@/types/orders'
import { MdCheckbox } from '@material/web/checkbox/checkbox'
import { MdListItem } from '@material/web/list/list-item'
import { ref } from 'vue'

const emit = defineEmits<{
  checkedProduct: [product: AvailableProduct, checked: boolean]
}>()
const props = defineProps<{
  product: AvailableProduct
  checked: boolean
  disabled: boolean
}>()

const checkbox = ref<MdCheckbox | null>()
const listItem = ref<MdListItem | null>()

const onClick = () => {
  if (listItem.value?.disabled) return

  checkbox.value!.checked = !checkbox.value?.checked
  emit('checkedProduct', props.product, checkbox.value!.checked)
}
</script>

<template>
  <md-list-item
    ref="listItem"
    type="button"
    :disabled
    @click="onClick"
  >
    <md-checkbox
      slot="start"
      ref="checkbox"
      :checked="checked || disabled"
    />
    <div class="search-product-row grid gap-4">
      <span class="truncate">{{ product.name }}</span>
      <span class="truncate">{{ `$${product.price} MXN` }}</span>
      <span class="truncate">{{ product.stock }}</span>
    </div>
  </md-list-item>
</template>

<style scoped>
.search-product-row {
  grid-template-columns: 3fr 2fr 1fr;
}
</style>
