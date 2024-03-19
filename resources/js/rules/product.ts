import {
  betweenLength,
  betweenNumeric,
  decimalCount,
  integer,
  numeric,
  required,
} from './validators'

export const productRules = {
  name: {
    required: required(),
    betweenLength: betweenLength(3, 255),
  },
  category_id: {
    required: required(),
    numeric: numeric(),
  },
  price: {
    required: required(),
    betweenNumeric: betweenNumeric(1, 9999.99),
    decimalCount: decimalCount(0, 2),
  },
  stock: {
    numeric: numeric(),
    integer: integer(),
    betweenNumeric: betweenNumeric(0, 999),
  },
}
