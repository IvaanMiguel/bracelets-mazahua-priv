<script setup lang="ts">
import { Address, IdAddress } from '@/types/customers'

import { useEventListener } from '@vueuse/core'
import { useForm } from '@inertiajs/vue3'
import { addressRules } from '@/rules/address'

import { useFormErrors } from '@/composables/useFormErrors'
import { useModal } from '@/composables/useModal'

import { ref } from 'vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import TextField from '@/Components/TextField.vue'
import Snackbar from '@/Components/Snackbar.vue'

const props = defineProps<{
  addresses: IdAddress[]
  selectedAddress: IdAddress | null
}>()

const form = useForm<Address>({
  main_street: '',
  cross_streets: '',
  neighborhood: '',
  postal_code: '',
  street_number: '',
  suite_number: '',
})
const { v$ } = useFormErrors(addressRules, form, { $stopPropagation: true })
const { modal: editAddressModal } = useModal('#edit-address-modal')
const { modal: cancelEditModal } = useModal('#cancel-edit-modal')

// Flag to know when changes are discarded.
const cancelledEdit = ref(false)
// Flag to know when to not refresh modal form.
const recoverForm = ref(false)
// Flag to know when changes are saved.
const savedChanges = ref(false)
const editedAddressSnackbar = ref<typeof Snackbar>()

const updateAddress = () => {
  const address = props.addresses.find(
    (address) => address.id === props.selectedAddress?.id
  )

  address!.main_street = form.main_street || address!.main_street
  address!.cross_streets = form.cross_streets || address!.cross_streets
  address!.neighborhood = form.neighborhood || address!.neighborhood
  address!.postal_code = form.postal_code || address!.postal_code
  address!.street_number = form.street_number || address!.street_number
  address!.suite_number = form.suite_number || address!.suite_number
}

const saveEdit = async () => {
  const validate = await v$.value?.$validate()

  if (validate) {
    updateAddress()

    form.reset()
    form.clearErrors()
    v$.value.$reset()
    editAddressModal.value?.close()

    savedChanges.value = true

    editedAddressSnackbar.value?.show(true)
  }
}

const notCancelEdit = () => {
  cancelEditModal.value?.close()
  recoverForm.value = true
}

const cancelEdit = () => {
  cancelledEdit.value = true
  cancelEditModal.value?.close()
}

useEventListener(editAddressModal, 'open', () => {
  if (recoverForm.value) return

  form.main_street = props.selectedAddress?.main_street || ''
  form.cross_streets = props.selectedAddress?.cross_streets || ''
  form.neighborhood = props.selectedAddress?.neighborhood || ''
  form.postal_code = props.selectedAddress?.postal_code || ''
  form.street_number = props.selectedAddress?.street_number || ''
  form.suite_number = props.selectedAddress?.suite_number || ''
})

useEventListener(editAddressModal, 'close', () => {
  if (!savedChanges.value) {
    cancelEditModal.value?.show()
  } else {
    savedChanges.value = false
  }
  recoverForm.value = false
})

useEventListener(cancelEditModal, 'close', () => {
  if (!cancelledEdit.value) {
    editAddressModal.value?.show()
  }
})

useEventListener(cancelEditModal, 'closed', () => {
  cancelledEdit.value = false
})
</script>

<template>
  <Modal
    id="edit-address-modal"
    not-cancellable
  >
    <div slot="headline">Editar dirección agregada</div>
    <div slot="content">
      <div class="mb-6 flex flex-col gap-6">
        <TextField
          class="w-full flex-1"
          label="Calle principal"
          :error="form.errors.main_street"
          v-model="form.main_street"
        >
          <template #floating-icon>
            <Icon>location_home</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-1"
          label="Calles adyacentes"
          :error="form.errors.cross_streets"
          v-model="form.cross_streets"
        >
          <template #floating-icon>
            <Icon>alt_route</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-[3]"
          label="Colonia"
          :error="form.errors.neighborhood"
          v-model="form.neighborhood"
        >
          <template #floating-icon>
            <Icon>warehouse</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-[2]"
          label="Código postal"
          :error="form.errors.postal_code"
          v-model="form.postal_code"
        >
          <template #floating-icon>
            <Icon>local_post_office</Icon>
          </template>
        </TextField>
        <div class="flex w-full flex-1 items-start gap-6">
          <TextField
            class="w-full flex-1"
            label="Número Exterior"
            :error="form.errors.street_number"
            v-model="form.street_number"
          >
            <template #floating-icon>
              <Icon>house</Icon>
            </template>
          </TextField>
          <TextField
            class="w-full flex-1"
            label="Número interior"
            :error="form.errors.suite_number"
            v-model="form.suite_number"
          >
            <template #floating-icon>
              <Icon>apartment</Icon>
            </template>
          </TextField>
        </div>
      </div>
    </div>
    <div slot="actions">
      <md-text-button @click="editAddressModal?.close">Cancelar</md-text-button>
      <md-text-button @click="saveEdit">Guardar cambios</md-text-button>
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
      <md-text-button @click="notCancelEdit">Cancelar</md-text-button>
      <md-text-button @click="cancelEdit">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="editedAddressSnackbar"
    close-button
  >
    <template #content>Dirección modificada correctamente.</template>
  </Snackbar>
</template>
