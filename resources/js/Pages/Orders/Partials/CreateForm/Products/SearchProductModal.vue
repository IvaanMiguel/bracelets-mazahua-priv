<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import SearchBar from '@/Components/SearchBar.vue'
import Table from '@/Components/Table.vue'
import { Pagination } from '@/types'
import { AvailableProduct } from '@/types/orders'
import { usePage } from '@inertiajs/vue3'
import { MdDialog } from '@material/web/dialog/dialog'
import { useEventListener } from '@vueuse/core'
import { computed, onMounted, reactive, ref } from 'vue'
import SearchProductItem from './SearchProductItem.vue'

const emit = defineEmits<{
  saveChecked: [checkedProducts: AvailableProduct[]]
}>()

const page = usePage()

const cancelSearchModal = ref<InstanceType<typeof Modal>>()
const searchProductModal = ref<InstanceType<typeof Modal>>()
const modal = ref<MdDialog | null>()
const cancellingModal = ref<MdDialog | null>()
const products = computed(() => {
  return page.props.products as Pagination<AvailableProduct>
})
const checkedProducts = reactive<AvailableProduct[]>([])
// Used to manage which products where checked.
const checkedIds = reactive<number[]>([])
// Dirty when there's at least one product checked.
const isDirty = ref(false)

const onCheckedProduct = (product: AvailableProduct, checked: boolean) => {
  const productFound = checkedProducts.findIndex(
    (_product) => _product.id === product.id
  )

  if (checked && productFound === -1) {
    checkedProducts.push({ ...product })
  } else if (!checked) {
    checkedProducts.splice(productFound, 1)
  }

  isDirty.value = checkedProducts.length > 0
}

const onAccept = () => {
  emit('saveChecked', checkedProducts)
  modal.value?.close()

  for (const checkedProduct of checkedProducts) {
    checkedIds.push(checkedProduct.id)
  }

  checkedProducts.splice(0, checkedProducts.length)
}

const onCancelling = () => {
  if (!isDirty.value) {
    modal.value?.close()
    return
  }

  cancellingModal.value?.show()
}

const onCancel = () => {
  modal.value?.close()
  cancellingModal.value?.close()

  checkedProducts.splice(0, checkedProducts.length)
}

const removeCheckedId = (id: number) => {
  const index = checkedIds.findIndex((_id) => _id === id)

  checkedIds.splice(index, 1)
}

const reset = () => {
  checkedProducts.splice(0, checkedProducts.length)
  checkedIds.splice(0, checkedIds.length)
  isDirty.value = false
}

useEventListener(modal, 'open', () => {
  const div = modal.value?.shadowRoot?.querySelector(
    '.content'
  ) as HTMLDivElement

  div.style.flex = '1'
  div.style.overflowY = 'auto'
  div.style.display = 'flex'
})

useEventListener(modal, 'close', () => (isDirty.value = false))

onMounted(() => {
  modal.value = searchProductModal.value?.modal
  cancellingModal.value = cancelSearchModal.value?.modal
})

defineExpose({ modal, removeCheckedId, reset })
</script>

<template>
  <Modal
    :not-cancellable="isDirty"
    ref="searchProductModal"
    class="h-full w-full max-w-[90%]"
  >
    <div slot="headline">Seleccionando productos</div>
    <div
      slot="content"
      class="flex min-h-full min-w-full flex-col overflow-hidden"
    >
      <SearchBar
        class="mb-3 w-full"
        placeholder="Buscar producto por nombre..."
        :base-url="route('orders.create')"
        name="product"
      />
      <Table
        class="flex h-full flex-col overflow-hidden rounded-md border border-light-outline-variant dark:border-dark-outline-variant"
        :headers="['', 'Producto', 'Precio', 'Stock']"
        grid-cols="grid-template-columns: 18px 3fr 2fr 1fr;"
        :pagination="products"
        :url="route('orders.create')"
        name="product"
      >
        <template v-for="(product, i) in products?.data">
          <SearchProductItem
            :product
            :checked="
              Boolean(
                checkedProducts.find((_product) => _product.id === product.id)
              )
            "
            :disabled="Boolean(checkedIds.find((id) => id === product.id))"
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
    <div slot="actions">
      <md-text-button @click="onCancelling">Cancelar</md-text-button>
      <md-text-button @click="onAccept"> Aceptar </md-text-button>
    </div>
  </Modal>

  <Modal ref="cancelSearchModal">
    <Icon slot="icon">block</Icon>
    <div slot="headline">Cancelar selección de productos</div>
    <div slot="content">
      La selección de
      <strong>{{ checkedProducts.length }} producto(s)</strong>
      será descartada, ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancellingModal?.close">Cancelar</md-text-button>
      <md-text-button @click="onCancel">Aceptar</md-text-button>
    </div>
  </Modal>
</template>

<style scoped>
#table-header {
  grid-template-columns: 18px 3fr 2fr 1fr;
}
</style>
