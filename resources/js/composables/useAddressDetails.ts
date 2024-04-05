import { Address, IdAddress } from '@/types/customers'
import { Ref, ref, watchEffect } from 'vue'

export function useAddressDetails(
  address: Ref<IdAddress | Address | null | undefined>
) {
  const addressDetails = ref<{ headline: string; supportingText: string }>({
    headline: '',
    supportingText: '',
  })

  const createDetails = () => {
    if (!address.value) return

    const {
      main_street,
      cross_streets,
      neighborhood,
      postal_code,
      street_number,
      suite_number,
    } = address.value

    addressDetails.value.headline = `Colonia ${neighborhood}, calle ${main_street}, C. P. ${postal_code}`

    const supportingText: (string | null)[] = [
      cross_streets,
      street_number ? `n. ext. ${street_number}` : null,
      suite_number ? `n. int. ${suite_number}` : null,
    ].filter(Boolean)

    addressDetails.value.supportingText = supportingText.join(', ')
  }

  watchEffect(() => {
    createDetails()
  })

  return addressDetails
}
