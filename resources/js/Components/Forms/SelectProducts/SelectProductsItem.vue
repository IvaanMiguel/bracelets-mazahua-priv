<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import { SelectedChangedProduct, SelectedProduct } from '@/types/orders'
import { MdOutlinedTextField } from '@material/web/textfield/outlined-text-field'
import { useEventListener } from '@vueuse/core'
import { computed, ref, watch } from 'vue'

defineEmits<{ removingProduct: [product: SelectedProduct] }>()
const props = defineProps<{
  product: (SelectedProduct | SelectedChangedProduct) & {
    deletedAt?: string | null
  }
}>()

const textfield = ref<MdOutlinedTextField>()
const subtotal = computed(() => props.product.price * props.product.amount)
const formattedPrice = computed(() => {
  return typeof props.product.price === 'number'
    ? props.product.price.toFixed(2)
    : props.product.price
})

useEventListener(textfield, 'blur', () => {
  const value = +textfield.value!.value

  if (value < 1) {
    props.product.amount = 1
  } else if (value > props.product.stock) {
    props.product.amount = props.product.stock
  }
})

watch(
  () => props.product.amount,
  () => {
    props.product.subtotal = subtotal.value
  }
)
</script>

<template>
  <md-list-item>
    <div
      :class="[
        'grid grid-cols-4 items-center gap-4',
        { 'text-light-error dark:text-dark-error': product.deletedAt },
      ]"
    >
      <div class="flex items-center gap-1 truncate">
        <span class="truncate">{{ product.name }}</span>
        <Icon
          v-if="product.deletedAt"
          size="1.25rem"
          title="Este producto ya ha sido eliminado."
        >
          report
        </Icon>
      </div>
      <span class="truncate">{{ `$${formattedPrice} MXN` }}</span>
      <md-outlined-text-field
        ref="textfield"
        class="max-h-[3.25rem] w-full md:w-2/3"
        type="number"
        min="1"
        maxlength="4"
        :max="product.stock"
        v-model="product.amount"
      />
      <span class="truncate">
        {{ `$${subtotal.toFixed(2)} MXN` }}
      </span>
    </div>
    <div slot="end">
      <md-icon-button
        id="remove-button"
        title="Remover producto del pedido"
        @click="$emit('removingProduct', product)"
      >
        <Icon>remove</Icon>
      </md-icon-button>
    </div>
  </md-list-item>
</template>

<style scoped>
.dark #remove-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.error);
}
#remove-button {
  --md-sys-color-on-surface-variant: theme(colors.light.error);
}
</style>
