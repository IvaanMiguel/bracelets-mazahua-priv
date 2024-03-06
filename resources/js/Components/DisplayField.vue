<script setup lang="ts">
defineProps<{
  label: string
  text: string
}>()
</script>

<template>
  <div class="flex gap-4">
    <slot name="icon" />
    <div class="text-on-background flex flex-col gap-2 overflow-hidden">
      <div class="truncate text-xs font-medium">{{ label }}</div>
      <div class="truncate text-base">{{ text }}</div>
    </div>
  </div>
</template>
