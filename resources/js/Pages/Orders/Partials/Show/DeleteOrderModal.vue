<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import { IdOrder } from '@/types/orders'
import { router, usePage } from '@inertiajs/vue3'
import { MdDialog } from '@material/web/dialog/dialog'
import { onMounted, ref } from 'vue'

const page = usePage()

const _modal = ref<InstanceType<typeof Modal>>()
const modal = ref<MdDialog | null>()
const order = ref(page.props.order as IdOrder)
const processing = ref(false)

const destroy = () => {
  router.delete(route('orders.destroy', { order: order.value }), {
    onStart: () => (processing.value = true),
    onFinish: () => (processing.value = false),
  })
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
    <Icon slot="icon">delete_forever</Icon>
    <div slot="headline">Eliminar pedido</div>
    <div slot="content">
      El pedido será eliminado de forma permanente, esta acción no puede
      deshacerse, ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="destroy">Aceptar</md-text-button>
    </div>
  </Modal>
</template>
