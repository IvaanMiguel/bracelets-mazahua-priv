<script setup lang="ts">
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/textfield/outlined-text-field'
import Icon from '@/Components/Icon.vue'
import { computed, ref } from 'vue'
import ColorMode from '@/Layouts/Partials/ColorMode.vue'
import { useForm } from '@inertiajs/vue3'
import Form from '@/Components/Form.vue'

const showPassword = ref(false)
const fieldType = computed(() => (showPassword.value ? 'text' : 'password'))

const form = useForm({
  email: '',
  password: '',
})

const toggleVisibility = () => (showPassword.value = !showPassword.value)

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <div class="grid min-h-svh place-items-center">
    <div
      id="form-container"
      class="relative w-3/4 max-w-md rounded-lg p-6"
    >
      <div class="text-end">
        <ColorMode />
      </div>
      <h1 class="mb-6 text-center text-2xl">Inicia sesión</h1>
      <Form
        class="flex flex-col gap-6"
        :submit="submit"
      >
        <md-outlined-text-field
          autofocus
          autocomplete="username"
          :error="form.errors.email"
          :error-text="form.errors.email"
          v-model="form.email"
          label="Email"
        >
          <Icon slot="leading-icon">email</Icon>
          <Icon
            v-if="form.errors.email"
            slot="trailing-icon"
          >
            error
          </Icon>
        </md-outlined-text-field>

        <md-outlined-text-field
          id="password-field"
          :type="fieldType"
          autocomplete="current-password"
          :error="form.errors.password"
          :error-text="form.errors.password"
          v-model="form.password"
          label="Contraseña"
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
        </md-outlined-text-field>

        <md-filled-button
          class="mx-auto w-fit"
          :disabled="form.processing"
        >
          Iniciar sesión
        </md-filled-button>
      </Form>
      <md-elevation />
    </div>
  </div>
</template>

<style scoped>
#form-container {
  --md-elevation-level: 3;
}
#password-field {
  --md-sys-color-surface-container-highest: theme(colors.neutral.90);
  --md-focus-ring-active-width: 4px;
  --md-focus-ring-width: 2px;
  --md-focus-ring-outward-offset: -2px;
}
</style>
