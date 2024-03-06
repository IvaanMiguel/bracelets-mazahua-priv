<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import '@material/web/button/filled-button'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
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
      <md-icon-button @click="back">
        <Icon>arrow_back</Icon>
      </md-icon-button>
      <PersonalInfo />
    </div>
  </div>
</template>
