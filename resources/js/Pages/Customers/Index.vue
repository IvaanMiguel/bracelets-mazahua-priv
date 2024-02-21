<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'

defineOptions({
  layout: MainLayout,
})
</script>

<template>
  <h1 class="text-2xl text-light-primary dark:text-dark-primary">
    Customers page
  </h1>
  {{ $page.component }}
</template>
