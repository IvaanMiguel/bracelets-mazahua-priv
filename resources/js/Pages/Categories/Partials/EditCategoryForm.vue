<script setup lang="ts">
import CategoryForm from '@/Components/Forms/CategoryForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdCategory } from '@/types/categories'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const props = defineProps<{ selectedCategory?: IdCategory }>()

const v = useVuelidate()

const editModal = ref<InstanceType<typeof Modal>>()
const cancelEditModal = ref<InstanceType<typeof Modal>>()
const editedCategorySnackbar = ref<InstanceType<typeof Snackbar>>()
const categoryForm = ref<InstanceType<typeof CategoryForm>>()

const update = async () => {
  const validate = await v.value.$validate()

  if (!validate) return

  categoryForm.value?.form.put(
    route('categories.update', { id: props.selectedCategory?.id }),
    {
      onSuccess: () => {
        categoryForm.value?.form.clearErrors()
        v.value.$reset()
        editModal.value?.dialog?.close()
        editedCategorySnackbar.value?.show(true)
      },
    }
  )
}

const cancelUpdate = () => {
  editModal.value?.dialog?.close()
  cancelEditModal.value?.dialog?.close()

  categoryForm.value?.form.reset()
  v.value.$reset()
}

onMounted(() => {
  useEventListener(editModal.value?.dialog, 'open', () => {
    if (!categoryForm.value?.form) return

    categoryForm.value.form.defaults({ ...props.selectedCategory })
    categoryForm.value.form.reset()
  })
})

defineExpose({ editModal })
</script>

<template>
  <Modal
    ref="editModal"
    class="w-full"
    :not-cancellable="categoryForm?.form.isDirty"
  >
    <div slot="headline">Editar categoría</div>

    <CategoryForm
      slot="content"
      ref="categoryForm"
      :submit="update"
    />

    <div slot="actions">
      <md-text-button
        @click="
          categoryForm?.form.isDirty
            ? cancelEditModal?.dialog?.show()
            : editModal?.dialog?.close()
        "
        :disabled="categoryForm?.form.processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="update"
        :disabled="categoryForm?.form.processing"
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
      <md-text-button @click="cancelEditModal?.dialog?.close"
        >Cancelar</md-text-button
      >
      <md-text-button @click="cancelUpdate">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="editedCategorySnackbar"
    text="Categoría modificada correctamente."
    close-button
  />
</template>
