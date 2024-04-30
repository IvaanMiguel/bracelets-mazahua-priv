<script setup lang="ts">
import DisplayField from '@/Components/DisplayField.vue'
import Icon from '@/Components/Icon.vue'
import { Customer } from '@/types/orders'
import { computed } from 'vue'

const props = defineProps<{
  customerInfo: Customer & {
    email: string | null
    phone_number: string
    deleted_at?: string | null
  }
}>()

const border = computed(() => {
  return props.customerInfo.deleted_at
    ? 'border-light-error dark:border-dark-error border-2'
    : 'border-light-outline-variant dark:border-dark-outline-variant'
})
</script>

<template>
  <div>
    <div :class="['rounded-lg border p-4', border]">
      <h1 class="text-on-background mb-4 text-xl font-medium">
        Información del cliente
      </h1>
      <div class="grid gap-4 md:grid-cols-2">
        <DisplayField
          label="Nombre del cliente"
          :text="`${customerInfo.name} ${customerInfo.last_name}`"
        >
          <template #icon>
            <Icon>person</Icon>
          </template>
        </DisplayField>
        <DisplayField
          label="Número celular"
          :text="`${customerInfo.phone_number}`"
        >
          <template #icon>
            <Icon>phone</Icon>
          </template>
        </DisplayField>
        <DisplayField
          v-if="customerInfo.email"
          label="Email"
          :text="`${customerInfo.email}`"
        >
          <template #icon>
            <Icon>alternate_email</Icon>
          </template>
        </DisplayField>
      </div>
    </div>
    <template v-if="customerInfo.deleted_at">
      <div
        class="mt-2 flex items-center gap-1 text-light-error dark:text-dark-error"
      >
        <Icon size="1.25rem">report</Icon>
        <span class="text-xs">Este cliente ya ha sido eliminado.</span>
      </div>
    </template>
  </div>
</template>
