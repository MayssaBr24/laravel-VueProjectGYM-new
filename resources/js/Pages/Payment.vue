<template>
  <div>
    <h1 class="text-2xl mb-4">Paiement</h1>
    <form @submit.prevent="submit">
      <div id="card-element" class="mb-4"></div>
      <button :disabled="processing" class="bg-blue-500 px-4 py-2 text-white rounded">
        Payer {{ amount }} TND
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { loadStripe } from '@stripe/stripe-js'

const props = defineProps({
  client_secret: String,
  amount: Number
})

const processing = ref(false)
let stripe = null
let elements = null

onMounted(async () => {
  stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
  elements = stripe.elements()
  const card = elements.create('card')
  card.mount('#card-element')
})

const submit = async () => {
  processing.value = true

  const { error, paymentMethod } = await stripe.createPaymentMethod({
    type: 'card',
    card: elements.getElement('card'),
  })

  if (error) {
    alert(error.message)
    processing.value = false
    return
  }

  await axios.post('/payment', {
    amount: props.amount,
    payment_method: paymentMethod.id,
  })

  window.location = '/dashboard'
}
</script>
