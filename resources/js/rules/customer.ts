import {
  betweenLength,
  digits,
  email,
  maxLength,
  notRegex,
  numeric,
  required,
} from './validators'

export const customerRules = {
  name: {
    required: required(),
    betweenLength: betweenLength(3, 100),
    notRegex: notRegex(/\d/),
  },
  last_name: {
    required: required(),
    betweenLength: betweenLength(3, 100),
    notRegex: notRegex(/\d/),
  },
  phone_number: {
    required: required(),
    numeric: numeric(),
    digits: digits(10),
  },
  email: {
    email: email(),
    maxLength: maxLength(255),
  },
}
