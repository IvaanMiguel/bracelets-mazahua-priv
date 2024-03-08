<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { useModal } from '@/composables/useModal'
import { IdCustomer } from '@/types/customers'
import { usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()
const { modal: addAddressModal } = useModal('#add-address-modal')
const { modal: cancelAddModal } = useModal('#cancel-add-modal')

const addressForm = ref<InstanceType<typeof AddressForm>>()
const storedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()
const customer = computed(() => page.props.customer as IdCustomer)

const store = async () => {
  const validate = await addressForm.value?.v$.$validate()

  if (!validate) return

  addressForm.value?.form
    .transform((data) => ({ ...data, customer_id: customer.value.id }))
    .post(route('addresses.store'), {
      onSuccess: () => {
        addressForm.value?.form.reset()
        addressForm.value?.v$.$reset()

        addAddressModal.value?.close()
        storedAddressSnackbar.value?.show(true)
      },
    })
}

const cancelStore = () => {
  addAddressModal.value?.close()
  cancelAddModal.value?.close()

  addressForm.value?.form.reset()
  addressForm.value?.v$.$reset()
}

defineExpose({ addAddressModal })
</script>

<template>
  <Modal
    id="add-address-modal"
    class="w-full"
    not-cancellable
  >
    <div slot="headline">Crear dirección nueva</div>

    <AddressForm
      ref="addressForm"
      slot="content"
      class="flex flex-col gap-4"
      :submit="store"
    />
    
    <div slot="actions">
      <md-text-button @click="cancelAddModal?.show">Cancelar</md-text-button>
      <md-text-button
        @click="store"
        :disabled="addressForm?.form.processing"
      >
        Guardar
      </md-text-button>
    </div>
  </Modal>

  <Modal
    id="cancel-add-modal"
    type="alert"
  >
    <Icon slot="icon">edit_off</Icon>
    <div slot="headline">Descartar dirección nueva</div>
    <div slot="content">
      La información de la dirección aún no ha sido guardada y se descartará,
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelAddModal?.close">Cancelar</md-text-button>
      <md-text-button @click="cancelStore">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="storedAddressSnackbar"
    text="Dirección nueva guardada con éxito."
    close-button
  />
</template>
