<script setup lang="ts">
import CategoryForm from '@/Components/Forms/CategoryForm.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { useModal } from '@/composables/useModal'
import useVuelidate from '@vuelidate/core'
import { ref } from 'vue'

const v = useVuelidate()
const { modal: createCategoryModal } = useModal('#create-category-modal')
const { modal: cancelCreateCategoryModal } = useModal(
  '#cancel-create-category-modal'
)

const createdCategorySnackbar = ref<InstanceType<typeof Snackbar>>()
const categoryForm = ref<InstanceType<typeof CategoryForm>>()

const store = async () => {
  const validate = await v.value.$validate()

  if (!validate) return

  categoryForm.value?.form.post(route('categories.store'), {
    onSuccess: () => {
      categoryForm.value?.form.reset()
      v.value.$reset()

      createCategoryModal.value?.close()
      createdCategorySnackbar.value?.show(true)
    },
  })
}

const cancelStore = () => {
  createCategoryModal.value?.close()
  cancelCreateCategoryModal.value?.close()

  categoryForm.value?.form.reset()
  v.value.$reset()
}

defineExpose({ createCategoryModal })
</script>

<template>
  <Modal
    id="create-category-modal"
    class="w-full"
    not-cancellable
  >
    <div slot="headline">Crear categoría</div>

    <CategoryForm
      slot="content"
      ref="categoryForm"
      :submit="store"
    />

    <div slot="actions">
      <md-text-button @click="cancelCreateCategoryModal?.show"
        >Cancelar</md-text-button
      >
      <md-text-button
        @click="store"
        :disabled="categoryForm?.form.processing"
      >
        Guardar
      </md-text-button>
    </div>
  </Modal>

  <Modal
    id="cancel-create-category-modal"
    type="alert"
  >
    <div slot="headline">Descartar categoría nueva</div>
    <div slot="content">
      El nombre de la nueva dirección aún no ha sido guardada y se descartará,
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelCreateCategoryModal?.close"
        >Cancelar</md-text-button
      >
      <md-text-button @click="cancelStore">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="createdCategorySnackbar"
    text="Categoría creada con éxito."
    close-button
  />
</template>
