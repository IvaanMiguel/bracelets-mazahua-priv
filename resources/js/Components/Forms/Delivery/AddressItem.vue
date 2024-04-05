<script setup lang="ts">
import { useAddressDetails } from '@/composables/useAddressDetails'
import { IdAddress } from '@/types/customers'
import '@material/web/radio/radio'
import { MdRadio } from '@material/web/radio/radio'
import { ref, toRef, useAttrs } from 'vue'

const emit = defineEmits<{ selectedAddress: [id: number] }>()
const props = defineProps<{ address: IdAddress }>()

const attrs = useAttrs()
const addressDetails = useAddressDetails(toRef(() => props.address))

const radio = ref<MdRadio>()

const onClick = () => {
  if (attrs.disabled) return

  radio.value!.checked = true
  emit('selectedAddress', props.address.id)
}
</script>

<template>
  <md-list-item
    type="button"
    selected
    @click="onClick"
  >
    <md-radio
      ref="radio"
      slot="start"
      name="address"
      :checked="$attrs.checked"
    />
    <span slot="headline">
      {{ addressDetails.headline }}
    </span>
    <span slot="supporting-text">
      {{ addressDetails.supportingText }}
    </span>
  </md-list-item>
</template>
