<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import { IdCustomer } from '@/types/customers'
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()

const processing = ref(false)
const deleteModal = ref<InstanceType<typeof Modal>>()
const customer = computed(() => page.props.customer as IdCustomer)

const destroy = () => {
  processing.value = true

  router.delete(route('customers.destroy', { id: customer.value.id }), {
    onFinish: () => (processing.value = false),
  })
}

defineExpose({ deleteModal })
</script>

<template>
  <Modal
    ref="deleteModal"
    type="alert"
    :not-cancellable="processing"
  >
    <Icon slot="icon">delete_forever</Icon>
    <div slot="headline">Eliminar cliente</div>
    <div slot="content">
      La información personal del cliente y sus direcciones relacionadas serán
      eliminadas permanentemente. Esta acción no puede deshacerse, ¿deseas
      continuar?
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
</template>
