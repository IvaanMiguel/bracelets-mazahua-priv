import {
  integer,
  maxLength,
  minLength,
  minValue,
  numeric,
  required,
} from '@vuelidate/validators'

export const addressRules = {
  main_street: {
    required,
    minLength: minLength(3),
    maxLength: maxLength(255),
  },
  cross_streets: {
    minLength: minLength(3),
    maxLength: maxLength(255),
  },
  neighborhood: {
    required,
    minLength: minLength(3),
    maxLength: maxLength(255),
  },
  postal_code: {
    required,
    numeric,
    integer,
    minValue: minValue(1),
    minLength: minLength(5),
    maxLength: maxLength(5),
  },
  street_number: {
    numeric,
    integer,
    minValue: minValue(1),
    minLength: minLength(1),
  },
  suite_number: {
    numeric,
    integer,
    minValue: minValue(1),
    minLength: minLength(1),
  },
}
