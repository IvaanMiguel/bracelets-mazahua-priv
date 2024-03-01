<script setup lang="ts">
import { Address, IdAddress } from '@/types/customers'

import { useForm } from '@inertiajs/vue3'
import { editAddressRules } from '@/rules/address'

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

const { v$ } = useFormErrors(editAddressRules, form)
const { modal } = useModal('#edit-address-modal')

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
    modal.value?.close()

    editedAddressSnackbar.value?.show(true)
  }
}

const cancelEdit = () => {

}
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
          :placeholder="selectedAddress?.main_street"
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
          :placeholder="selectedAddress?.cross_streets"
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
          :placeholder="selectedAddress?.neighborhood"
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
          :placeholder="selectedAddress?.postal_code"
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
            :placeholder="selectedAddress?.street_number"
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
            :placeholder="selectedAddress?.suite_number"
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
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="saveEdit">Guardar cambios</md-text-button>
    </div>
  </Modal>

  <Modal type="alert">
    <md-elevation />
    <div slot="headline">Cancelar edición</div>
    <div slot="content">Los cambios realizados aún no han sido guardados y se descartarán, ¿deseas continuar?</div>
    <div slot="actions">
      <md-text-button @click="">Cancelar</md-text-button>
      <md-text-button @click="">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar ref="editedAddressSnackbar" close-button>
    <template #content>Dirección modificada correctamente.</template>
  </Snackbar>
</template>
