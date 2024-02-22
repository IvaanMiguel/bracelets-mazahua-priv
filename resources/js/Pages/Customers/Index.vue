<script setup lang="ts">
import '@material/web/tabs/primary-tab'

import MainLayout from '@/Layouts/MainLayout.vue'
import Tabs from '@/Components/Tabs.vue'
import Icon from '@/Components/Icon.vue'

defineOptions({
  layout: MainLayout,
})
</script>

<template>
  <Tabs auto-activate>
    <template #tab>
      <md-primary-tab id="list-tab" aria-controls="list-panel">
        <Icon slot="icon">list_alt</Icon>
        Clientes
      </md-primary-tab>
      <md-primary-tab id="add-tab" aria-controls="add-panel">
        <Icon slot="icon">add</Icon>
        Agregar cliente nuevo
      </md-primary-tab>
    </template>
    <template #panel>
      <div id="list-panel" aria-labelledby="list-tab" role="tabpanel">
        <div>Lista</div>
      </div>
      <div id="add-panel" aria-labelledby="add-tab" role="tabpanel">
        Agregar cliente nuevo
      </div>
    </template>
  </Tabs>
</template>
