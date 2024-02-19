<script setup lang="ts">
import '@material/web/icon/icon'

withDefaults(
  defineProps<{
    filled?: boolean
    size?: string
  }>(),
  {
    filled: false,
    size: '24px',
  }
)
</script>

<template>
  <md-icon :filled="filled">
    <slot />
  </md-icon>
</template>

<style scoped>
md-icon {
  --md-icon-font: 'Material Symbols Rounded';
  --md-icon-size: v-bind(size);
}
md-icon[filled='true'] {
  font-variation-settings: 'FILL' 1;
}
</style>
