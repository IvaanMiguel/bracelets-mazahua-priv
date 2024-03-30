<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import { SelectedProduct } from '@/types/orders'
import { MdDialog } from '@material/web/dialog/dialog'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const emit = defineEmits<{ removeProduct: [] }>()
const props = defineProps<{ productToRemove: SelectedProduct | null }>()

const _modal = ref<InstanceType<typeof Modal>>()
const modal = ref<MdDialog | null>()
const removeText = ref('')

const onAccept = () => {
  emit('removeProduct')
  modal.value?.close()
}

useEventListener(modal, 'open', () => {
  removeText.value = `${props.productToRemove?.amount} × ${props.productToRemove?.name}`
})

onMounted(() => {
  modal.value = _modal.value?.modal
})

defineExpose({ modal })
</script>

<template>
  <Modal ref="_modal">
    <Icon slot="icon">remove_shopping_cart</Icon>
    <div slot="headline">Remover producto del pedido</div>
    <div slot="content">
      <strong>{{ removeText }}</strong>
      será removido del pedido, ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="onAccept">Aceptar</md-text-button>
    </div>
  </Modal>
</template>
