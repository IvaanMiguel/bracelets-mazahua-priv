<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

import { useModal } from '@/composables/useModal'

import { watchEffect } from 'vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import TextField from '@/Components/TextField.vue'
import { Address } from '@/types/customers'

const props = withDefaults(
  defineProps<{
    addresses: (Address & { id: number })[]
    selectedAddress: number | null
  }>(),
  {}
)

const form = useForm<Address>({
  main_street: '',
  cross_streets: '',
  neighborhood: '',
  postal_code: '',
  street_number: '',
  suite_number: '',
})

const { modal } = useModal('#edit-address-modal')

watchEffect(() => {
})

</script>

<template>
  <Modal id="edit-address-modal" not-cancellable>
    <div slot="headline">Editar dirección agregada</div>
    <div slot="content">
      <div class="mb-6 flex flex-col gap-6">
        <TextField
          class="w-full flex-1"
          label="Calle principal"
          :placeholder="addresses[Number(selectedAddress)]?.main_street"
          v-model="form.main_street"
        >
          <template #floating-icon>
            <Icon>location_home</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-1"
          label="Calles adyacentes"
          :placeholder="addresses[Number(selectedAddress)]?.cross_streets"
          v-model="form.cross_streets"
        >
          <template #floating-icon>
            <Icon>alt_route</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-[3]"
          label="Colonia"
          :placeholder="addresses[Number(selectedAddress)]?.neighborhood"
          v-model="form.neighborhood"
        >
          <template #floating-icon>
            <Icon>warehouse</Icon>
          </template>
        </TextField>
        <TextField
          class="w-full flex-[2]"
          label="Código postal"
          :placeholder="addresses[Number(selectedAddress)]?.postal_code"
          v-model="form.postal_code"
        >
          <template #floating-icon>
            <Icon>local_post_office</Icon>
          </template>
        </TextField>
        <div class="flex w-full flex-1 items-start gap-6">
          <TextField
            class="w-full flex-1"
            label="Número Exterior"
            :placeholder="addresses[Number(selectedAddress)]?.street_number"
            v-model="form.street_number"
          >
            <template #floating-icon>
              <Icon>house</Icon>
            </template>
          </TextField>
          <TextField
            class="w-full flex-1"
            label="Número interior"
            :placeholder="addresses[Number(selectedAddress)]?.suite_number"
            v-model="form.suite_number"
          >
            <template #floating-icon>
              <Icon>apartment</Icon>
            </template>
          </TextField>
        </div>
      </div>
    </div>
    <div slot="actions">
      <md-text-button @click="modal?.close">Cancelar</md-text-button>
      <md-text-button @click="">Guardar</md-text-button>
    </div>
  </Modal>

  <Modal>
    <div slot="headline">Cancelar edición</div>
  </Modal>
</template>
