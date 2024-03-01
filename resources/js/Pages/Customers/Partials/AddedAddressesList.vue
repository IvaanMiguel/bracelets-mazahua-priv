<script setup lang="ts">
import { MdDialog } from '@material/web/dialog/dialog'
import { Address } from '@/types/customers'

import '@material/web/divider/divider'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'

import { useModal } from '@/composables/useModal'

import { reactive, ref } from 'vue'
import EditAddedAddressForm from './EditAddedAddressForm.vue'
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import RemoveAddedAddress from './RemoveAddedAddress.vue'

const addedAddressSnackbar = ref<typeof Snackbar>()
const selectedAddress = ref<number | null>(null)
const addresses = reactive<(Address & { id: number })[]>([])
let incrementalId = 0

const { modal: removeAddressModal } = useModal('#remove-address-modal')
const { modal: editAddressModal } = useModal('#edit-address-modal')

const addAddress = (address: Address & { id: number }) => {
  address.id = incrementalId
  addresses.push(address)
  incrementalId += 1
  addedAddressSnackbar.value?.show(true)
}

const createAddressDetails = (address: Address) => {
  const { cross_streets, street_number, suite_number } = address
  const addressDetails: (string | null)[] = [
    cross_streets,
    street_number ? `n. ext. ${street_number}` : null,
    suite_number ? `n. int. ${suite_number}` : null,
  ].filter(Boolean)

  return addressDetails.join(', ')
}

const openModal = (modal: MdDialog | null, addressId: number) => {
  selectedAddress.value = addressId
  modal?.show()
}

defineExpose({
  addAddress,
})
</script>
1
<template>
  <md-list
    class="mb-6 rounded border border-light-outline bg-light-surface-container-lowest dark:border-dark-outline dark:bg-dark-surface-container"
  >
    <h1 class="text-on-background px-4 py-3 text-xl font-medium">
      Direcciones agregadas
    </h1>
    <md-divider inset />
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
      v-for="address in addresses"
    >
      <md-divider inset />
      <md-list-item>
        <span slot="headline">
          {{
            `${address.neighborhood}, calle ${address.main_street}, C. P. ${address.postal_code}`
          }}
        </span>
        <span slot="supporting-text">
          {{ createAddressDetails(address) }}
        </span>
        <div slot="end">
          <md-icon-button @click="openModal(editAddressModal, address.id)">
            <Icon>edit</Icon>
          </md-icon-button>
          <md-icon-button
            id="remove-button"
            class="ms-4"
            @click="openModal(removeAddressModal, address.id)"
          >
            <Icon>remove</Icon>
          </md-icon-button>
        </div>
      </md-list-item>
    </template>
  </md-list>

  <RemoveAddedAddress
    :addresses="addresses"
    :selected-address="selectedAddress"
    @unselect-address="selectedAddress = null"
  />

  <EditAddedAddressForm
    :addresses="addresses"
    :selected-address="selectedAddress"
  />

  <Snackbar
    ref="addedAddressSnackbar"
    close-button
  >
    <template #content>Dirección agregada correctamente.</template>
  </Snackbar>
</template>

<style scoped>
.dark #remove-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.error);
}
#remove-button {
  --md-sys-color-on-surface-variant: theme(colors.light.error);
}
</style>
