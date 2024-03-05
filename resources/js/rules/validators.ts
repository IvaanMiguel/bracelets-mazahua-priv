import {
  helpers,
  email as _email,
  integer as _integer,
  maxLength as _maxLength,
  numeric as _numeric,
  required as _required,
} from '@vuelidate/validators'

export const required = () => {
  return helpers.withMessage('El campo es obligatorio.', _required)
}

export const numeric = () => {
  return helpers.withMessage('El campo debe ser numérico.', _numeric)
}

export const integer = () => {
  return helpers.withMessage('El campo debe ser un número entero.', _integer)
}

export const email = () => {
  return helpers.withMessage('El campo no es un email válido.', _email)
}

export const maxLength = (max: number) => {
  return helpers.withMessage(
    `El campo no debe tener más de ${max} caracteres.`,
    _maxLength(max)
  )
}

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

export const digits = (len: number) => {
  return helpers.withMessage(
    `El campo debe tener ${len} dígitos.`,
    (value: string) => !helpers.req(value) || value.length === len
  )
}

export const gte = (gte: number) => {
  return helpers.withMessage(
    `El valor del campo debe ser como mínimo ${gte}.`,
    (value: number) => !helpers.req(value) || value >= gte
  )
}

export const notRegex = (regex: RegExp) => {
  return helpers.withMessage(
    'El formato del campo no es válido.',
    (value: string) => !helpers.req(value) || !regex.test(value)
  )
}
