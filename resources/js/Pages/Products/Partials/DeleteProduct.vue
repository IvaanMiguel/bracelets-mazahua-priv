<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue';
import { IdProduct } from '@/types/products';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue'

const props = defineProps<{ selectedProduct?: IdProduct }>()

const deleteModal = ref<InstanceType<typeof Modal>>()
const snackbar = ref<InstanceType<typeof Snackbar>>()

const destroy = () => {
  router.delete(route('products.destroy', { id: props.selectedProduct?.id }), {
    onSuccess: () => {
      snackbar.value?.show(true)
    },
    onFinish: () => deleteModal.value?.dialog?.close()
  })
}

defineExpose({ deleteModal })
</script>

<template>
  <Modal
    ref="deleteModal"
    type="alert"
  >
    <Icon slot="icon">delete_forever</Icon>
    <div slot="headline">Eliminar producto</div>
    <div slot="content">
      La información del producto seleccionado será eliminada permanentemente,
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="deleteModal?.dialog?.close">Cancelar</md-text-button>
      <md-text-button @click="destroy">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="snackbar"
    text="El producto ha sido eliminado con éxito."
    close-button
  />
</template>
