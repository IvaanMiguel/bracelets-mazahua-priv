<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { useModal } from '@/composables/useModal'
import { IdCategory } from '@/types/categories'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ selectedCategory?: IdCategory }>()

const { modal } = useModal('#delete-category-modal')

const deletedCategorySnackbar = ref<InstanceType<typeof Snackbar>>()

const destroy = () => {
  console.log(props.selectedCategory?.id)

  router.delete(
    route('categories.destroy', { id: props.selectedCategory?.id }),
    {
      onSuccess: () => {
        deletedCategorySnackbar.value?.show(true)
      },
      onFinish: () => modal.value?.close()
    }
  )
}

defineExpose({ modal })
</script>

<template>
  <Modal
    id="delete-category-modal"
    class="w-full"
    type="alert"
  >
    <Icon slot="icon">delete_forever</Icon>
    <div slot="headline">Eliminar categoría</div>
    <div slot="content">
      La categoría seleccionada será eliminada permanentemente, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="destroy">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="deletedCategorySnackbar"
    text="La categoría ha sido eliminada con éxito."
    close-button
  />
</template>
