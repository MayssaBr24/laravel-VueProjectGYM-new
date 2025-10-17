<template> 
  <div class="bg-gray-800 rounded-2xl border border-gray-700/50 shadow-lg p-6">
    <h3 class="text-lg font-bold text-white mb-4">Paiements en attente</h3>

    <!-- Message de succès -->
    <div v-if="$page.props.flash.success" class="bg-emerald-600 text-white px-4 py-2 rounded mb-4">
      {{ $page.props.flash.success }}
    </div>

    <template v-if="payments.length === 0">
      <p class="text-gray-400 text-sm">Aucun paiement à effectuer pour le moment.</p>
    </template>

    <template v-else>
      <div
        v-for="p in payments"
        :key="p.id"
        class="bg-gray-900 p-4 rounded-xl mb-3 flex justify-between items-center"
      >
        <div>
          <p class="text-white font-semibold">{{ p.course.name }}</p>
          <p class="text-sm text-gray-400">Montant : {{ p.amount }} TND</p>
          <p class="text-xs text-gray-500 italic">
            {{ p.attendance_verified ? '✔ Présence vérifiée' : '⏳ En attente de présence' }}
          </p>
        </div>

        <div class="flex items-center">
          <button
            v-if="p.attendance_verified && !p.paid_at"
            @click="pay(p.id)"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded text-sm"
          >
            Payer maintenant
          </button>
         
        </div>
      </div>
    </template>

    <!-- Modal de paiement -->
    <CheckoutModal
      v-if="showModal"
      :payment="selectedPayment"
      :client-secret="clientSecret"
      @close="showModal = false"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import CheckoutModal from './CheckoutModal.vue'
import axios from 'axios'

const props = defineProps({
  payments: {
    type: Array,
    default: () => []
  }
})

const page = usePage()
const route = window.route // Ziggy

const showModal = ref(false)
const selectedPayment = ref(null)
const clientSecret = ref(null)

const pay = async (id) => {
  try {
    const response = await axios.post('/payments/prepare', { id })
    const data = response.data

    selectedPayment.value = data.payment
    clientSecret.value = data.client_secret
    showModal.value = true
  } catch (error) {
    console.error("Erreur lors de la préparation du paiement", error)
  }
}
</script>
