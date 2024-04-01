<script setup lang="ts">
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/select/outlined-select'
import '@material/web/select/select-option'
import VueDatePicker, { ModelValue } from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { format } from 'date-fns'
import { es } from 'date-fns/locale'
import { computed, ref } from 'vue'
import Icon from './Icon.vue'
import TextField from './TextField.vue'

withDefaults(
  defineProps<{
    error?: string
    floatingIcon?: boolean
    icon?: string
    label?: string
  }>(),
  {
    floatingIcon: false
  }
)
const model = defineModel<ModelValue>()

const textfield = ref<InstanceType<typeof TextField>>()
const formatValue = computed(() => {
  // For some reason, this needs to be here in order to properly display a date that already has been setted.
  textfield.value?.$forceUpdate()

  if (!model.value) return

  return format(new Date(`${model.value}`), "iiii, dd 'de' MMMM 'de' y", {
    locale: es,
  })
})

const formatPreview = (value: string) => {
  if (!value) return

  return format(value, 'dd/MMMM/y', { locale: es })
}
</script>

<template>
  <VueDatePicker
    @closed="textfield?.input?.focus()"
    menu-class-name="dp__menu"
    calendar-cell-class-name="dp__calendar-cell"
    locale="es"
    model-type="yyyy/MM/dd"
    :enable-time-picker="false"
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
        <template v-if="floatingIcon" #floating-icon>
          <Icon>{{ icon }}</Icon>
        </template>
      </TextField>
    </template>

    <template #arrow-left>
      <Icon>navigate_before</Icon>
    </template>

    <template #arrow-right>
      <Icon>navigate_next</Icon>
    </template>

    <template #action-preview="{ value }">
      <md-elevation />
      {{ formatPreview(value) }}
    </template>

    <template #calendar-icon>
      <Icon>calendar_today</Icon>
    </template>

    <template #clear-icon="{ clear }">
      <md-icon-button
        @click="clear"
        class="me-4"
        type="button"
        title="Remover fecha seleccionada."
      >
        <Icon>event_busy</Icon>
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
.dp__menu {
  --md-elevation-level: 3;

  position: relative;
  border-width: 0;
}
.dp__calendar-cell {
  border-radius: 50%;
  padding: 0;
}
.dp__clear_icon {
  top: 0.5rem;
  transform: unset;
}
</style>
