<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
    <div class="bg-gray-900 border border-emerald-500 p-6 rounded-xl w-full max-w-md">
<h2 class="text-xl font-bold text-white mb-4">Paiement - {{ payment.course.name }}</h2>

      <form @submit.prevent="handleSubmit">
        <div id="card-element" class="bg-gray-800 p-3 rounded mb-4"></div>

        <button
          type="submit"
          :disabled="loading"
          class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded w-full"
        >
          <span v-if="loading">Paiement...</span>
          <span v-else>Payer {{ payment.amount }} TND</span>
        </button>
      </form>

      <button @click="$emit('close')" class="mt-4 text-sm text-gray-400 hover:text-white">Annuler</button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { loadStripe } from '@stripe/stripe-js'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  payment: Object,
  clientSecret: String,
})

const loading = ref(false)
let stripe = null
let elements = null
let cardElement = null

onMounted(async () => {
  stripe = await loadStripe('pk_test_XXXX') // Ta clé publique Stripe
  elements = stripe.elements()
  cardElement = elements.create('card')
  cardElement.mount('#card-element')
})

const handleSubmit = async () => {
  loading.value = true

  const { error, setupIntent } = await stripe.confirmCardSetup(props.clientSecret, {
    payment_method: {
      card: cardElement,
    }
  })

  if (error) {
    alert(error.message)
    loading.value = false
    return
  }

  // Envoie les données avec Inertia
  router.post('/payments/finalize', {
    payment_id: props.payment.id,
    payment_method: setupIntent.payment_method
  }, {
    preserveScroll: true,
    onSuccess: () => {
      loading.value = false
      location.reload()
    }
  })
}
</script>
