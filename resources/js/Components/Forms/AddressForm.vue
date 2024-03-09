<script setup lang="ts">
import { addressRules } from '@/rules/address'
import { Address } from '@/types/customers'
import { useForm } from '@inertiajs/vue3'
import Form from '../Form.vue'
import Icon from '../Icon.vue'
import TextField from '../TextField.vue'

const form = useForm<Address>({
  main_street: '',
  cross_streets: '',
  neighborhood: '',
  postal_code: '',
  street_number: '',
  suite_number: '',
})

defineExpose({ form })
</script>

<template>
  <Form
    :inertia-form="form"
    :rules="addressRules"
  >
    <TextField
      label="Calle principal"
      required
      minlength="3"
      maxlength="255"
      :error="form.errors.main_street"
      v-model="form.main_street"
    >
      <template #floating-icon>
        <Icon>location_home</Icon>
      </template>
    </TextField>
    <TextField
      label="Calles adyacentes"
      minlength="3"
      maxlength="255"
      :error="form.errors.cross_streets"
      v-model="form.cross_streets"
    >
      <template #floating-icon>
        <Icon>alt_route</Icon>
      </template>
    </TextField>
    <TextField
      label="Colonia"
      required
      minlength="3"
      maxlength="255"
      :error="form.errors.neighborhood"
      v-model="form.neighborhood"
    >
      <template #floating-icon>
        <Icon>warehouse</Icon>
      </template>
    </TextField>
    <TextField
      label="Código postal"
      required
      min="0"
      minlength="5"
      maxlength="5"
      :error="form.errors.postal_code"
      v-model="form.postal_code"
    >
      <template #floating-icon>
        <Icon>local_post_office</Icon>
      </template>
    </TextField>
    <div class="flex items-start gap-4">
      <TextField
        class="flex-1"
        label="Número Exterior"
        min="0"
        :error="form.errors.street_number"
        v-model="form.street_number"
      >
        <template #floating-icon>
          <Icon>house</Icon>
        </template>
      </TextField>
      <TextField
        class="flex-1"
        label="Número interior"
        min="0"
        :error="form.errors.suite_number"
        v-model="form.suite_number"
      >
        <template #floating-icon>
          <Icon>apartment</Icon>
        </template>
      </TextField>
    </div>
  </Form>
</template>
