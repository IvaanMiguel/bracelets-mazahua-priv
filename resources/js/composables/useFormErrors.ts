import { Validation } from '@vuelidate/core'
import { Ref } from 'vue'

export function useFormErrors(validation: Ref<Validation>, form: any) {
  const setErrors = () => {
    for (const key in form.data()) {
      console.log(validation.value[key].$errors[0]?.$message)
      form.setError(key, validation.value[key].$errors[0]?.$message)
    }
  }

  return { setErrors }
}
