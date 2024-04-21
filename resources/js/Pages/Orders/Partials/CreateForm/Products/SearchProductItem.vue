<script setup lang="ts">
import HighlightSearch from '@/Components/HighlightSearch.vue'
import { AvailableProduct } from '@/types/orders'
import { usePage } from '@inertiajs/vue3'
import { MdCheckbox } from '@material/web/checkbox/checkbox'
import { MdListItem } from '@material/web/list/list-item'
import { computed, ref } from 'vue'

const emit = defineEmits<{
  checkedProduct: [product: AvailableProduct, checked: boolean]
}>()
const props = defineProps<{
  product: AvailableProduct
  checked: boolean
  disabled: boolean
}>()

const page = usePage()

const search = computed(() => page.props.filters.search as { product: string })
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
      <HighlightSearch
        class="truncate"
        :text="`${product.name}`"
        :search="`${search.product}`"
      />
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
