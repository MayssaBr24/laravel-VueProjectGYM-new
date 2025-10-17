<!-- resources/js/Components/dashboardAdmin/PaymentsTab.vue -->
<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h3 class="text-2xl font-bold text-white">Historique des Paiements</h3>
      <div class="flex flex-col sm:flex-row gap-3">
        <button 
          @click="router.visit('/payments/new')"
          class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-semibold transition-all duration-300"
        >
          Nouveau Paiement
        </button>

        
        <a
          href="/admin/payments/export"
          target="_blank"
          class="px-6 py-3 bg-gray-700/50 hover:bg-gray-600/50 border border-gray-600 text-gray-300 rounded-xl text-sm font-semibold hover:border-gray-500 transition-all duration-300 backdrop-blur-sm"
        >
          Exporter
        </a>

      </div>
    </div>

    <div class="bg-gray-800/50 rounded-xl border border-gray-700/50 overflow-hidden backdrop-blur-sm">
      <ul class="divide-y divide-gray-700/50">
        <li v-for="payment in payments" :key="payment.id" class="px-6 py-4 hover:bg-gray-700/30 transition-colors duration-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="h-12 w-12 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V6a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-white">{{ payment.member_name || 'Membre inconnu' }}</p>
                <p class="text-sm text-gray-400">{{ payment.date || 'Date inconnue' }}</p>
              </div>
            </div>
            <div class="text-right">
              <span :class="getPaymentStatusBadgeClass(payment.status)" class="px-3 py-1 text-xs rounded-full font-semibold mb-2 inline-block">
                {{ payment.status || 'Statut inconnu' }}
              </span>
              <p class="text-xl font-bold text-white">{{ payment.amount || 0 }} TND</p>
            </div>
          </div>
        </li>
        <li v-if="payments.length === 0" class="px-6 py-8 text-center text-gray-400">
          <div class="flex flex-col items-center space-y-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
            <span>Aucun paiement trouvé</span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
defineProps({
  payments: {
    type: Array,
    default: () => []
  },
 
})

defineEmits(['filter-change', 'export'])

const getPaymentStatusBadgeClass = (status) => {
  const classes = {
    'succeeded': 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
    'pending': 'bg-red-500/20 text-red-400 border border-red-500/30',
    'en attente': 'bg-blue-500/20 text-blue-400 border border-blue-500/30'
  }
  return classes[status] || 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
}
</script>
