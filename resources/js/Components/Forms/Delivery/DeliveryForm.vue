<script setup lang="ts">
import DatePicker from '@/Components/DatePicker.vue'
import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import TimePicker from '@/Components/TimePicker.vue'
import { IdAddress } from '@/types/customers'
import { Delivery } from '@/types/orders'
import { useForm } from '@inertiajs/vue3'
import '@material/web/select/outlined-select'
import '@material/web/select/select-option'
import {
  format,
  getHours,
  getMinutes,
  isSameDay,
  isSameMonth,
  isSameYear,
} from 'date-fns'
import { computed, watch } from 'vue'
import AddressItem from './AddressItem.vue'

const props = defineProps<{
  defaults?: Delivery
  deliveryTypes: { id: number; name: string }[]
  deliveryApps: { id: number; name: string }[]
  addresses: IdAddress[]
}>()

const form = useForm<Delivery>({
  delivery_type_id:
    props.defaults?.delivery_type_id || props.deliveryTypes[0].id,
  delivery_app_id: props.defaults?.delivery_app_id || props.deliveryApps[0].id,
  address_id:
    props.defaults?.address_id ||
    (props.addresses.length ? props.addresses[0].id : -1),
  date: format(props.defaults?.date || Date.now(), 'yyyy/MM/dd'),
  time: props.defaults?.time.substring(0, 5) || format(Date.now(), 'HH:mm'),
})

const minTime = computed(() => {
  const date = props.defaults?.date || Date.now()

  if (
    !(
      isSameDay(form.date, date) &&
      isSameMonth(form.date, date) &&
      isSameYear(form.date, date)
    )
  ) {
    return
  }

  const time = props.defaults?.time.split(':')

  return props.defaults?.date
    ? { hours: time![0], minutes: time![1] }
    : {
        hours: +getHours(date),
        minutes: +getMinutes(date),
      }
})

const onSelectedAddress = (id: number) => {
  form.address_id = id
}

watch(
  () => props.addresses,
  () => {
    form.address_id = props.addresses[0].id
  }
)

defineExpose({ form })
</script>

<template>
  <Form
    class="flex flex-col"
    :inertia-form="form"
    :rules="{}"
  >
    <div class="mb-6 grid gap-6 md:grid-cols-2">
      <div class="flex">
        <span class="text-on-background me-4">
          <Icon>quick_reorder</Icon>
        </span>
        <md-outlined-select
          class="w-full"
          required
          label="Tipo de entrega"
          :error="!!form.errors.delivery_type_id"
          :error-text="form.errors.delivery_type_id"
          v-model="form.delivery_type_id"
        >
          <md-select-option
            v-for="deliveryType in deliveryTypes"
            :value="deliveryType.id"
          >
            {{ deliveryType.name }}
          </md-select-option>
        </md-outlined-select>
      </div>
      <div class="flex">
        <span class="text-on-background me-4">
          <Icon>local_shipping</Icon>
        </span>
        <md-outlined-select
          class="w-full"
          required
          label="Aplicación de entrega"
          :error="!!form.errors.delivery_app_id"
          :error-text="form.errors.delivery_app_id"
          :disabled="form.delivery_type_id !== 3"
          v-model="form.delivery_app_id"
        >
          <md-select-option
            v-for="deliveryApp in deliveryApps"
            :value="deliveryApp.id"
          >
            {{ deliveryApp.name }}
          </md-select-option>
        </md-outlined-select>
      </div>
      <DatePicker
        :auto-position="false"
        label="Fecha de entrega"
        :error="form.errors.date"
        :min-date="props.defaults?.date || new Date()"
        :clearable="false"
        floating-icon
        icon="event"
        v-model="form.date"
      />
      <TimePicker
        :auto-position="false"
        label="Hora de entrega"
        :error="form.errors.time"
        :min-time="minTime"
        :clearable="false"
        floating-icon
        icon="alarm"
        v-model="form.time"
      />
    </div>
    <div
      class="text-on-background col-span-full flex min-h-[204px] flex-1 flex-col overflow-hidden rounded-lg border border-light-outline-variant bg-light-surface-container-lowest dark:border-dark-outline-variant dark:bg-dark-surface-container"
    >
      <h3
        class="text-on-background text-md border-b border-light-outline-variant p-4 font-medium dark:border-dark-outline-variant"
      >
        Dirección de entrega
      </h3>
      <md-list class="h-full overflow-y-auto rounded-b-lg bg-transparent p-0">
        <template v-for="(address, i) in addresses">
          <md-divider
            v-if="i !== 0"
            class="min-h-[1px]"
            inset
          />
          <AddressItem
            :address
            :disabled="form.delivery_type_id === 1"
            :checked="
              address.id === form.address_id ||
              address.id === defaults?.address_id
            "
            @selected-address="onSelectedAddress"
          />
        </template>
      </md-list>
    </div>
  </Form>
</template>
