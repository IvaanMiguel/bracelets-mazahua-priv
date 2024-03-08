<script setup lang="ts">
import Icon from '@/Components/Icon.vue'
import '@material/web/elevation/elevation'
import '@material/web/focus/md-focus-ring'
import '@material/web/ripple/ripple'

withDefaults(
  defineProps<{
    icon?: string
    active?: boolean
  }>(),
  {
    active: false,
  }
)
</script>

<template>
  <component
    :is="$attrs.href ? 'Link' : 'button'"
    class="active:md-elevation-1 hover:md-elevation-4 relative flex items-center gap-3 rounded-full p-3 text-light-on-primary outline-none dark:text-dark-on-primary-container"
    :class="{
      'bg-light-on-primary !text-light-primary dark:bg-dark-on-primary-container dark:!text-dark-primary-container':
        active,
    }"
    as="button"
  >
    <span class="absolute left-0 right-0 top-1/2 h-12 -translate-y-1/2"></span>
    <Icon :filled="active">{{ icon }}</Icon>
    <slot />
    <md-ripple aria-hidden="true" />
    <md-focus-ring aria-hidden="true" />
    <md-elevation />
  </component>
</template>

<style scoped>
.dark md-ripple {
  --md-sys-color-on-surface: theme(colors.dark.on-primary-container);
}

md-ripple {
  --md-sys-color-on-surface: theme(colors.light.on-primary);
}
</style>
