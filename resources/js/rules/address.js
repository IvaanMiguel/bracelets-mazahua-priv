import {
  integer,
  maxLength,
  minLength,
  minValue,
  numeric,
  required,
} from '@vuelidate/validators'

export const editAddressRules = {
  main_street: {
    minLength: minLength(3),
    maxLength: maxLength(255),
  },
  cross_streets: {
    minLength: minLength(3),
    maxLength: maxLength(255),
  },
  neighborhood: {
    minLength: minLength(3),
    maxLength: maxLength(255),
  },
  postal_code: {
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

export const storeAddressRules = {
  main_street: {
    required,
    ...editAddressRules.main_street,
  },
  cross_streets: { ...editAddressRules.cross_streets },
  neighborhood: {
    required,
    ...editAddressRules.neighborhood,
  },
  postal_code: {
    required,
    ...editAddressRules.postal_code,
  },
  street_number: { ...editAddressRules.street_number },
  suite_number: { ...editAddressRules.suite },
}
