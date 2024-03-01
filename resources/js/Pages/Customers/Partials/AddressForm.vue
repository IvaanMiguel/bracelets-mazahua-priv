<script setup lang="ts">
import { Address } from '@/types/customers'

import '@material/web/button/filled-tonal-button'

import { useForm } from '@inertiajs/vue3'
import { storeAddressRules } from '@/rules/address'

import { useFormErrors } from '@/composables/useFormErrors'

import { ref } from 'vue'
import AddedAddressesList from './AddedAddressesList.vue'
import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import TextField from '@/Components/TextField.vue'

defineProps<{
  submit: (e?: Event) => void
}>()

const form = useForm<Address>({
  main_street: '',
  cross_streets: '',
  neighborhood: '',
  postal_code: '',
  street_number: '',
  suite_number: '',
})

const { v$ } = useFormErrors(storeAddressRules, form)

const addressesList = ref<typeof AddedAddressesList | null>(null)

const validateAddress = async () => {
  const validate = await v$.value.$validate()

  if (validate) {
    addressesList.value?.addAddress({ ...form.data() })
    form.reset()
    form.clearErrors()
    v$.value.$reset()
  }
}

defineExpose({
  form,
})
</script>

<template>
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-on-background text-xl font-medium">Direcciones</h1>
    <md-filled-tonal-button @click="validateAddress">
      Agregar dirección
      <Icon slot="icon">add_location</Icon>
    </md-filled-tonal-button>
  </div>
  <Form
    class="flex flex-col"
    :submit="submit"
  >
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <TextField
        class="w-full flex-1"
        label="Calle principal"
        name="main_street"
        :error="form.errors.main_street"
        required
        v-model="form.main_street"
      >
        <template #floating-icon>
          <Icon>location_home</Icon>
        </template>
      </TextField>
      <TextField
        class="w-full flex-1"
        label="Calles adyacentes"
        :error="form.errors.cross_streets"
        v-model="form.cross_streets"
      >
        <template #floating-icon>
          <Icon>alt_route</Icon>
        </template>
      </TextField>
    </div>
    <div class="mb-6 flex flex-col gap-6 md:flex-row">
      <div class="flex w-full flex-1 flex-col items-start gap-6 md:flex-row">
        <TextField
          class="w-full flex-[3]"
          label="Colonia"
          :error="form.errors.neighborhood"
          required
          v-model="form.neighborhood"
        >
          <template #floating-icon>
            <Icon>warehouse</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-[2]"
          label="Código postal"
          :error="form.errors.postal_code"
          required
          v-model="form.postal_code"
        >
          <template #floating-icon>
            <Icon>local_post_office</Icon>
          </template>
        </TextField>
      </div>
      <div class="flex w-full flex-1 items-start gap-6">
        <TextField
          class="w-full flex-1"
          label="Número Exterior"
          :error="form.errors.street_number"
          v-model="form.street_number"
        >
          <template #floating-icon>
            <Icon>house</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-1"
          label="Número interior"
          :error="form.errors.suite_number"
          v-model="form.suite_number"
        >
          <template #floating-icon>
            <Icon>apartment</Icon>
          </template>
        </TextField>
      </div>
    </div>
  </Form>
  <AddedAddressesList ref="addressesList" />
</template>
