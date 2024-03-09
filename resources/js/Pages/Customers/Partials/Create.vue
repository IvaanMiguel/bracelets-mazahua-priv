<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import CustomerForm from '@/Components/Forms/CustomerForm.vue'
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { router } from '@inertiajs/vue3'
import useVuelidate, { Validation } from '@vuelidate/core'
import { ref } from 'vue'
import AddedAddressesList from './Create/AddedAddressesList.vue'

const v = useVuelidate()

const processing = ref(false)
const customerForm = ref<InstanceType<typeof CustomerForm>>()
const addressForm = ref<InstanceType<typeof AddressForm>>()
const addedAddressesList = ref<InstanceType<typeof AddedAddressesList>>()
const successSnackbar = ref<InstanceType<typeof Snackbar>>()

const validateAddress = async () => {
  const validate = await (v.value.address as Validation).$validate()

  if (!validate) return

  addedAddressesList.value?.addAddress({
    id: -1,
    ...addressForm.value!.form.data(),
  })
  addressForm.value?.form.reset()
  v.value.address.$reset()
}

const store = async () => {
  let addressFormValidate = true
  const personalInfoFormValidate =
    await (v.value.personalInfo.$validate() as Promise<boolean>)

  if (
    addedAddressesList.value?.addresses.length === 0 ||
    addressForm.value?.form.isDirty
  ) {
    addressFormValidate =
      await (v.value.address.$validate() as Promise<boolean>)
  } else {
    v.value.address.$reset()
  }

  if (!addressFormValidate || !personalInfoFormValidate) return

  router.post(
    route('customers.store'),
    {
      ...customerForm.value?.form.data(),
      ...addressForm.value?.form.data(),
      addresses: addedAddressesList.value?.addresses,
    },
    {
      onStart: () => (processing.value = true),
      onSuccess: () => {
        customerForm.value?.form.reset()
        addressForm.value?.form.reset()
        addedAddressesList.value?.resetAddresses()

        v.value.$reset()

        successSnackbar.value?.show(true)
      },
      onFinish: () => (processing.value = false),
    }
  )
}
</script>

<template>
  <div
    class="md-elevation-1 relative rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container"
  >
    <md-elevation />

    <CustomerForm
      ref="customerForm"
      class="grid gap-6 md:grid-cols-2"
      :config="{ $registerAs: 'personalInfo' }"
      :date-picker-config="{ teleport: 'body' }"
    />

    <md-divider class="my-8" />
    <div class="mb-4 flex items-center justify-between">
      <h1 class="text-on-background text-xl font-medium">Direcciones</h1>
      <md-filled-tonal-button @click="validateAddress">
        Agregar dirección
        <Icon slot="icon">add_location</Icon>
      </md-filled-tonal-button>
    </div>

    <AddressForm
      ref="addressForm"
      class="mb-6 grid gap-6 md:grid-cols-2"
      :submit="validateAddress"
      :config="{ $registerAs: 'address' }"
    />

    <AddedAddressesList ref="addedAddressesList" />

    <div class="text-end">
      <md-filled-button
        @click="store"
        :disabled="processing"
      >
        Guardar cliente
        <Icon slot="icon">save</Icon>
      </md-filled-button>
    </div>

    <Snackbar
      ref="successSnackbar"
      text="Información del cliente guardada con éxito."
      close-button
    />
  </div>
</template>
