<script setup lang="ts">
import { loginRules } from '@/rules/login'
import { useForm } from '@inertiajs/vue3'
import Form from '../Form.vue'
import TextField from '../TextField.vue'
import Icon from '../Icon.vue'
import { computed, ref } from 'vue'

const form = useForm({
  email: '',
  password: '',
})

const showPassword = ref(false)
const fieldType = computed(() => (showPassword.value ? 'text' : 'password'))

const toggleVisibility = () => (showPassword.value = !showPassword.value)

defineExpose({ form })
</script>

<template>
  <Form
    :inertia-form="form"
    :rules="loginRules"
  >
    <TextField
      required
      autofocus
      autocomplete="username"
      label="Email"
      :error="form.errors.email"
      :error-text="form.errors.email"
      v-model="form.email"
    >
      <Icon slot="leading-icon">email</Icon>
    </TextField>
    <TextField
      id="password-field"
      required
      minlength="4"
      maxlength="255"
      autocomplete="current-password"
      label="Contraseña"
      no-error-icon
      :type="fieldType"
      :error="form.errors.password"
      :error-text="form.errors.password"
      v-model="form.password"
    >
      <Icon slot="leading-icon">password</Icon>
      <md-icon-button
        slot="trailing-icon"
        type="button"
        @click="toggleVisibility"
        toggle
      >
        <Icon>visibility</Icon>
        <Icon slot="selected">visibility_off</Icon>
      </md-icon-button>
    </TextField>
  </Form>
</template>
