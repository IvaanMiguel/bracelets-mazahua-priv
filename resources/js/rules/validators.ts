import { helpers } from '@vuelidate/validators'

export const betweenLength = (min: number, max: number) => {
  return helpers.withMessage(
    `El campo debe tener entre ${min} y ${max} caracteres.`,
    (value: string) =>
      !helpers.req(value) || (value.length >= min && value.length <= max)
  )
}

export const length = (len: number) => {
  return helpers.withMessage(
    `El campo debe tener ${len} caracteres.`,
    (value: string) => !helpers.req(value) || value.length === len
  )
}
