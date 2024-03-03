import {
  integer,
  minLength,
  minValue,
  numeric,
  required,
} from '@vuelidate/validators'
import { betweenLength, length } from './validators'

export const addressRules = {
  main_street: {
    required,
    lengthBetween: betweenLength(3, 100),
  },
  cross_streets: {
    lengthBetween: betweenLength(3, 255),
  },
  neighborhood: {
    required,
    lengthBetween: betweenLength(3, 255),
  },
  postal_code: {
    required,
    numeric,
    integer,
    minValue: minValue(0),
    length: length(5),
  },
  street_number: {
    numeric,
    integer,
    minValue: minValue(0),
  },
  suite_number: {
    numeric,
    integer,
    minValue: minValue(0),
  },
}
