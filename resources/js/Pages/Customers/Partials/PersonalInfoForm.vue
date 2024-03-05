<script setup lang="ts">
import DatePicker from '@/Components/DatePicker.vue'
import Form from '@/Components/Form.vue'
import Icon from '@/Components/Icon.vue'
import TextField from '@/Components/TextField.vue'
import { useFormErrors } from '@/composables/useFormErrors'
import { customerRules } from '@/rules/customer'
import { Customer } from '@/types/customers'
import { useForm } from '@inertiajs/vue3'

defineProps<{
  submit: (e?: Event) => void
}>()

const form = useForm<Customer>({
  name: '',
  last_name: '',
  birth_date: '',
  email: '',
  phone_number: '',
})
useFormErrors(customerRules, form, { $registerAs: 'personalInfo' })

defineExpose({
  form,
})
</script>

<template>
  <h1
    class="mb-4 text-xl font-medium text-light-on-background dark:text-dark-on-background"
  >
    Datos personales
  </h1>
  <Form
    class="flex flex-col"
    :submit="submit"
  >
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <TextField
        class="w-full flex-1"
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
        class="w-full flex-1"
        label="Apellido(s)"
        required
        minlength="1"
        maxlength="100"
        empty-floating-icon
        :error="form.errors.last_name"
        v-model="form.last_name"
      />
    </div>
    <div class="mb-6 flex flex-col items-start gap-6 md:flex-row">
      <TextField
        class="w-full flex-1"
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
        class="w-full flex-1"
        label="Email"
        type="email"
        maxlength="255"
        :error="form.errors.email"
        v-model="form.email"
      >
        <template #floating-icon>
          <Icon>email</Icon>
        </template>
      </TextField>
    </div>
    <div class="md:w-[calc(50%-(1.5rem/2))]">
      <DatePicker
        v-model="form.birth_date"
        :error="form.errors.birth_date"
      />
    </div>
  </Form>
</template>

<style>
.dp-custom-input {
  box-shadow: 0 0 6px #1976d2;
  color: #1976d2;
}
</style>
