<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import '@material/web/button/filled-button'
import '@material/web/divider/divider'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/list/list'
import '@material/web/list/list-item'
import Addresses from './Partials/Show/Addresses.vue'
import PersonalInfo from './Partials/Show/PersonalInfo.vue'

defineOptions({
  layout: MainLayout,
})

const back = () => history.back()
</script>

<template>
  <div class="p-4">
    <div
      class="md-elevation-1 relative mx-auto w-full max-w-6xl rounded-lg bg-light-surface-container-lowest p-4 dark:bg-dark-surface-container"
    >
      <md-elevation />
      <md-icon-button
        class="mb-6"
        @click="back"
      >
        <Icon>arrow_back</Icon>
      </md-icon-button>
      <PersonalInfo />
      <Addresses class="mt-6" />
    </div>
  </div>
</template>
