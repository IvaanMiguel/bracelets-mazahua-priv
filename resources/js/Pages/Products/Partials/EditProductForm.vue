<script setup lang="ts">
import ProductForm from '@/Components/Forms/ProductForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdProduct } from '@/types/products'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const props = defineProps<{ selectedProduct?: IdProduct }>()

const v = useVuelidate()

const productForm = ref<InstanceType<typeof ProductForm>>()
const editModal = ref<InstanceType<typeof Modal>>()
const cancelEditModal = ref<InstanceType<typeof Modal>>()
const snackbar = ref<InstanceType<typeof Snackbar>>()

const update = async () => {
  const validate = await v.value.$validate()

  if (!validate) return

  productForm.value?.form.put(
    route('products.update', { id: props.selectedProduct?.id }),
    {
      onSuccess: () => {
        productForm.value?.form.clearErrors()
        v.value.$reset()
        editModal.value?.modal?.close()
        snackbar.value?.show(true)
      },
    }
  )
}

const cancelUpdate = () => {
  editModal.value?.modal?.close()
  cancelEditModal.value?.modal?.close()

  productForm.value?.form.reset()
  v.value.$reset()
}

onMounted(() => {
  useEventListener(editModal.value?.modal, 'open', () => {
    if (!productForm.value?.form) return

    productForm.value.form.defaults({ ...props.selectedProduct })
    productForm.value.form.reset()
  })
})

defineExpose({ editModal })
</script>

<template>
  <Modal
    ref="editModal"
    class="w-full"
    not-cancellable
  >
    <div slot="headline">Editar producto</div>

    <ProductForm
      ref="productForm"
      slot="content"
      class="grid grid-cols-2 items-start gap-6"
      :submit="update"
    />

    <div slot="actions">
      <md-text-button @click="cancelEditModal?.modal?.show">
        Cancelar
      </md-text-button>
      <md-text-button
        @click="update"
        :disabled="productForm?.form.processing"
      >
        Guardar cambios
      </md-text-button>
    </div>
  </Modal>

  <Modal
    ref="cancelEditModal"
    type="alert"
  >
    <Icon slot="icon">edit_off</Icon>
    <div slot="headline">Cancelar edición</div>
    <div slot="content">
      Los cambios realizados aún no han sido guardados y se descartarán, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelEditModal?.modal?.close">
        Cancelar
      </md-text-button>
      <md-text-button @click="cancelUpdate">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="snackbar"
    text="Producto modificado correctamente"
    close-button
  />
</template>
