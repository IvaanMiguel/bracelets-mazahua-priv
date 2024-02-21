<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue'

const props = withDefaults(
  defineProps<{
    submit: () => void
    notSubmitOnEnter?: boolean
  }>(),
  {
    notSubmitOnEnter: false,
  }
)

const form = ref<HTMLFormElement | null>(null)

const submitFormOnEnter   = (e: KeyboardEvent) => {
  if (e.code !== 'Enter' && e.code !== 'NumpadEnter') return

  props.submit()
}

onMounted(() => {
  if (props.notSubmitOnEnter) return

  form.value?.addEventListener('keydown', submitFormOnEnter)
})

onUnmounted(() => {
  form.value?.removeEventListener('keydown', submitFormOnEnter)
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
