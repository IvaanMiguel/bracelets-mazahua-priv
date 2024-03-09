<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { useModal } from '@/composables/useModal'
import { IdAddress } from '@/types/customers'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

const props = defineProps<{ selectedAddress?: IdAddress }>()

const v = useVuelidate()
const { modal: editAddressModal } = useModal('#edit-address-modal')
const { modal: cancelEditModal } = useModal('#cancel-edit-address-modal')

const addressForm = ref<InstanceType<typeof AddressForm>>()
const updatedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()

const update = () => {
  const validate = v.value.$validate()

  if (!validate) return

  addressForm.value?.form.put(
    route('addresses.update', { id: props.selectedAddress?.id }),
    {
      onSuccess: () => {
        addressForm.value?.form.defaults()
        addressForm.value?.form.reset()
        addressForm.value?.form.clearErrors()

        v.value.$reset()

        updatedAddressSnackbar.value?.show(true)
        editAddressModal.value?.close()
      },
    }
  )
}

const cancelUpdate = () => {
  editAddressModal.value?.close()
  cancelEditModal.value?.close()

  addressForm.value?.form.reset()
  v.value.$reset()
}

useEventListener(editAddressModal, 'open', () => {
  if (!addressForm.value?.form) return

  addressForm.value.form.defaults({ ...props.selectedAddress })
  addressForm.value.form.reset()
})

defineExpose({ editAddressModal })
</script>

<template>
  <Modal
    id="edit-address-modal"
    not-cancellable
  >
    <div slot="headline">Editar dirección</div>

    <AddressForm
      ref="addressForm"
      slot="content"
      class="flex flex-col gap-4"
      :submit="update"
    />

    <div slot="actions">
      <md-text-button @click="cancelEditModal?.show">Cancelar</md-text-button>
      <md-text-button
        @click="update"
        :disabled="addressForm?.form.processing"
      >
        Guardar cambios
      </md-text-button>
    </div>
  </Modal>

  <Modal
    id="cancel-edit-address-modal"
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
      <md-text-button @click="cancelUpdate">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="updatedAddressSnackbar"
    text="Dirección modificada correctamente."
    close-button
  />
</template>
