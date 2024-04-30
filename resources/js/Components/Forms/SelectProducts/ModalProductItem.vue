<script setup lang="ts">
import HighlightSearch from '@/Components/HighlightSearch.vue'
import Icon from '@/Components/Icon.vue'
import { AvailableChangedProduct } from '@/types/orders'
import { MdCheckbox } from '@material/web/checkbox/checkbox'
import { MdListItem } from '@material/web/list/list-item'
import { computed, ref } from 'vue'

const emit = defineEmits<{
  checkedProduct: [product: AvailableChangedProduct, checked: boolean]
}>()
const props = defineProps<{
  product: AvailableChangedProduct & { deleted_at: string | null }
  checked: boolean
  disabled: boolean
}>()

const checkbox = ref<MdCheckbox | null>()
const listItem = ref<MdListItem | null>()
const formattedPrice = computed(() => {
  return typeof props.product.price === 'number'
    ? props.product.price.toFixed(2)
    : props.product.price
})

const onClick = () => {
  if (listItem.value?.disabled) return

  const product = {
    id: props.product.id,
    name: props.product.name,
    price: props.product.price,
    stock: props.product.stock,
    priceHasChanged: props.product.priceHasChanged || false,
  }

  checkbox.value!.checked = !checkbox.value?.checked
  emit('checkedProduct', product, checkbox.value!.checked)
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
    <div
      :class="[
        'search-product-row grid gap-4',
        { 'text-light-error dark:text-dark-error': product.deleted_at },
      ]"
    >
      <div class="flex items-center gap-1 truncate">
        <HighlightSearch
          class="truncate"
          :text="`${product.name}`"
          :search="`${$page.props.filters.search}`"
        />
        <Icon
          v-if="product.deleted_at"
          size="1.25rem"
          title="Este producto ya ha sido eliminado."
        >
          report
        </Icon>
      </div>
      <span class="truncate">{{ `$${formattedPrice} MXN` }}</span>
      <span class="truncate">{{ product.stock }}</span>
    </div>
  </md-list-item>
</template>

<style scoped>
.search-product-row {
  grid-template-columns: 3fr 2fr 1fr;
}
</style>
