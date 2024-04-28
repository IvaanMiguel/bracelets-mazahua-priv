<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { CustomerWithAddresses, IdAddress } from '@/types/customers'
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{ selectedAddress?: IdAddress }>()

const page = usePage()

const deleteModal = ref<InstanceType<typeof Modal>>()
const deletedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()
const processing = ref(false)
const addresses = computed(() => {
  const customer = page.props.customer as CustomerWithAddresses
  return customer.addresses
})

const destroy = () => {
  if (addresses.value.length <= 1) return

  processing.value = true

  router.delete(
    route('addresses.destroy', {
      id: props.selectedAddress?.id,
    }),
    {
      onSuccess: () => deletedAddressSnackbar.value?.show(true),
      onFinish: () => {
        deleteModal.value?.dialog?.close()
        processing.value = false
      },
    }
  )
}

defineExpose({ deleteModal })
</script>

<template>
  <Modal
    ref="deleteModal"
    type="alert"
    :not-cancellable="processing"
  >
    <Icon slot="icon">remove_road</Icon>
    <div slot="headline">Eliminar dirección</div>
    <div slot="content">
      La dirección seleccionada será eliminada permanentemente. Esta acción no
      puede deshacerse, ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button
        @click="deleteModal?.dialog?.close"
        :disabled="processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="destroy"
        :disabled="processing"
      >
        Aceptar
      </md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="deletedAddressSnackbar"
    text="La dirección ha sido eliminada con éxito."
    close-button
  />
</template>
