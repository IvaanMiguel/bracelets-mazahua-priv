<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import TextField from '@/Components/TextField.vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useModal } from '@/composables/useModal'
import { addressRules } from '@/rules/address'
import { Address, IdAddress } from '@/types/customers'
import { useForm } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

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

const editedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()

const updateAddress = () => {
  const address = props.addresses.find(
    (address) => address.id === props.selectedAddress?.id
  )

  address!.main_street = form.main_street || ''
  address!.cross_streets = form.cross_streets || ''
  address!.neighborhood = form.neighborhood || ''
  address!.postal_code = form.postal_code || ''
  address!.street_number = form.street_number || ''
  address!.suite_number = form.suite_number || ''
}

const saveChanges = async () => {
  const validate = await v$.value?.$validate()

  if (validate) {
    updateAddress()

    form.reset()
    form.clearErrors()
    v$.value.$reset()
    editAddressModal.value?.close()

    editedAddressSnackbar.value?.show(true)
  }
}

const discardChanges = () => {
  cancelEditModal.value?.close()
  editAddressModal.value?.close()

  form.reset()
  v$.value.$reset()
}

useEventListener(editAddressModal, 'open', () => {
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
      <div class="flex flex-col gap-4">
        <TextField
          class="w-full flex-1"
          label="Calle principal"
          required
          minlength="3"
          maxlength="255"
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
          minlength="3"
          maxlength="255"
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
          required
          minlength="3"
          maxlength="255"
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
          required
          min="0"
          minlength="5"
          maxlength="5"
          :error="form.errors.postal_code"
          v-model="form.postal_code"
        >
          <template #floating-icon>
            <Icon>local_post_office</Icon>
          </template>
        </TextField>
        <div class="flex w-full flex-1 items-start gap-4">
          <TextField
            class="w-full flex-1"
            label="Número Exterior"
            min="0"
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
            min="0"
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
