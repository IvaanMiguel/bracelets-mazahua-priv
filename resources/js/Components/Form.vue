<script setup lang="ts">
import { useFormErrors } from '@/composables/useFormErrors'
import { InertiaForm } from '@inertiajs/vue3'
import { GlobalConfig, ValidationArgs } from '@vuelidate/core'
import { useEventListener } from '@vueuse/core'
import { ref } from 'vue'

const props = withDefaults(
  defineProps<{
    submit?: (e?: Event) => void
    notSubmitOnEnter?: boolean
    inertiaForm: InertiaForm<any>
    rules: ValidationArgs
    config?: GlobalConfig
  }>(),
  {
    notSubmitOnEnter: false,
  }
)

useFormErrors(props.rules, props.inertiaForm, props.config)

const form = ref<HTMLFormElement>()
useEventListener(form, 'keydown', (e: KeyboardEvent) => {
  if (props.inertiaForm.processing) return

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
