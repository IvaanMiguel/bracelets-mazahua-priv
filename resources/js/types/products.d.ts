import { IdCategory } from './categories'

export interface ProductListItem {
  id: number,
  name: string,
  category_id: number,
  price: number,
  stock: number,
  category: IdCategory
}

export interface Product {
  name: string,
  category_id?: number,
  price?: number,
  stock?: number
}
