<script setup lang="ts">
import DatePicker from '@/Components/DatePicker.vue'
import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import TextField from '@/Components/TextField.vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useModal } from '@/composables/useModal'
import { customerRules } from '@/rules/customer'
import { Customer, IdCustomer } from '@/types/customers'
import { useForm, usePage } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { es } from 'date-fns/locale'
import { computed, ref } from 'vue'

const page = usePage()
const { modal: editPersonalInfoModal } = useModal('#edit-personal-info-modal')
const { modal: cancelEditModal } = useModal('#cancel-edit-modal')

const editedCustomerSnackbar = ref<InstanceType<typeof Snackbar>>()
const customer = computed(() => page.props.customer as IdCustomer)

const form = useForm<Customer>({
  name: customer.value.name,
  last_name: customer.value.last_name,
  birth_date: customer.value.birth_date
    ? format(`${customer.value.birth_date}`, 'y/MM/dd', { locale: es })
    : null,
  email: customer.value.email,
  phone_number: customer.value.phone_number,
})
const { v$ } = useFormErrors(customerRules, form)

const discardChanges = () => {
  editPersonalInfoModal.value?.close()
  cancelEditModal.value?.close()

  form.reset()
  v$.value.$reset()
}

const saveChanges = async () => {
  const validate = await v$.value?.$validate()

  if (!validate) return

  form.put(route('customers.update', { id: customer.value.id }), {
    onSuccess: () => {
      form.defaults()
      form.reset()
      form.clearErrors()
      v$.value.$reset()

      editPersonalInfoModal.value?.close()
      editedCustomerSnackbar.value?.show(true)
    },
  })
}

// Just to be able to open it from parent PersonalInfo.
defineExpose({ editPersonalInfoModal })
</script>

<template>
  <Modal
    id="edit-personal-info-modal"
    class="w-full"
    not-cancellable
  >
    <div slot="headline">Editar datos personales</div>
    <Form
      slot="content"
      id="edit-form"
      class="flex flex-col gap-4"
      :submit="saveChanges"
    >
      <TextField
        label="Nombre(s)"
        required
        minlength="1"
        maxlength="100"
        :error="form.errors.name"
        v-model="form.name"
      >
        <template #floating-icon>
          <Icon>person</Icon>
        </template>
      </TextField>
      <TextField
        label="Apellido(s)"
        required
        minlength="1"
        maxlength="100"
        empty-floating-icon
        :error="form.errors.last_name"
        v-model="form.last_name"
      />
      <TextField
        label="Número celular"
        type="tel"
        required
        minlength="10"
        maxlength="10"
        :error="form.errors.phone_number"
        v-model="form.phone_number"
      >
        <template #floating-icon>
          <Icon>phone</Icon>
        </template>
      </TextField>
      <TextField
        label="Email"
        type="email"
        maxlength="255"
        :error="form.errors.email"
        v-model="form.email"
      >
        <template #floating-icon>
          <Icon>alternate_email</Icon>
        </template>
      </TextField>
      <DatePicker
        six-weeks="center"
        v-model="form.birth_date"
        :error="form.errors.birth_date"
      />
    </Form>
    <div slot="actions">
      <md-text-button @click="cancelEditModal?.show">Cancelar</md-text-button>
      <md-text-button
        type="submit"
        form="edit-form"
        :disabled="form.processing"
      >
        Guardar cambios
      </md-text-button>
    </div>
  </Modal>

  <Modal
    id="cancel-edit-modal"
    type="alert"
  >
    <Icon slot="icon">edit_off</Icon>
    <div slot="headline">Cancelar edición</div>
    <div slot="content">
      Los cambios realizados aún no han sido guardados y se descartarán, ¿deseas
      continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelEditModal?.close">Cancelar</md-text-button>
      <md-text-button @click="discardChanges">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="editedCustomerSnackbar"
    text="Cliente modificado correctamente."
    close-button
  />
</template>
