<script setup lang="ts">
import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import TextField from '@/Components/TextField.vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { addressRules } from '@/rules/address'
import { Address, IdAddress } from '@/types/customers'
import { useForm } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import AddedAddressesList from './AddedAddressesList.vue'

const form = useForm<Address>({
  main_street: '',
  cross_streets: '',
  neighborhood: '',
  postal_code: '',
  street_number: '',
  suite_number: '',
})
const { v$ } = useFormErrors(addressRules, form, { $registerAs: 'address' })

const mainStreetField = ref<InstanceType<typeof TextField>>()
const addressesList = ref<InstanceType<typeof AddedAddressesList>>()
const addresses = reactive<IdAddress[]>([])

const validateAddress = async () => {
  const validate = await v$.value.$validate()

  if (!validate) return

  addressesList.value?.addAddress({ id: -1, ...form.data() })
  form.reset()
  v$.value.$reset()

  mainStreetField.value?.input?.focus()
}

const resetAddresses = () => {
  addresses.splice(0, addresses.length)
}

defineExpose({
  form,
  addresses,
  resetAddresses,
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
    :submit="validateAddress"
  >
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <TextField
        ref="mainStreetField"
        class="w-full flex-1"
        label="Calle principal"
        name="main_street"
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
        class="w-full flex-1"
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
    </div>
    <div class="mb-6 flex flex-col gap-6 md:flex-row">
      <div class="flex w-full flex-1 flex-col items-start gap-6 md:flex-row">
        <TextField
          class="w-full flex-[3]"
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
          class="w-full flex-[2]"
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
      </div>
      <div class="flex w-full flex-1 items-start gap-6">
        <TextField
          class="w-full flex-1"
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
          class="w-full flex-1"
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
    </div>
  </Form>

  <AddedAddressesList
    ref="addressesList"
    :addresses="addresses"
  />
</template>
