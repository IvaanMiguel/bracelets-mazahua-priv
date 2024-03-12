<script setup lang="ts">
import { customerRules } from '@/rules/customer'
import { Customer } from '@/types/customers'
import { useForm } from '@inertiajs/vue3'
import DatePicker from '../DatePicker.vue'
import Form from '../Form.vue'
import Icon from '../Icon.vue'
import TextField from '../TextField.vue'

const props = defineProps<{
  defaults?: Customer
  datePickerConfig?: Object
}>()

const form = useForm<Customer>({
  name: props.defaults?.name || '',
  last_name: props.defaults?.last_name || '',
  phone_number: props.defaults?.phone_number || '',
  email: props.defaults?.email || '',
  birth_date: props.defaults?.birth_date || '',
})

defineExpose({ form })
</script>

<template>
  <Form
    :inertia-form="form"
    :rules="customerRules"
  >
    <TextField
      label="Nombre(s)"
      required
      minlength="1"
      maxlength="100"
      :error="form.errors.name"
      v-model="form.name"
    >
      <template #floating-icon>
        <Icon>person</Icon>
      </template>
    </TextField>
    <TextField
      label="Apellido(s)"
      required
      minlength="1"
      maxlength="100"
      empty-floating-icon
      :error="form.errors.last_name"
      v-model="form.last_name"
    />
    <TextField
      label="Número celular"
      type="tel"
      required
      minlength="10"
      maxlength="10"
      :error="form.errors.phone_number"
      v-model="form.phone_number"
    >
      <template #floating-icon>
        <Icon>phone</Icon>
      </template>
    </TextField>
    <TextField
      label="Email"
      type="email"
      maxlength="255"
      :error="form.errors.email"
      v-model="form.email"
    >
      <template #floating-icon>
        <Icon>alternate_email</Icon>
      </template>
    </TextField>
    <DatePicker
      v-model="form.birth_date"
      :error="form.errors.birth_date"
      v-bind="datePickerConfig"
      :max-date="new Date()"
    />
  </Form>
</template>
