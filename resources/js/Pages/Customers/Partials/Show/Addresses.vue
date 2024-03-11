<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import Snackbar from '@/Components/Snackbar.vue'
import { CustomerWithAddresses, IdAddress } from '@/types/customers'
import { usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import CreateAddressForm from './CreateAddressForm.vue'
import DeleteAddress from './DeleteAddress.vue'
import EditAddressForm from './EditAddressForm.vue'

const page = usePage()

const createAddressForm = ref<InstanceType<typeof CreateAddressForm>>()
const editAddressForm = ref<InstanceType<typeof EditAddressForm>>()
const cantDeleteSnackbar = ref<InstanceType<typeof Snackbar>>()
const deleteAddress = ref<InstanceType<typeof DeleteAddress>>()
const selectedAddress = ref<IdAddress>()
const addresses = computed(() => {
  const customer = page.props.customer as CustomerWithAddresses
  return customer.addresses
})

const createAddressDetails = (address: IdAddress) => {
  const { cross_streets, street_number, suite_number } = address
  const addressDetails: (string | null)[] = [
    cross_streets,
    street_number ? `n. ext. ${street_number}` : null,
    suite_number ? `n. int. ${suite_number}` : null,
  ].filter(Boolean)

  return addressDetails.join(', ')
}

const onUpdate = (address: IdAddress) => {
  selectedAddress.value = address
  editAddressForm.value?.editAddressModal?.show()
}

const onDestroy = (address: IdAddress) => {
  if (addresses.value.length <= 1) {
    cantDeleteSnackbar.value?.show(true)
    return
  }

  selectedAddress.value = address
  deleteAddress.value?.modal?.show()
}
</script>

<template>
  <div
    class="text-on-background rounded-lg border border-light-outline-variant pt-4 dark:border-dark-outline-variant"
    v-bind="$attrs"
  >
    <div class="mx-4 mb-4 flex items-center justify-between">
      <h1
        class="text-xl font-medium text-light-on-background dark:text-dark-on-background"
      >
        Direcciones
      </h1>
      <md-filled-button @click="createAddressForm?.addAddressModal?.show()">
        Agregar
        <Icon slot="icon">add</Icon>
      </md-filled-button>
    </div>
    <md-divider />
    <md-list
      class="rounded-b-lg bg-light-surface-container-lowest dark:bg-dark-surface-container"
    >
      <template v-for="(address, i) in addresses">
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
            <md-icon-button @click="onUpdate(address)">
              <Icon>edit</Icon>
            </md-icon-button>
            <md-icon-button
              id="delete-button"
              class="ms-4"
              @click="onDestroy(address)"
            >
              <Icon>delete</Icon>
            </md-icon-button>
          </div>
        </md-list-item>
      </template>
    </md-list>
  </div>

  <CreateAddressForm ref="createAddressForm" />

  <EditAddressForm
    ref="editAddressForm"
    :selectedAddress
  />

  <DeleteAddress
    ref="deleteAddress"
    :selectedAddress
  />

  <Snackbar
    ref="cantDeleteSnackbar"
    text="No es posible eliminar la única dirección del cliente."
    close-button
  />
</template>

<style scoped>
.dark #delete-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.error);
}
#delete-button {
  --md-sys-color-on-surface-variant: theme(colors.light.error);
}
</style>
