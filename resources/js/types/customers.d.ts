export interface CustomerListItem {
  id: number
  name: string
  last_name: string
}

export interface Customer {
  name: string,
  last_name: string
  birth_date: string | null
  email: string | null
  phone_number: string
}

export interface Address {
  main_street: string,
  cross_streets: string | null
  neighborhood: string
  street_number: string | null
  suite_number: string | null
  postal_code: string
}
