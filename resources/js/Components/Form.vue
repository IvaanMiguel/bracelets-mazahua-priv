<script setup lang="ts">
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

const props = withDefaults(
  defineProps<{
    submit?: (e?: Event) => void
    notSubmitOnEnter?: boolean
  }>(),
  {
    notSubmitOnEnter: false,
  }
)

const form = ref<HTMLFormElement | null>(null)

useEventListener(form, 'keydown', (e: KeyboardEvent) => {
  if (
    (e.code !== 'Enter' && e.code !== 'NumpadEnter') ||
    props.notSubmitOnEnter
  )
    return

  if (props.submit) props.submit()
})
</script>

<template>
  <form
    @submit.prevent="submit"
    ref="form"
  >
    <slot />
  </form>
</template>
