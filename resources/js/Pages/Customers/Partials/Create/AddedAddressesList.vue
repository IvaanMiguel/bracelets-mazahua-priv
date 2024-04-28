<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { IdAddress } from '@/types/customers'
import { MdDialog } from '@material/web/dialog/dialog'
import { reactive, ref } from 'vue'
import EditAddedAddressForm from './EditAddedAddressForm.vue'
import RemoveAddedAddress from './RemoveAddedAddress.vue'

const addresses = reactive<IdAddress[]>([])
const editAddedAddressForm = ref<InstanceType<typeof EditAddedAddressForm>>()
const removeAddedAddress = ref<InstanceType<typeof RemoveAddedAddress>>()
const addedAddressSnackbar = ref<InstanceType<typeof Snackbar>>()
const selectedAddress = ref<IdAddress | null>(null)
let incrementalId = 0

const addAddress = (address: IdAddress) => {
  address.id = incrementalId
  addresses.push(address)
  incrementalId += 1
  addedAddressSnackbar.value?.show(true)
}

const resetAddresses = () => {
  addresses.splice(0, addresses.length)
}

const createAddressDetails = (address: IdAddress) => {
  const { cross_streets, street_number, suite_number } = address
  const addressDetails: (string | null)[] = [
    cross_streets,
    street_number ? `n. ext. ${street_number}` : null,
    suite_number ? `n. int. ${suite_number}` : null,
  ].filter(Boolean)

  return addressDetails.join(', ')
}

const openModal = (address: IdAddress, modal?: MdDialog | null) => {
  selectedAddress.value = address
  modal?.show()
}

defineExpose({ addAddress, resetAddresses, addresses })
</script>
1
<template>
  <md-list
    class="mb-6 rounded border border-light-outline bg-light-surface-container-lowest dark:border-dark-outline dark:bg-dark-surface-container"
  >
    <h1 class="text-on-background px-4 py-3 text-xl font-medium">
      Direcciones agregadas
    </h1>
    <md-divider />
    <div
      v-if="!addresses.length"
      class="p-4 text-center"
    >
      <Icon class="text-light-on-background dark:text-dark-on-background">
        distance
      </Icon>
      <span
        class="block font-medium text-light-on-background dark:text-dark-on-background"
      >
        Aún no se han agregado más direcciones.
      </span>
    </div>
    <template
      v-else
      v-for="(address, i) in addresses"
    >
      <md-divider
        v-if="i !== 0"
        inset
      />
      <md-list-item>
        <span slot="headline">
          {{
            `Colonia ${address.neighborhood}, calle ${address.main_street}, C. P. ${address.postal_code}`
          }}
        </span>
        <span slot="supporting-text">
          {{ createAddressDetails(address) }}
        </span>
        <div slot="end">
          <md-icon-button
            @click="openModal(address, editAddedAddressForm?.editModal?.dialog)"
          >
            <Icon>edit</Icon>
          </md-icon-button>
          <md-icon-button
            id="remove-button"
            class="ms-4"
            @click="openModal(address, removeAddedAddress?.removeModal?.dialog)"
          >
            <Icon>remove</Icon>
          </md-icon-button>
        </div>
      </md-list-item>
    </template>
  </md-list>

  <RemoveAddedAddress
    ref="removeAddedAddress"
    :addresses="addresses"
    :selected-address="selectedAddress"
    @unselect-address="() => (selectedAddress = null)"
  />

  <EditAddedAddressForm
    ref="editAddedAddressForm"
    :addresses="addresses"
    :selected-address="selectedAddress"
  />

  <Snackbar
    ref="addedAddressSnackbar"
    text="Dirección agregada correctamente."
    close-button
  />
</template>

<style scoped>
.dark #remove-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.error);
}
#remove-button {
  --md-sys-color-on-surface-variant: theme(colors.light.error);
}
</style>
