<script setup lang="ts">
import { Address } from '@/types/customers'

import '@material/web/button/filled-tonal-button'
import '@material/web/divider/divider'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'

import { reactive } from 'vue'
import Icon from '@/Components/Icon.vue'

const addresses = reactive<Address[]>([])

const addAddress = (address: Address) => {
  addresses.push(address)
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
    <template v-for="address in addresses">
      <md-divider inset />
      <md-list-item>
        <span slot="headline">
          {{
            `${address.neighborhood}, ${address.main_street}, C. P. ${address.postal_code}`
          }}
        </span>
        <span slot="supporting-text">
          {{
            `${address.cross_streets}, n. ext.${address.street_number}, n. int. ${address.suite_number}`
          }}
        </span>
        <div slot="end">
          <md-icon-button>
            <Icon>edit</Icon>
          </md-icon-button>
          <md-icon-button
            id="delete-button"
            class="ms-4"
          >
            <Icon>delete</Icon>
          </md-icon-button>
        </div>
      </md-list-item>
    </template>
  </md-list>
</template>

<style scoped>
.dark #delete-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.error);
}
#delete-button {
  --md-sys-color-on-surface-variant: theme(colors.light.error);
}
</style>
