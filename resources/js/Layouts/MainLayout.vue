<script setup lang="ts">
import Header from './Partials/Header.vue'
import LateralMenu from './Partials/Menu/LateralMenu.vue'
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
</template>
