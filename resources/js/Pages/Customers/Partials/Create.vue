<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { router } from '@inertiajs/vue3'
import useVuelidate from '@vuelidate/core'
import { ref } from 'vue'
import AddressForm from './Create/AddressForm.vue'
import PersonalInfoForm from './Create/PersonalInfoForm.vue'

const v = useVuelidate()

const successSnackbar = ref<InstanceType<typeof Snackbar>>()
const personalInfoForm = ref<InstanceType<typeof PersonalInfoForm>>()
const addressForm = ref<InstanceType<typeof AddressForm>>()
const processing = ref(false)

const submit = async () => {
  let addressFormValidate = true
  const personalInfoFormValidate =
    await (v.value.personalInfo.$validate() as Promise<boolean>)

  if (
    addressForm.value?.addresses.length === 0 ||
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
      ...personalInfoForm.value?.form.data(),
      ...addressForm.value?.form.data(),
      addresses: addressForm.value?.addresses,
    },
    {
      onStart: () => (processing.value = true),
      onSuccess: () => {
        personalInfoForm.value?.form.reset()
        addressForm.value?.form.reset()
        addressForm.value?.resetAddresses()

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
    <PersonalInfoForm ref="personalInfoForm" />
    <md-divider class="my-8" />
    <AddressForm ref="addressForm" />
    <div class="text-end">
      <md-filled-button
        @click="submit"
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
