<script setup lang="ts">
import '@material/web/button/text-button'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import { computed, ref, watch } from 'vue'
import Icon from './Icon.vue'

const props = withDefaults(
  defineProps<{
    action?: string
    onAction?: Function
    closeButton?: boolean
    time?: number
  }>(),
  {
    closeButton: false,
    time: 3
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

let closeOnTimeoutId:number | undefined = undefined
const show = (bool: boolean) => (isVisible.value = bool)
const closeOnTimeout = () => isVisible.value = false

watch(isVisible, () => {
  if (isVisible.value) {
    closeOnTimeoutId = setTimeout(closeOnTimeout, props.time * 1000)

    return
  }

  clearTimeout(closeOnTimeoutId)
})

defineExpose({
  show
})
</script>

<template>
  <Teleport to="body">
    <div
      class="absolute inset-x-0 bottom-4 z-[1] mx-auto h-fit w-fit"
    >
      <Transition
        enter-from-class="translate-y-20"
        enter-active-class="transition-transform duration-300 ease-out"
        leave-active-class="transition-transform duration-300 ease-out"
        leave-to-class="translate-y-20"
      >
        <div
          v-show="isVisible"
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
              @click="show(false)"
            >
              <Icon>close</Icon>
            </md-icon-button>
          </div>
        </div>
      </Transition>
    </div>
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
