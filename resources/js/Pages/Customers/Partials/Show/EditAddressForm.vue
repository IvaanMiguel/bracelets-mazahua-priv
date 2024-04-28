<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdAddress } from '@/types/customers'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { onMounted, ref } from 'vue'

const props = defineProps<{ selectedAddress?: IdAddress }>()

const v = useVuelidate()

const addressForm = ref<InstanceType<typeof AddressForm>>()
const updatedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()
const editModal = ref<InstanceType<typeof Modal>>()
const cancelEditModal = ref<InstanceType<typeof Modal>>()

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
        editModal.value?.dialog?.close()
      },
    }
  )
}

const cancelUpdate = () => {
  editModal.value?.dialog?.close()
  cancelEditModal.value?.dialog?.close()

  addressForm.value?.form.reset()
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
    <div slot="headline">Editar dirección</div>

    <AddressForm
      ref="addressForm"
      slot="content"
      class="flex flex-col gap-4"
      :submit="update"
    />

    <div slot="actions">
      <md-text-button
        @click="
          addressForm?.form.isDirty
            ? cancelEditModal?.dialog?.show()
            : editModal?.dialog?.close()
        "
        :disabled="addressForm?.form.processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="update"
        :disabled="addressForm?.form.processing"
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
      <md-text-button @click="cancelEditModal?.dialog?.close">
        Cancelar
      </md-text-button>
      <md-text-button @click="cancelUpdate">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="updatedAddressSnackbar"
    text="Dirección modificada correctamente."
    close-button
  />
</template>
