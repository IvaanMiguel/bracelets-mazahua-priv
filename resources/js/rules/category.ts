import { betweenLength, required } from "./validators";

export const categoryRules = {
  name: {
    required: required(),
    betweenLength: betweenLength(3, 255)
  }
}
