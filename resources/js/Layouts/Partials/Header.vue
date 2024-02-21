<script setup lang="ts">
import ColorMode from '@/Components/ColorMode.vue'
</script>

<template>
  <header
    class="flex items-center border-b-2 border-light-on-surface p-4 dark:border-dark-on-surface"
  >
    <h1
      class="text-2xl font-medium text-light-primary dark:text-dark-on-surface"
    >
      Bracelets Mazahua
    </h1>
    <ColorMode class="ms-auto me-4" />
    <h6 class="text-lg text-light-on-background dark:text-dark-on-background">
      {{ $page.props.auth.user.username }}
    </h6>
  </header>
</template>
