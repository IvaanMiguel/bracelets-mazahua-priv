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
  date: props.defaults?.date || format(Date.now(), 'dd/MMMM/y'),
  time: props.defaults?.time || format(Date.now(), 'HH:mm'),
})

const minTime = computed(() => {
  if (
    isSameDay(form.date, Date.now()) &&
    isSameMonth(form.date, Date.now()) &&
    isSameYear(form.date, Date.now())
  ) {
    return {
      hours: +getHours(Date.now()),
      minutes: +getMinutes(Date.now()),
    }
  }

  return null
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
    :inertia-form="form"
    :rules="{
      hours: getHours(Date.now()),
      minutes: getMinutes(Date.now()),
    }"
  >
    <div class="mb-6 mt-2 grid gap-6 md:grid-cols-2">
      <div class="flex">
        <span class="text-on-background me-4">
          <Icon>quick_reorder</Icon>
        </span>
        <md-outlined-select
          class="w-full"
          required
          label="Tipo de entrega"
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
        teleport="body"
        label="Fecha de entrega"
        :min-date="new Date()"
        :clearable="false"
        floating-icon
        icon="event"
        v-model="form.date"
      />
      <TimePicker
        teleport="body"
        label="Hora de entrega"
        :min-time="minTime"
        :clearable="false"
        floating-icon
        icon="alarm"
        v-model="form.time"
      />
    </div>
    <div
      class="text-on-background col-span-full flex h-full min-h-[204px] flex-col overflow-hidden rounded-lg border border-light-outline-variant dark:border-dark-outline-variant"
    >
      <h3
        class="text-on-background text-md border-b border-light-outline-variant p-4 font-medium dark:border-dark-outline-variant"
      >
        Dirección de entrega
      </h3>
      <md-list
        class="h-full overflow-y-auto rounded-b-lg bg-light-surface-container-lowest p-0 dark:bg-dark-surface-container"
      >
        <template v-for="(address, i) in addresses">
          <md-divider
            v-if="i !== 0"
            class="min-h-[1px]"
            inset
          />
          <AddressItem
            :address
            :disabled="form.delivery_type_id === 1"
            :checked="address.id === form.address_id"
            @selected-address="onSelectedAddress"
          />
        </template>
      </md-list>
    </div>
  </Form>
</template>
