export type CategoryListItem = IdCategory & {
  stock: number
}
export type IdCategory = Category & {
  id: number
}

export interface Category {
  name: string
}
