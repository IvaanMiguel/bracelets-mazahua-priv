<script setup lang="ts">
import DisplayField from '@/Components/DisplayField.vue'
import Icon from '@/Components/Icon.vue'
import { Customer } from '@/types/orders'

defineProps<{
  customerInfo: Customer & { email: string | null; phone_number: string }
}>()
</script>

<template>
  <div>
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
          <Icon>person</Icon>
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
</template>
