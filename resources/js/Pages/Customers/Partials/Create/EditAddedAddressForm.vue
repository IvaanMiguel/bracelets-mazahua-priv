<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdAddress } from '@/types/customers'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const props = defineProps<{
  addresses: IdAddress[]
  selectedAddress: IdAddress | null
}>()

const v = useVuelidate()
const editModal = ref<InstanceType<typeof Modal>>()
const cancelEditModal = ref<InstanceType<typeof Modal>>()

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
  const validate = await v.value.$validate()

  if (!validate) return

  updateAddress()

  addressForm.value!.form.defaults()
  addressForm.value!.form.reset()
  addressForm.value!.form.clearErrors()

  v.value.$reset()

  editModal.value?.dialog?.close()
  editedAddressSnackbar.value?.show(true)
}

const discardChanges = () => {
  cancelEditModal.value?.dialog?.close()
  editModal.value?.dialog?.close()

  addressForm.value!.form.reset()
  v.value.$reset()
}

onMounted(() => {
  useEventListener(editModal.value?.dialog, 'open', () => {
    if (!addressForm.value?.form) return

    addressForm.value.form.defaults({ ...props.selectedAddress })
    addressForm.value.form.reset()
  })
})

defineExpose({ editModal })
</script>

<template>
  <Modal
    ref="editModal"
    :not-cancellable="addressForm?.form.isDirty"
  >
    <div slot="headline">Editar dirección agregada</div>

    <AddressForm
      ref="addressForm"
      slot="content"
      class="flex flex-col gap-4"
      :submit="saveChanges"
    />

    <div slot="actions">
      <md-text-button
        @click="
          addressForm?.form.isDirty
            ? cancelEditModal?.dialog?.show()
            : editModal?.dialog?.close()
        "
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="
          addressForm?.form.isDirty ? saveChanges() : editModal?.dialog?.close()
        "
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
      <md-text-button @click="discardChanges">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="editedAddressSnackbar"
    text="Dirección modificada correctamente."
    close-button
  />
</template>
