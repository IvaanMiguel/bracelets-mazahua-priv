import { InertiaForm } from '@inertiajs/vue3'
import { ValidationArgs } from '@vuelidate/core'
import useVuelidate from '@vuelidate/core'
import { watch } from 'vue'

export function useFormErrors<
  T extends { [key in keyof ValidationArgs]: any },
  F extends { [key in keyof T]: any },
>(
  rules: T,
  form: InertiaForm<F>
) {
  const v$ = useVuelidate(rules, form)

  watch(v$, () => {
    for (const key in form.data()) {
      form.setError(key, v$.value[key].$errors[0]?.$message)
    }
  })

  return { v$ }
}
