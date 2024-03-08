import {
  betweenLength,
  digits,
  gte,
  integer,
  numeric,
  required,
} from './validators'

export const addressRules = {
  main_street: {
    required: required(),
    lengthBetween: betweenLength(3, 255),
  },
  cross_streets: {
    lengthBetween: betweenLength(3, 255),
  },
  neighborhood: {
    required: required(),
    lengthBetween: betweenLength(3, 255),
  },
  postal_code: {
    required: required(),
    numeric: numeric(),
    integer: integer(),
    gte: gte(0),
    digits: digits(5),
  },
  street_number: {
    numeric: numeric(),
    integer: integer(),
    gte: gte(0),
  },
  suite_number: {
    numeric: numeric(),
    integer: integer(),
    gte: gte(0),
  },
}
