<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { useModal } from '@/composables/useModal'
import { IdAddress } from '@/types/customers'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

const props = defineProps<{
  addresses: IdAddress[]
  selectedAddress: IdAddress | null
}>()

const { modal: editAddressModal } = useModal('#edit-address-modal')
const { modal: cancelEditModal } = useModal('#cancel-edit-modal')

const addressForm = ref<InstanceType<typeof AddressForm>>()
const editedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()

const updateAddress = () => {
  const address = props.addresses.find(
    (address) => address.id === props.selectedAddress?.id
  )

  const form = addressForm.value!.form

  address!.main_street = form.main_street || ''
  address!.cross_streets = form.cross_streets || ''
  address!.neighborhood = form.neighborhood || ''
  address!.postal_code = form.postal_code || ''
  address!.street_number = form.street_number || ''
  address!.suite_number = form.suite_number || ''
}

const saveChanges = async () => {
  const validate = await addressForm.value!.v$.$validate()

  if (validate) {
    updateAddress()

    addressForm.value!.form.reset()
    addressForm.value!.form.clearErrors()
    addressForm.value!.v$.$reset()
    editAddressModal.value?.close()

    editedAddressSnackbar.value?.show(true)
  }
}

const discardChanges = () => {
  cancelEditModal.value?.close()
  editAddressModal.value?.close()

  addressForm.value!.form.reset()
  addressForm.value!.v$.$reset()
}

useEventListener(editAddressModal, 'open', () => {
  const form = addressForm.value!.form

  form.main_street = props.selectedAddress?.main_street || ''
  form.cross_streets = props.selectedAddress?.cross_streets || ''
  form.neighborhood = props.selectedAddress?.neighborhood || ''
  form.postal_code = props.selectedAddress?.postal_code || ''
  form.street_number = props.selectedAddress?.street_number || ''
  form.suite_number = props.selectedAddress?.suite_number || ''
})
</script>

<template>
  <Modal
    id="edit-address-modal"
    not-cancellable
  >
    <div slot="headline">Editar dirección agregada</div>
    <div slot="content">

      <AddressForm
        ref="addressForm"
        class="flex flex-col gap-4"
        :submit="saveChanges"
        :config="{ $stopPropagation: true }"
      />

    </div>
    <div slot="actions">
      <md-text-button @click="cancelEditModal?.show">Cancelar</md-text-button>
      <md-text-button @click="saveChanges">Guardar cambios</md-text-button>
    </div>
  </Modal>

  <Modal
    id="cancel-edit-modal"
    type="alert"
  >
    <Icon slot="icon">edit_off</Icon>
    <div slot="headline">Cancelar edición</div>
    <div slot="content">
      Los cambios realizados aún no han sido guardados y se descartarán, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelEditModal?.close">Cancelar</md-text-button>
      <md-text-button @click="discardChanges">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="editedAddressSnackbar"
    text="Dirección modificada correctamente."
    close-button
  />
</template>
