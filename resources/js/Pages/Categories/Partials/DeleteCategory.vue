<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdCategory } from '@/types/categories'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ selectedCategory?: IdCategory }>()

const processing = ref(false)
const deleteModal = ref<InstanceType<typeof Modal>>()
const deletedCategorySnackbar = ref<InstanceType<typeof Snackbar>>()

const destroy = () => {
  processing.value = true

  router.delete(
    route('categories.destroy', { id: props.selectedCategory?.id }),
    {
      onSuccess: () => deletedCategorySnackbar.value?.show(true),
      onFinish: () => {
        deleteModal.value?.dialog?.close()
        processing.value = false
      }
    }
  )
}

defineExpose({ deleteModal })
</script>

<template>
  <Modal
    ref="deleteModal"
    class="w-full"
    type="alert"
    :not-cancellable="processing"
  >
    <Icon slot="icon">delete_forever</Icon>
    <div slot="headline">Eliminar categoría</div>
    <div slot="content">
      La categoría seleccionada será eliminada permanentemente, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button
        @click="deleteModal?.dialog?.close"
        :disabled="processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="destroy"
        :disabled="processing"
      >
        Aceptar
      </md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="deletedCategorySnackbar"
    text="La categoría ha sido eliminada con éxito."
    close-button
  />
</template>
