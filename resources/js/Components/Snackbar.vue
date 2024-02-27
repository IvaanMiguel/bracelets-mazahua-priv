<script setup lang="ts">
import '@material/web/button/text-button'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'

import { computed, ref } from 'vue'
import Icon from './Icon.vue'

const props = withDefaults(
  defineProps<{
    action?: string
    onAction?: Function
    closeButton?: boolean
  }>(),
  {
    closeButton: false,
  }
)

const paddingEnd = computed(() => {
  if (props.closeButton) {
    return 'pe-3'
  } else if (props.action) {
    return 'pe-2'
  } else {
    return 'pe-4'
  }
})

const isVisible = ref(false)

const close = () => (isVisible.value = false)
const show = () => (isVisible.value = true)

defineExpose({
  close,
  show
})
</script>

<template>
  <Teleport to="body">
    <Transition>
      <div
        v-show="isVisible"
        class="absolute inset-x-0 bottom-4 z-[1] mx-auto h-fit w-fit"
      >
        <div
          class="md-elevation-3 relative flex max-h-16 min-h-12 w-fit min-w-[40ch] max-w-[60ch] items-center justify-between rounded bg-light-inverse-surface ps-4 dark:bg-dark-inverse-surface"
          :class="[paddingEnd, action ? 'gap-2' : 'gap-3']"
        >
          <md-elevation />
          <div
            class="overflow-hidden text-ellipsis text-nowrap text-sm text-light-inverse-on-surface dark:text-dark-inverse-on-surface"
          >
            <slot name="content" />
          </div>
          <div
            v-if="action || closeButton"
            class="flex gap-3"
          >
            <md-text-button
              v-if="action"
              class="text-sm"
              @click="onAction"
            >
              {{ action }}
            </md-text-button>
            <md-icon-button
              v-if="closeButton"
              @click="close"
            >
              <Icon>close</Icon>
            </md-icon-button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.dark md-text-button {
  --md-sys-color-primary: theme(colors.dark.inverse-primary);
}
md-text-button {
  --md-sys-color-primary: theme(colors.light.inverse-primary);
}
.dark md-icon-button {
  --md-sys-color-on-surface-variant: theme(colors.dark.inverse-on-surface);
}
md-icon-button {
  --md-sys-color-on-surface-variant: theme(colors.light.inverse-on-surface);
}
</style>
