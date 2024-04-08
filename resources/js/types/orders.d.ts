import { IdAddress } from './customers'

interface Customer {
  id: number
  name: string
  last_name: string
}

interface PaymentType {
  id: number
  name: string
}

export interface OrderListItem {
  id: number
  customer_id: number
  customer: Customer
  delivery_id: number
  delivery: {
    id: number
    date: string
    delivery_type_id: number
    delivery_type: {
      id: number
      name: string
    }
  }
  completed: number
  payment_type_id: number
  payment_type: PaymentType
  total: number
}

export interface AvailableProduct {
  id: number
  name: string
  price: number
  stock: number
}

export type SelectedProduct = AvailableProduct & {
  amount: number
  subtotal: number
}

export interface Delivery {
  delivery_type_id: number
  delivery_app_id: number | null
  address_id: number | null
  date: string
  time: string
}

export interface Payment {
  payment_type_id: number
  name: string
  cardNumber: string
  clabe: string
  details: string | null
}

export interface Catalog {
  id: number
  name: string
}

export interface IdOrder {
  id: number
  advance: number
  completed: boolean
  customer: Customer & {
    email: string | null
    phone_number: string
  }
  customer_id: number
  delivery: Delivery & {
    delivery_app: Catalog | null
    delivery_type: Catalog
    address: IdAddress
  }
  delivery_id: number
  details: string | null
  payment_type: Catalog
  payment_type_id: number
  products: {
    name: string
    pivot: {
      order_id: number
      product_id: number
      amount: number
      subtotal: number
    }
    price: number
  }[]
  products_total: number
  total: number
}
