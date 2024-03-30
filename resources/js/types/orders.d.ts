interface Customer {
  id: number
  name: string
  last_name: string
}

interface Delivery {
  id: number
  date: string
  delivery_type_id: number
  delivery_type: {
    id: number
    name: string
  }
}

interface PaymentType {
  id: number
  name: string
}

export interface OrderListItem {
  customer_id: number
  customer: Customer
  delivery_id: number
  delivery: Delivery 
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
