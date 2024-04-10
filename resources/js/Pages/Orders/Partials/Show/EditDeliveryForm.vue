<script setup lang="ts">
import DeliveryForm from '@/Components/Forms/Delivery/DeliveryForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { Catalog, IdOrder } from '@/types/orders'
import { usePage } from '@inertiajs/vue3'
import { MdDialog } from '@material/web/dialog/dialog'
import { useEventListener } from '@vueuse/core'
import { computed, onMounted, ref } from 'vue'

const page = usePage()

const _modal = ref<InstanceType<typeof Modal>>()
const modal = ref<MdDialog | null>()
const cancelModal = ref<InstanceType<typeof Modal>>()
const cancellingModal = ref<MdDialog | null>()
const deliveryForm = ref<InstanceType<typeof DeliveryForm>>()
const editedDeliverySnackbar = ref<InstanceType<typeof Snackbar>>()
const isDirty = computed(() => deliveryForm.value?.form.isDirty)
const order = ref(page.props.order as IdOrder)
const deliveryTypes = ref(page.props.deliveryTypes as Catalog[])
const deliveryApps = ref(page.props.deliveryApps as Catalog[])

const defaults = {
  delivery_type_id: order.value.delivery.delivery_type_id,
  delivery_app_id: order.value.delivery.delivery_app_id,
  address_id: order.value.delivery.address_id,
  date: order.value.delivery.date,
  time: order.value.delivery.time,
}

const update = () => {
  if (!isDirty.value) {
    modal.value?.close()
    return
  }

  deliveryForm.value?.form.put(
    route('orders.update_delivery', { order: order.value }),
    {
      onSuccess: () => {
        deliveryForm.value?.form.defaults()
        deliveryForm.value?.form.reset()
        deliveryForm.value?.form.clearErrors()

        modal.value?.close()
        editedDeliverySnackbar.value?.show(true)
      },
    }
  )
}

const cancelUpdate = () => {
  modal.value?.close()
  cancellingModal.value?.close()
  deliveryForm.value?.form.reset()
}

useEventListener(modal, 'open', () => {
  const div = modal.value?.shadowRoot?.querySelector(
    '.content'
  ) as HTMLDivElement

  div.style.height = '100%'
})

onMounted(() => {
  modal.value = _modal.value?.dialog
  cancellingModal.value = cancelModal.value?.dialog
})

defineExpose({ modal })
</script>

<template>
  <Modal
    ref="_modal"
    class="h-[90%] w-full max-w-[90%]"
    :not-cancellable="isDirty || deliveryForm?.form.processing"
  >
    <div slot="headline">Editar información de entrega</div>
    <div
      slot="content"
      class="flex h-full flex-col overflow-y-auto"
    >
      <DeliveryForm
        ref="deliveryForm"
        class="h-full"
        :defaults
        :delivery-types="deliveryTypes"
        :delivery-apps="deliveryApps"
        :addresses="order.customer.addresses"
      />
    </div>
    <div slot="actions">
      <md-text-button
        :disabled="deliveryForm?.form.processing"
        @click="isDirty ? cancellingModal?.show() : modal?.close()"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        :disabled="deliveryForm?.form.processing"
        @click="update"
      >
        Guardar cambios
      </md-text-button>
    </div>
  </Modal>

  <Modal
    ref="cancelModal"
    type="alert"
  >
    <Icon slot="icon">edit_off</Icon>
    <div slot="headline">Cancelar edición</div>
    <div slot="content">
      Los cambios realizados aún no han sido guardados y se descartarán, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancellingModal?.close">Cancelar</md-text-button>
      <md-text-button @click="cancelUpdate">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="editedDeliverySnackbar"
    text="Información de entrega modificada correctamente."
    close-button
  />
</template>
