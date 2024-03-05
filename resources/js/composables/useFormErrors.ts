import { InertiaForm, usePage } from '@inertiajs/vue3'
import useVuelidate, { GlobalConfig, ValidationArgs } from '@vuelidate/core'
import { watch } from 'vue'

export function useFormErrors<
  T extends { [key in keyof ValidationArgs]: any },
  F extends { [key in keyof T]: any },
>(rules: T, form: InertiaForm<F>, globalConfig?: GlobalConfig) {
  const v$ = useVuelidate(rules, form, globalConfig)
  const page = usePage()

  watch(() => v$.value.$errors, () => {
    for (const key in form.data()) {
      form.setError(key, v$.value[key]?.$errors[0]?.$message)
    }
  })

  watch(() => page.props.errors, () => {
    for (const key in form.data()) {
      form.setError(key, page.props.errors[key])
    }
  })

  return { v$, page }
}
