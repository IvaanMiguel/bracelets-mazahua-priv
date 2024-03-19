<script setup lang="ts">
import ProductForm from '@/Components/Forms/ProductForm.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { useModal } from '@/composables/useModal'
import useVuelidate from '@vuelidate/core'
import { ref } from 'vue'

const v = useVuelidate()
const { modal: createProductModal } = useModal('#create-product-modal')
const { modal: cancelCreateProductModal } = useModal(
  '#cancel-create-product-modal'
)

const productForm = ref<InstanceType<typeof ProductForm>>()
const storedProductSnackbar = ref<InstanceType<typeof Snackbar>>()

const store = async () => {
  const validate = await v.value.$validate()

  if (!validate) return

  productForm.value?.form.post(route('products.store'), {
    onSuccess: () => {
      productForm.value?.form.reset()
      v.value.$reset()

      storedProductSnackbar.value?.show(true)
      createProductModal.value?.close()
    }
  })
}

const cancelStore = () => {
  createProductModal.value?.close()
  cancelCreateProductModal.value?.close()

  productForm.value?.form.reset()
  v.value.$reset()
}

defineExpose({ createProductModal })
</script>

<template>
  <Modal
    id="create-product-modal"
    class="w-full"
    not-cancellable
  >
    <div slot="headline">Nuevo producto</div>

    <ProductForm
      slot="content"
      ref="productForm"
      class="grid grid-cols-2 items-start gap-6"
      :submit="store"
    />

    <div slot="actions">
      <md-text-button @click="cancelCreateProductModal?.show">
        Cancelar
      </md-text-button>
      <md-text-button
        @click="store"
        :disabled="false"
      >
        Guardar
      </md-text-button>
    </div>
  </Modal>

  <Modal
    id="cancel-create-product-modal"
    type="alert"
  >
    <div slot="headline">Descartar producto nuevo</div>
    <div slot="content">
      La información del producto nuevo aún no ha sido guardada y se descartará
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelCreateProductModal?.close">
        Cancelar
      </md-text-button>
      <md-text-button @click="cancelStore">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="storedProductSnackbar"
    text="Producto nuevo guardado con éxito."
    close-button
  />
</template>
