<script setup lang="ts">
import '@material/web/elevation/elevation'
import '@material/web/focus/md-focus-ring'
import '@material/web/ripple/ripple'
import Icon from '@/Components/Icon.vue'

defineProps<{
  icon?: string
}>()
</script>

<template>
  <component
    :is="$attrs.href ? 'Link' : 'button'"
    class="relative flex items-center gap-3 rounded-full py-3 pe-4 ps-3 text-white outline-none dark:text-dark-on-primary-container"
    as="button"
  >
    <span class="absolute left-0 right-0 top-1/2 h-12 -translate-y-1/2"></span>
    <Icon>{{ icon }}</Icon>
    <slot />
    <md-ripple aria-hidden="true" />
    <md-focus-ring aria-hidden="true" />
    <md-elevation />
  </component>
</template>

<style scoped>
button {
  --md-elevation-level: 0;
}

button:hover {
  --md-elevation-level: 4;
}

button:active {
  --md-elevation-level: 1;
}

.dark md-ripple {
  --md-ripple-hover-color: theme(colors.dark.on-primary-container);
  --md-ripple-pressed-color: theme(colors.dark.on-primary-container);
}

md-ripple {
  --md-ripple-hover-color: white;
  --md-ripple-pressed-color: white;
}
</style>
