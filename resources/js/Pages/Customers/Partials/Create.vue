<script setup lang="ts">
import '@material/web/button/filled-button'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'

import { useForm } from '@inertiajs/vue3'

import { ref } from 'vue'
import PersonalInfoForm from './PersonalInfoForm.vue'
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import AddressForm from './AddressForm.vue'

const successSnackbar = ref<typeof Snackbar | null>(null)

const form = useForm({
  name: '',
  last_name: '',
  birth_date: '',
  email: '',
  phone_number: '',
  main_street: '',
  cross_streets: '',
  neighborhood: '',
  street_number: '',
  suite_number: '',
  postal_code: '',
})

const submit = () => {
  form.post(route('customers.store'), {
    onSuccess: () => {
      form.reset()
      successSnackbar.value?.show(true)
    },
  })
}
</script>

<template>
  <div
    class="md-elevation-1 relative rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container"
  >
    <md-elevation />
    <PersonalInfoForm
      :submit="submit"
      :form="form"
    />
    <md-divider class="my-8" />
    <AddressForm
      :submit="submit"
      :form="form"
    />
    <div class="text-end">
      <md-filled-button
        @click="submit"
        :disabled="form.processing"
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
