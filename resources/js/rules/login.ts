import { betweenLength, required } from './validators';

export const loginRules = {
  email: {
    required: required()
  },
  password: {
    required: required(),
    betweenLength: betweenLength(4, 255),
  }
}