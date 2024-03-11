<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import { useModal } from '@/composables/useModal'
import { IdCustomer } from '@/types/customers'
import { router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const { modal } = useModal('#delete-customer-modal')

const customer = computed(() => page.props.customer as IdCustomer)

const destroy = () => {
  router.delete(route('customers.destroy', { id: customer.value.id }))
}

defineExpose({ modal })
</script>

<template>
  <Modal
    id="delete-customer-modal"
    type="alert"
  >
    <Icon slot="icon">delete_forever</Icon>
    <div slot="headline">Eliminar cliente</div>
    <div slot="content">
      La información personal del cliente y sus direcciones relacionadas serán
      eliminadas permanentemente. Esta acción no puede deshacerse, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="destroy">Aceptar</md-text-button>
    </div>
  </Modal>
</template>
