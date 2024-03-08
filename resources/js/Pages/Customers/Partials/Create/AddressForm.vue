<script setup lang="ts">
import AddressForm from '@/Components/Forms/AddressForm.vue'
import Icon from '@/Components/Icon.vue'
import { IdAddress } from '@/types/customers'
import { reactive, ref } from 'vue'
import AddedAddressesList from './AddedAddressesList.vue'

const addressForm = ref<InstanceType<typeof AddressForm>>()
const addressesList = ref<InstanceType<typeof AddedAddressesList>>()
const addresses = reactive<IdAddress[]>([])

const validateAddress = async () => {
  const validate = await addressForm.value?.v$.$validate()

  if (!validate) return

  addressesList.value?.addAddress({ id: -1, ...addressForm.value!.form.data() })
  addressForm.value?.form.reset()
  addressForm.value?.v$.$reset()
}

const resetAddresses = () => {
  addresses.splice(0, addresses.length)
}

defineExpose({
  addressForm,
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

  <AddressForm
    ref="addressForm"
    class="mb-6 grid gap-6 md:grid-cols-2"
    :submit="validateAddress"
    :config="{ $registerAs: 'address' }"
  />

  <AddedAddressesList
    ref="addressesList"
    :addresses="addresses"
  />
</template>
