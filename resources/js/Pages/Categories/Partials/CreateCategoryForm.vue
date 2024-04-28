<script setup lang="ts">
import CategoryForm from '@/Components/Forms/CategoryForm.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const v = useVuelidate()

const createModal = ref<InstanceType<typeof Modal>>()
const cancelCreateModal = ref<InstanceType<typeof Modal>>()
const createdCategorySnackbar = ref<InstanceType<typeof Snackbar>>()
const categoryForm = ref<InstanceType<typeof CategoryForm>>()

const store = async () => {
  const validate = await v.value.$validate()

  if (!validate) return

  categoryForm.value?.form.post(route('categories.store'), {
    onSuccess: () => {
      categoryForm.value?.form.reset()
      v.value.$reset()

      createModal.value?.dialog?.close()
      createdCategorySnackbar.value?.show(true)
    },
  })
}

const cancelStore = () => {
  createModal.value?.dialog?.close()
  cancelCreateModal.value?.dialog?.close()
}

onMounted(() => {
  useEventListener(createModal.value?.dialog, 'closed', () => {
    categoryForm.value?.form.reset()
    v.value.$reset()
  })
})

defineExpose({ createModal })
</script>

<template>
  <Modal
    ref="createModal"
    class="w-full"
    :not-cancellable="categoryForm?.form.isDirty"
  >
    <div slot="headline">Crear categoría</div>

    <CategoryForm
      slot="content"
      ref="categoryForm"
      :submit="store"
    />

    <div slot="actions">
      <md-text-button
        @click="
          categoryForm?.form.isDirty
            ? cancelCreateModal?.dialog?.show()
            : createModal?.dialog?.close()
        "
        :disabled="categoryForm?.form.processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="store"
        :disabled="categoryForm?.form.processing"
      >
        Guardar
      </md-text-button>
    </div>
  </Modal>

  <Modal
    ref="cancelCreateModal"
    type="alert"
  >
    <div slot="headline">Descartar categoría nueva</div>
    <div slot="content">
      El nombre de la nueva dirección aún no ha sido guardada y se descartará,
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelCreateModal?.dialog?.close"
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
