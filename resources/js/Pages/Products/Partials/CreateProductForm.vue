<script setup lang="ts">
import ProductForm from '@/Components/Forms/ProductForm.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const v = useVuelidate()

const createModal = ref<InstanceType<typeof Modal>>()
const cancelCreateModal = ref<InstanceType<typeof Modal>>()
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
      createModal.value?.dialog?.close()
    },
  })
}

const cancelStore = () => {
  createModal.value?.dialog?.close()
  cancelCreateModal.value?.dialog?.close()
}

onMounted(() => {
  useEventListener(createModal.value?.dialog, 'closed', () => {
    productForm.value?.form.reset()
    v.value.$reset()
  })
})

defineExpose({ createModal })
</script>

<template>
  <Modal
    ref="createModal"
    class="w-full"
    :not-cancellable="productForm?.form.isDirty"
  >
    <div slot="headline">Nuevo producto</div>

    <ProductForm
      slot="content"
      ref="productForm"
      class="grid grid-cols-2 items-start gap-6"
      :submit="store"
    />

    <div slot="actions">
      <md-text-button
        @click="
          productForm?.form.isDirty
            ? cancelCreateModal?.dialog?.show()
            : createModal?.dialog?.close()
        "
        :disabled="productForm?.form.processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="store"
        :disabled="productForm?.form.processing"
      >
        Guardar
      </md-text-button>
    </div>
  </Modal>

  <Modal
    ref="cancelCreateModal"
    type="alert"
  >
    <div slot="headline">Descartar producto nuevo</div>
    <div slot="content">
      La información del producto nuevo aún no ha sido guardada y se descartará
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelCreateModal?.dialog?.close">
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
