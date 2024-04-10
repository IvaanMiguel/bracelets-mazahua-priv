<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdOrder } from '@/types/orders'
import { router, usePage } from '@inertiajs/vue3'
import { MdDialog } from '@material/web/dialog/dialog'
import { onMounted, ref } from 'vue'

const page = usePage()

const completedOrderSnackbar = ref<InstanceType<typeof Snackbar>>()
const _modal = ref<InstanceType<typeof Modal>>()
const modal = ref<MdDialog | null>()
const processing = ref(false)
const order = ref(page.props.order as IdOrder)

const complete = () => {
  router.post(
    route('orders.complete', { order: order.value }),
    {},
    {
      onStart: () => (processing.value = true),
      onSuccess: () => {
        modal.value?.close()
        completedOrderSnackbar.value?.show(true)
      },
      onFinish: () => (processing.value = false),
    }
  )
}

onMounted(() => {
  modal.value = _modal.value?.dialog
})

defineExpose({ modal, processing })
</script>

<template>
  <Modal
    :not-cancellable="processing"
    ref="_modal"
    type="alert"
  >
    <Icon slot="icon">fact_check</Icon>
    <div slot="headline">Completar pedido</div>
    <div slot="content">
      Una vez completado el pedido, no podrá ser editado o eliminado, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="complete">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="completedOrderSnackbar"
    text="Pedido completado correctamente."
    close-button
  />
</template>
