<script setup lang="ts">
import '@material/web/button/filled-button'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'

import { router } from '@inertiajs/vue3'

import { ref } from 'vue'
import PersonalInfoForm from './PersonalInfoForm.vue'
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import AddressForm from './AddressForm.vue'

const successSnackbar = ref<typeof Snackbar | null>(null)
const personalInfoForm = ref<typeof PersonalInfoForm | null>(null)
const addressForm = ref<typeof AddressForm | null>(null)

const processing = ref(false)

const submit = () => {
  router.post(
    route('customers.store'),
    {
      ...personalInfoForm.value?.form.data(),
    },
    {
      onStart: () => (processing.value = true),
      onSuccess: () => {
        personalInfoForm.value?.form.reset()
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
    <PersonalInfoForm
      ref="personalInfoForm"
      :submit="submit"
    />
    <md-divider class="my-8" />
    <AddressForm
      ref='addressForm'
      :submit="submit"
    />
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
      close-button
    >
      <template #content>
        Información del cliente guardada con éxito.
      </template>
    </Snackbar>
  </div>
</template>
