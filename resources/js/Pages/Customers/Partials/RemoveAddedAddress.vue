<script setup lang="ts">
import { ref } from 'vue'
import Snackbar from '@/Components/Snackbar.vue';
import { useModal } from '@/composables/useModal'
import { Address } from '@/types/customers'
import Modal from '@/Components/Modal.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps<{
  addresses: (Address & { id: number })[]
  selectedAddress: number | null
}>()

const emit = defineEmits<{
  (e: 'unselectAddress'): void
}>()

const { modal } = useModal('#remove-address-modal')

const removedAddressSnackbar = ref<typeof Snackbar>()

const removeAddress = () => {
  const i = props.addresses.findIndex(
    (address) => address.id === props.selectedAddress
  )

  emit('unselectAddress')

  props.addresses.splice(i, 1)
  modal.value?.close()
  removedAddressSnackbar.value?.show(true)
}
</script>

<template>
  <Modal id="remove-address-modal">
    <Icon slot="icon">wrong_location</Icon>
    <div slot="headline">Remover dirección</div>
    <div slot="content">
      La dirección seleccionada será removida, ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">
        Cancelar
      </md-text-button>
      <md-text-button @click="removeAddress">Aceptar</md-text-button>
    </div>
  </Modal>
  <Snackbar
    ref="removedAddressSnackbar"
    close-button
  >
    <template #content>Dirección removida correctamente.</template>
  </Snackbar>
</template>
