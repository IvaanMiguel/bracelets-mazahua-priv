<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { useModal } from '@/composables/useModal'
import { CustomerWithAddresses, IdAddress } from '@/types/customers'
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{ selectedAddress: IdAddress | null }>()

const page = usePage()
const { modal } = useModal('#delete-address-modal')

const backendSnackbar = ref<InstanceType<typeof Snackbar>>()
const deletedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()
const addresses = computed(() => {
  const customer = page.props.customer as CustomerWithAddresses
  return customer.addresses
})

const destroy = () => {
  if (addresses.value.length <= 1) return

  router.delete(
    route('addresses.destroy', {
      id: props.selectedAddress?.id,
    }),
    {
      onSuccess: () => deletedAddressSnackbar.value?.show(true),
      onError: () => backendSnackbar.value?.show(true),
    }
  )

  modal.value?.close()
}

defineExpose({ modal })
</script>

<template>
  <Modal
    id="delete-address-modal"
    type="alert"
  >
    <Icon slot="icon">remove_road</Icon>
    <div slot="headline">Eliminar dirección</div>
    <div slot="content">
      La dirección seleccionada será eliminada permanentemente. Esta acción no
      puede deshacerse, ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="destroy">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="deletedAddressSnackbar"
    text="La dirección ha sido eliminada con éxito."
    close-button
  />

  <Snackbar
    ref="backendSnackbar"
    :text="$page.props.errors.internal_error"
    close-button
  />
</template>
