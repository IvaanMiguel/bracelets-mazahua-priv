<script setup lang="ts">
import VueDatePicker, { ModelValue } from '@vuepic/vue-datepicker'
import { format } from 'date-fns'
import { es } from 'date-fns/locale'
import { computed, ref } from 'vue'
import Icon from './Icon.vue'
import TextField from './TextField.vue'

const model = defineModel<ModelValue>()
withDefaults(
  defineProps<{
    error?: string
    floatingIcon?: boolean
    icon?: string
    label?: string
  }>(),
  {
    floatingIcon: false,
  }
)

const textfield = ref<InstanceType<typeof TextField>>()
const formatValue = computed(() => {
  // For some reason, this needs to be here in order to properly display a date that already has been setted.
  textfield.value?.$forceUpdate()

  if (!model.value) return

  const time = (model.value as string).split(':')

  return format(new Date(0, 0, 0, +time[0], +time[1]), "'a las' HH:mm B", {
    locale: es,
  })
})
</script>

<template>
  <VueDatePicker
    @closed="textfield?.input?.focus()"
    menu-class-name="dp__menu"
    locale="es"
    time-picker
    model-type="HH:mm"
    v-model="model"
  >
    <template #dp-input>
      <TextField
        ref="textfield"
        readonly
        type="text"
        :label
        no-error-icon
        :error
        :value="formatValue"
      >
        <template
          v-if="floatingIcon"
          #floating-icon
        >
          <Icon>{{ icon }}</Icon>
        </template>
      </TextField>
    </template>

    <template #action-preview>
      <md-elevation />
    </template>

    <template #clear-icon="{ clear }">
      <md-icon-button
        @click="clear"
        class="me-4"
        type="button"
        title="Remover hora seleccionada."
      >
        <Icon>alarm_off</Icon>
      </md-icon-button>
    </template>
  </VueDatePicker>
</template>

<style>
.dark .dp__theme_light {
  --dp-background-color: theme(colors.dark.surface-container-high);
  --dp-primary-color: theme(colors.dark.primary);
  --dp-text-color: theme(colors.dark.on-background);
  --dp-secondary-color: theme(colors.dark.on-surface-variant);
  --dp-primary-text-color: theme(colors.dark.on-primary);
  --dp-disabled-color: theme(colors.neutral.10);
}
.dp__theme_light {
  --dp-background-color: theme(colors.light.surface-container-high);
  --dp-primary-color: theme(colors.light.primary);
  --dp-text-color: theme(colors.light.on-background);
  --dp-secondary-color: theme(colors.light.on-surface-variant);
  --dp-primary-text-color: theme(colors.light.on-primary);
  --dp-scroll-bar-background: transparent;
  --dp-menu-border-color: transparent;
}
</style>
