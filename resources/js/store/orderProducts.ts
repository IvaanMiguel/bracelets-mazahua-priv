import { AvailableChangedProduct, SelectedChangedProduct } from '@/types/orders'
import { reactive } from 'vue'

export const store = reactive<{
  products: {
    notChanged: AvailableChangedProduct[]
    changed: AvailableChangedProduct[]
  }
  updateOrderProducts: (products?: SelectedChangedProduct[]) => void
}>({
  products: { notChanged: [], changed: [] },

  updateOrderProducts(products?: SelectedChangedProduct[]) {
    const _defaultProducts: {
      notChanged: AvailableChangedProduct[]
      changed: AvailableChangedProduct[]
    } = { notChanged: [], changed: [] }

    for (const product of products || []) {
      const _product = {
        id: product.id,
        name: product.name,
        price: product.price,
        stock: product.stock,
        amount: product.amount,
        priceHasChanged: product.priceHasChanged,
      }

      if (product.priceHasChanged === false) {
        _defaultProducts.notChanged.push(_product)
      } else {
        _defaultProducts.changed.push(_product)
      }
    }

    this.products = _defaultProducts
  },
})
