<script setup lang="ts">
import HighlightSearch from '@/Components/HighlightSearch.vue'
import { Customer } from '@/types/orders'
import { usePage } from '@inertiajs/vue3'
import { MdRadio } from '@material/web/radio/radio'
import { computed, ref } from 'vue'

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

const page = usePage()

const search = computed(() => page.props.filters.search as { customer: string })
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
    <HighlightSearch
      :text="`${customer.name} ${customer.last_name}`"
      :search="`${search.customer}`"
    />
  </md-list-item>
</template>
