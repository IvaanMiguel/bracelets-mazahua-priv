<script setup lang="ts">
import ColorMode from '@/Components/ColorMode.vue'
import LoginForm from '@/Components/Forms/LoginForm.vue'
import '@material/web/elevation/elevation'
import '@material/web/iconbutton/icon-button'
import '@material/web/textfield/outlined-text-field'
import useVuelidate from '@vuelidate/core'
import { ref } from 'vue'

const v = useVuelidate()

const loginForm = ref<InstanceType<typeof LoginForm>>()

const login = async () => {
  const validate = await v.value.$validate()

  if (!validate) return

  loginForm.value?.form.post(route('login'), {
    onFinish: () => loginForm.value?.form.reset('password'),
  })
}
</script>

<template>
  <div class="grid min-h-svh place-items-center">
    <div
      class="md-elevation-4 relative w-3/4 max-w-md rounded-lg border-[1px] border-light-outline bg-light-surface-container-highest p-6 dark:border-dark-outline dark:bg-dark-surface-container"
    >
      <div class="text-end">
        <ColorMode />
      </div>
      <h1
        class="mb-6 text-center text-2xl text-light-on-background dark:text-dark-on-background"
      >
        Inicia sesión
      </h1>

      <LoginForm
        ref="loginForm"
        class="flex flex-col gap-6"
        :submit="login"
      />

      <div class="mt-6 text-center">
        <md-filled-button
          @click="login"
          :disabled="loginForm?.form.processing"
        >
          Iniciar sesión
        </md-filled-button>
      </div>
      <md-elevation />
    </div>
  </div>
</template>

<style scoped>
#password-field {
  --md-focus-ring-active-width: 4px;
  --md-focus-ring-width: 2px;
  --md-focus-ring-outward-offset: -2px;
}
</style>
