<script setup lang="ts">
import Snackbar from '@/Components/Snackbar.vue'
import { router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import Header from './Partials/Header.vue'
import LateralMenu from './Partials/Menu/LateralMenu.vue'

const backendSnackbar = ref<InstanceType<typeof Snackbar>>()

onMounted(() => {
  router.on('error', () => {
    backendSnackbar.value?.show(true)
  })
})
</script>

<template>
  <div class="flex h-svh">
    <LateralMenu />
    <div class="flex flex-1 flex-col">
      <Header />
      <main class="h-svh overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>

  <Snackbar
    ref="backendSnackbar"
    :text="$page.props.errors.internal_error"
    close-button
  />
</template>
