<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdAddress } from '@/types/customers'
import { ref } from 'vue'

const props = defineProps<{
  addresses: IdAddress[]
  selectedAddress: IdAddress | null
}>()

const emit = defineEmits<{
  (e: 'unselectAddress'): void
}>()

const removeModal = ref<InstanceType<typeof Modal>>()
const removedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()

const remove = () => {
  const i = props.addresses.findIndex(
    (address) => address.id === props.selectedAddress?.id
  )

  emit('unselectAddress')

  props.addresses.splice(i, 1)
  removeModal.value?.dialog?.close()
  removedAddressSnackbar.value?.show(true)
}

defineExpose({ removeModal })
</script>

<template>
  <Modal ref="removeModal">
    <Icon slot="icon">wrong_location</Icon>
    <div slot="headline">Remover dirección</div>
    <div slot="content">
      La dirección seleccionada será removida, ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="removeModal?.dialog?.close">
        Cancelar
      </md-text-button>
      <md-text-button @click="remove">Aceptar</md-text-button>
    </div>
  </Modal>
  <Snackbar
    ref="removedAddressSnackbar"
    text="Dirección removida correctamente."
    close-button
  />
</template>
