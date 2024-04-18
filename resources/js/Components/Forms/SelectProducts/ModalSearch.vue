<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Tabs from '@/Components/Tabs.vue'
import { AvailableChangedProduct } from '@/types/orders'
import { MdDialog } from '@material/web/dialog/dialog'
import { useEventListener } from '@vueuse/core'
import { computed, inject, onMounted, ref } from 'vue'
import ModalChangedProductsList from './ModalChangedProductsList.vue'
import ModalProductsList from './ModalProductsList.vue'

const { changed: changedProducts } = inject('defaultProducts') as {
  changed: AvailableChangedProduct[]
}

const emit = defineEmits<{
  saveChecked: [checkedProducts: AvailableChangedProduct[]]
}>()

const cancelSearchModal = ref<InstanceType<typeof Modal>>()
const searchProductModal = ref<InstanceType<typeof Modal>>()
const modal = ref<MdDialog | null>()
const cancellingModal = ref<MdDialog | null>()
const productsList = ref<InstanceType<typeof ModalProductsList>>()
const changedProductsList = ref<InstanceType<typeof ModalChangedProductsList>>()
const isDirty = computed(() => {
  return productsList.value?.isDirty || changedProductsList.value?.isDirty
})
const checkedProductsCount = computed(() => {
  const notChangedCount = productsList.value?.checkedProducts.length || 0
  const changedCount = changedProductsList.value?.checkedProducts.length || 0

  return notChangedCount + changedCount
})

const onAccept = () => {
  const checkedProducts = productsList.value!.checkedProducts.concat(
    changedProductsList.value?.checkedProducts || []
  )

  emit('saveChecked', checkedProducts)
  modal.value?.close()

  productsList.value?.saveIds()
  changedProductsList.value?.saveIds()
}

const onCancel = () => {
  modal.value?.close()
  cancellingModal.value?.close()

  productsList.value?.clearCheckedProducts()
  changedProductsList.value?.clearCheckedProducts()
}

// const removeSavedId = (id: number) => {
//   productsList.value?.removeSavedId(id)
// }

// const reset = () => {
//   productsList.value?.clearCheckedProducts()
//   productsList.value?.clearSavedIds()
// }

useEventListener(modal, 'open', () => {
  const div = modal.value?.shadowRoot?.querySelector(
    '.content'
  ) as HTMLDivElement

  div.style.flex = '1'
  div.style.overflowY = 'auto'
  div.style.display = 'flex'
})

onMounted(() => {
  modal.value = searchProductModal.value?.dialog
  cancellingModal.value = cancelSearchModal.value?.dialog
})

defineExpose({
  modal,
  // removeSavedId,
  // reset,
  productsList,
  changedProductsList,
})
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
      class="flex min-h-full min-w-full flex-col overflow-hidden px-0"
    >
      <Tabs v-if="changedProducts?.length">
        <template #tab>
          <md-primary-tab
            id="products-tab"
            aria-controls="products-panel"
          >
            <Icon slot="icon">list_alt</Icon>
            Lista de productos
          </md-primary-tab>
          <md-primary-tab
            id="changed-products-tab"
            aria-controls="changed-products-panel"
          >
            <Icon slot="icon">box</Icon>
            Productos originales
          </md-primary-tab>
        </template>

        <template #panel>
          <div
            id="products-panel"
            class="flex flex-col overflow-hidden px-6 pb-0 pt-2"
            aria-labelledby="products-tab"
            role="tabpanel"
          >
            <ModalProductsList ref="productsList" />
          </div>
          <div
            id="changed-products-panel"
            class="flex flex-col overflow-hidden px-6 pb-0 pt-2"
            aria-labelledby="changed-products-tab"
            role="tabpanel"
          >
            <ModalChangedProductsList ref="changedProductsList" />
          </div>
        </template>
      </Tabs>

      <ModalProductsList
        v-else
        ref="productsList"
        class="mx-6"
      />
    </div>
    <div slot="actions">
      <md-text-button
        @click="isDirty ? cancellingModal?.show() : modal?.close()"
      >
        Cancelar
      </md-text-button>
      <md-text-button @click="onAccept"> Aceptar </md-text-button>
    </div>
  </Modal>

  <Modal ref="cancelSearchModal">
    <Icon slot="icon">block</Icon>
    <div slot="headline">Cancelar selección de productos</div>
    <div slot="content">
      La selección de
      <strong>{{ checkedProductsCount }} producto(s)</strong>
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
./ModalProductItem.vue
