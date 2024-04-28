<script setup lang="ts">
import CustomerForm from '@/Components/Forms/CustomerForm.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdCustomer } from '@/types/customers'
import { usePage } from '@inertiajs/vue3'
import useVuelidate from '@vuelidate/core'
import { format } from 'date-fns'
import { es } from 'date-fns/locale'
import { computed, ref } from 'vue'

const v = useVuelidate()
const page = usePage()

const editModal = ref<InstanceType<typeof Modal>>()
const cancelEditModal = ref<InstanceType<typeof Modal>>()
const customerForm = ref<InstanceType<typeof CustomerForm>>()
const editedCustomerSnackbar = ref<InstanceType<typeof Snackbar>>()
const customer = computed(() => page.props.customer as IdCustomer)

const formDefaults = {
  name: customer.value.name,
  last_name: customer.value.last_name,
  birth_date: customer.value.birth_date
    ? format(`${customer.value.birth_date}`, 'y/MM/dd', { locale: es })
    : null,
  email: customer.value.email,
  phone_number: customer.value.phone_number,
}

const update = async () => {
  const validate = await v.value?.$validate()

  if (!validate) return

  customerForm.value?.form.put(
    route('customers.update', { id: customer.value.id }),
    {
      onSuccess: () => {
        customerForm.value?.form.defaults()
        customerForm.value?.form.reset()
        customerForm.value?.form.clearErrors()
        v.value.$reset()

        editModal.value?.dialog?.close()
        editedCustomerSnackbar.value?.show(true)
      },
    }
  )
}

const cancelUpdate = () => {
  editModal.value?.dialog?.close()
  cancelEditModal.value?.dialog?.close()

  customerForm.value?.form.reset()
  v.value.$reset()
}

defineExpose({ editModal })
</script>

<template>
  <Modal
    ref="editModal"
    class="w-full"
    :not-cancellable="customerForm?.form.isDirty"
  >
    <div slot="headline">Editar datos personales</div>

    <CustomerForm
      slot="content"
      ref="customerForm"
      class="flex flex-col gap-4"
      :submit="update"
      :defaults="formDefaults"
      :date-picker-config="{ 'six-weeks': 'center' }"
    />

    <div slot="actions">
      <md-text-button
        @click="
          customerForm?.form.isDirty
            ? cancelEditModal?.dialog?.show()
            : editModal?.dialog?.close()
        "
        :disabled="customerForm?.form.processing"
      >
        Cancelar
      </md-text-button>
      <md-text-button
        @click="
          customerForm?.form.isDirty ? update() : editModal?.dialog?.close()
        "
        :disabled="customerForm?.form.processing"
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
    ref="editedCustomerSnackbar"
    text="Cliente modificado correctamente."
    close-button
  />
</template>
