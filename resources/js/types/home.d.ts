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
