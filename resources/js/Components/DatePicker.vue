<script setup lang="ts">
import '@material/web/elevation/elevation'
import '@material/web/select/outlined-select'
import '@material/web/select/select-option'
import '@material/web/iconbutton/icon-button'
import '@vuepic/vue-datepicker/dist/main.css'

import { format } from 'date-fns'
import { es } from 'date-fns/locale'
import VueDatePicker, { ModelValue } from '@vuepic/vue-datepicker'

import { computed, ref } from 'vue'
import Icon from './Icon.vue'
import TextField from './TextField.vue'

const model = defineModel<ModelValue>({
  get: (v) => v || '2001/01/01',
})

const textfield = ref<InstanceType<typeof TextField>>()
const date = computed(() => new Date(`${model.value}`))

const formatValue = () =>
  format(date.value, "iiii, dd 'de' MMMM 'de' y", { locale: es })

const formatPreview = (value: string) =>
  format(value, 'dd/MMMM/y', { locale: es })
</script>

<template>
  <VueDatePicker
    @closed="textfield?.input?.focus()"
    menu-class-name="dp__menu"
    calendar-cell-class-name="dp__calendar-cell"
    locale="es"
    model-type="yyyy/MM/dd"
    :enable-time-picker="false"
    teleport="body"
    v-model="model"
  >
    <template #trigger>
      <TextField
        ref="textfield"
        readonly
        type="text"
        label="Fecha de nacimiento"
        :error="$page.props.errors.birth_date"
        :value="formatValue()"
      >
        <template #floating-icon>
          <Icon>cake</Icon>
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
  </VueDatePicker>
</template>

<style>
.dark .dp__theme_light {
  --dp-background-color: theme(colors.dark.surface-container-high);
  --dp-primary-color: theme(colors.dark.primary);
  --dp-text-color: theme(colors.dark.on-background);
  --dp-secondary-color: theme(colors.dark.on-surface-variant);
  --dp-primary-text-color: theme(colors.dark.on-primary);
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
.dp__calendar-row {
  margin: 50px !important;
}
</style>
