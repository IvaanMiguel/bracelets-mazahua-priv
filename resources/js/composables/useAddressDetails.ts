import { Address, IdAddress } from '@/types/customers'
import { ref } from 'vue'

export function useAddressDetails(address: IdAddress | Address) {
  const addressDetails = ref<{ headline: string; supportingText: string }>({
    headline: '',
    supportingText: '',
  })

  const {
    main_street,
    cross_streets,
    neighborhood,
    postal_code,
    street_number,
    suite_number,
  } = address

  addressDetails.value.headline = `Colonia ${neighborhood}, calle ${main_street}, C. P. ${postal_code}`

  const supportingText: (string | null)[] = [
    cross_streets,
    street_number ? `n. ext. ${street_number}` : null,
    suite_number ? `n. int. ${suite_number}` : null,
  ].filter(Boolean)

  addressDetails.value.supportingText = supportingText.join(', ')

  return addressDetails
}
