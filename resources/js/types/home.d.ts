export interface CompletedOrder {
  name: string
  total: number
}

export interface SalesPerCategory {
  id: number
  name: string
  total_sales: string | number
}

export interface BestSeller {
  product_id: number
  name: string
  total_sales: string | number
  units: string | number
}

export interface TopCustomer {
  customer_id: number
  name: string
  total: string | number
}

export interface PopularPaymentType {
  payment_type_id: number
  name: string
}

export interface CustomerOrders {
  customer_id: number
  name: string
  total_orders: string | number
  completed_orders: string | number
}
