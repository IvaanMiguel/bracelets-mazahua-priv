<script setup lang="ts">
import ColorMode from './ColorMode.vue'
</script>

<template>
  <header
    class="flex items-center justify-between gap-2 border-b-2 border-light-on-surface p-4 dark:border-dark-on-surface"
  >
    <h1
      class="text-2xl font-medium text-light-primary dark:text-dark-on-surface"
    >
      Bracelets Mazahua
    </h1>
    <ColorMode class="ms-auto" />
    <h6 class="text-lg text-light-on-surface dark:text-dark-on-surface">
      {{ $page.props.auth.user.username }}
    </h6>
  </header>
</template>
