<script setup lang="ts">
import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Snackbar from '@/Components/Snackbar.vue'
import TextField from '@/Components/TextField.vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { useModal } from '@/composables/useModal'
import { addressRules } from '@/rules/address'
import { Address, IdCustomer } from '@/types/customers'
import { useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()
const { modal: addAddressModal } = useModal('#add-address-modal')
const { modal: cancelAddModal } = useModal('#cancel-add-modal')
const form = useForm<Address>({
  main_street: '',
  cross_streets: '',
  neighborhood: '',
  postal_code: '',
  street_number: '',
  suite_number: '',
})
const { v$ } = useFormErrors(addressRules, form)

const storedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()
const customer = computed(() => page.props.customer as IdCustomer)

const store = async () => {
  const validate = await v$.value.$validate()

  if (!validate) return

  form
    .transform((data) => ({ ...data, customer_id: customer.value.id }))
    .post(route('addresses.store'), {
      onSuccess: () => {
        form.reset()
        v$.value.$reset()

        addAddressModal.value?.close()
        storedAddressSnackbar.value?.show(true)
      },
    })
}

const discard = () => {
  addAddressModal.value?.close()
  cancelAddModal.value?.close()

  form.reset()
  v$.value.$reset()
}

defineExpose({ addAddressModal })
</script>

<template>
  <Modal
    id="add-address-modal"
    class="w-full"
    not-cancellable
  >
    <div slot="headline">Crear dirección nueva</div>
    <Form
      slot="content"
      id="create-form"
      class="flex flex-col gap-4"
      :submit="store"
    >
      <TextField
        label="Calle principal"
        required
        minlength="3"
        maxlength="255"
        :error="form.errors.main_street"
        v-model="form.main_street"
      >
        <template #floating-icon>
          <Icon>location_home</Icon>
        </template>
      </TextField>
      <TextField
        label="Calles adyacentes"
        minlength="3"
        maxlength="255"
        :error="form.errors.cross_streets"
        v-model="form.cross_streets"
      >
        <template #floating-icon>
          <Icon>alt_route</Icon>
        </template>
      </TextField>
      <TextField
        label="Colonia"
        required
        minlength="3"
        maxlength="255"
        :error="form.errors.neighborhood"
        v-model="form.neighborhood"
      >
        <template #floating-icon>
          <Icon>warehouse</Icon>
        </template>
      </TextField>
      <TextField
        label="Código postal"
        required
        min="0"
        minlength="5"
        maxlength="5"
        :error="form.errors.postal_code"
        v-model="form.postal_code"
      >
        <template #floating-icon>
          <Icon>local_post_office</Icon>
        </template>
      </TextField>
      <div class="flex items-start gap-4">
        <TextField
          class="flex-1"
          label="Número Exterior"
          min="0"
          :error="form.errors.street_number"
          v-model="form.street_number"
        >
          <template #floating-icon>
            <Icon>house</Icon>
          </template>
        </TextField>
        <TextField
          class="flex-1"
          label="Número interior"
          min="0"
          :error="form.errors.suite_number"
          v-model="form.suite_number"
        >
          <template #floating-icon>
            <Icon>apartment</Icon>
          </template>
        </TextField>
      </div>
    </Form>
    <div slot="actions">
      <md-text-button @click="cancelAddModal?.show">Cancelar</md-text-button>
      <md-text-button
        @click="store"
        :disabled="form.processing"
      >
        Guardar
      </md-text-button>
    </div>
  </Modal>

  <Modal
    id="cancel-add-modal"
    type="alert"
  >
    <Icon slot="icon">edit_off</Icon>
    <div slot="headline">Descartar dirección nueva</div>
    <div slot="content">
      La información de la dirección aún no ha sido guardada y se descartará,
      ¿deseas continuar?
    </div>
    <div slot="actions">
      <md-text-button @click="cancelAddModal?.close">Cancelar</md-text-button>
      <md-text-button @click="discard">Aceptar</md-text-button>
    </div>
  </Modal>

  <Snackbar
    ref="storedAddressSnackbar"
    text="Dirección nueva guardada con éxito."
    close-button
  />
</template>
