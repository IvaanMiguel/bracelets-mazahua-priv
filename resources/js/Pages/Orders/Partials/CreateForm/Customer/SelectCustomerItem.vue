<script setup lang="ts">
import { Customer } from '@/types/orders'
import { MdRadio } from '@material/web/radio/radio'
import { ref } from 'vue'

const emit = defineEmits<{ selectedCustomer: [customer: Customer] }>()
const props = withDefaults(
  defineProps<{
    customer: Customer
    selected?: boolean
  }>(),
  {
    selected: false,
  }
)

const radio = ref<MdRadio>()

const onClick = () => {
  radio.value!.checked = true
  emit('selectedCustomer', props.customer)
}
</script>

<template>
  <md-list-item
    type="button"
    @click="onClick"
  >
    <md-radio
      ref="radio"
      slot="start"
      name="customer"
      :checked="selected"
      :value="customer.id"
    />
    {{ `${customer.name} ${customer.last_name}` }}
  </md-list-item>
</template>
