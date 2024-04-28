<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdCustomer } from '@/types/customers'
import { usePage } from '@inertiajs/vue3'
import useVuelidate from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { computed, onMounted, ref } from 'vue'

const v = useVuelidate()
const page = usePage()
const createModal = ref<InstanceType<typeof Modal>>()
const cancelCreateModal = ref<InstanceType<typeof Modal>>()

const addressForm = ref<InstanceType<typeof AddressForm>>()
const storedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()
const customer = computed(() => page.props.customer as IdCustomer)

const store = async () => {
  const validate = await v.value.$validate()

  if (!validate) return

  addressForm.value?.form
    .transform((data) => ({ ...data, customer_id: customer.value.id }))
    .post(route('addresses.store'), {
      onSuccess: () => {
        addressForm.value?.form.reset()
        v.value.$reset()

        createModal.value?.dialog?.close()
        storedAddressSnackbar.value?.show(true)
      },
    })
}

const cancelStore = () => {
  createModal.value?.dialog?.close()
  cancelCreateModal.value?.dialog?.close()
}

onMounted(() => {
  useEventListener(createModal.value?.dialog, 'closed', () => {
    addressForm.value?.form.reset()
    v.value.$reset()
  })
})

defineExpose({ createModal })
</script>

<template>
  <Modal
    ref="createModal"
    class="w-full"
    :not-cancellable="addressForm?.form.isDirty"
  >
    <div slot="headline">Crear dirección nueva</div>

    <AddressForm
      ref="addressForm"
      slot="content"
      class="flex flex-col gap-4"
      :submit="store"
    />

    <div slot="actions">
      <md-text-button
        @click="
          addressForm?.form.isDirty
            ? cancelCreateModal?.dialog?.show()
            : createModal?.dialog?.close()
        "
        :disabled="addressForm?.form.processing"
      >
        Cancelar
      </md-text-button>
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
    ref="cancelCreateModal"
    type="alert"
  >
    <Icon slot="icon">edit_off</Icon>
    <div slot="headline">Descartar dirección nueva</div>
    <div slot="content">
      La información de la dirección aún no ha sido guardada y se descartará,
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelCreateModal?.dialog?.close">
        Cancelar
      </md-text-button>
      <md-text-button @click="cancelStore">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="storedAddressSnackbar"
    text="Dirección nueva guardada con éxito."
    close-button
  />
</template>
