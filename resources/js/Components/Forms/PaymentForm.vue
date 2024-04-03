<script setup lang="ts">
import { paymentRules } from '@/rules/payment'
import { Customer, Payment } from '@/types/orders'
import { useForm } from '@inertiajs/vue3'
import '@material/web/iconbutton/icon-button'
import '@material/web/select/outlined-select'
import '@material/web/select/select-option'
import Form from '../Form.vue'
import Icon from '../Icon.vue'
import TextField from '../TextField.vue'

const props = defineProps<{
  paymentTypes: { id: number; name: string }[]
  customerName?: Customer | null
}>()

const form = useForm<Payment>({
  payment_type_id: props.paymentTypes[0].id,
  name: '',
  cardNumber: '',
  clabe: ''
})

const onClick = () => {
  form.name = `${props.customerName?.name} ${props.customerName?.last_name}`
}

defineExpose({ form })
</script>

<template>
  <Form
    :inertia-form="form"
    :rules="paymentRules"
  >
    <div class="flex">
      <span class="text-on-background me-4">
        <Icon>payments</Icon>
      </span>
      <md-outlined-select
        class="w-full"
        required
        label="Tipo de pago"
        v-model="form.payment_type_id"
      >
        <md-select-option
          v-for="paymentType in paymentTypes"
          :value="paymentType.id"
        >
          {{ paymentType.name }}
        </md-select-option>
      </md-outlined-select>
    </div>
    <TextField
      v-if="form.payment_type_id === 2"
      class="md:row-start-2"
      required
      label="CLABE de cuenta"
      maxlength="18"
      :error="form.errors.clabe"
      v-model="form.clabe"
    >
      <template #floating-icon>
        <Icon>account_balance_wallet</Icon>
      </template>
    </TextField>
    <template v-if="form.payment_type_id === 3">
      <TextField
        class="md:row-start-2"
        required
        label="Titular"
        maxlength="255"
        no-error-icon
        :error="form.errors.name"
        v-model="form.name"
      >
        <md-icon-button
          slot="trailing-icon"
          title="Usar nombre del cliente."
          type="button"
          @click="onClick"
        >
          <Icon>metabolism</Icon>
        </md-icon-button>
        <template #floating-icon>
          <Icon>id_card</Icon>
        </template>
      </TextField>
      <TextField
        class="md:row-start-2"
        required
        label="Número"
        maxlength="16"
        :error="form.errors.cardNumber"
        v-model="form.cardNumber"
      >
        <template #floating-icon>
          <Icon>credit_card</Icon>
        </template>
      </TextField>
    </template>
  </Form>
</template>
