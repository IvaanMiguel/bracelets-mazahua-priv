import {
  betweenLength,
  digits,
  notRegex,
  numeric,
  required,
} from './validators'

export const paymentRules = {
  name: {
    required: required(),
    betweenLength: betweenLength(3, 255),
    notRegex: notRegex(/\d/),
  },
  cardNumber: {
    required: required(),
    numeric: numeric(),
    digits: digits(16),
  },
  clabe: {
    required: required(),
    numeric: numeric(),
    digits: digits(18),
  },
}
