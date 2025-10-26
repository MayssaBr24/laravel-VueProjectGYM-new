<template>
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h3 class="text-2xl font-bold text-white">Historique des Paiements</h3>
        <p class="text-gray-400 text-sm mt-1">{{ payments.length }} paiement(s) au total</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3">
        <button 
          @click="router.visit('/payments/new')"
          class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white rounded-xl text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-emerald-500/25 flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Nouveau Paiement
        </button>

        <a
          href="/admin/payments/export"
          target="_blank"
          class="px-6 py-3 bg-gray-700/50 hover:bg-gray-600/50 border border-gray-600 text-gray-300 rounded-xl text-sm font-semibold hover:border-gray-500 transition-all duration-300 backdrop-blur-sm flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
          Exporter
        </a>
      </div>
    </div>

    <!-- Payments List Container -->
    <div class="bg-gray-800/50 rounded-2xl border border-gray-700/50 overflow-hidden backdrop-blur-sm shadow-2xl">
      <!-- Scrollable List -->
      <div class="max-h-96 overflow-y-auto custom-scrollbar">
        <ul class="divide-y divide-gray-700/50">
          <li 
            v-for="payment in payments" 
            :key="payment.id" 
            class="px-6 py-4 hover:bg-gray-700/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg group cursor-pointer"
            @click="handlePaymentClick(payment)"
          >
            <div class="flex items-center justify-between">
              <!-- Left Section - Member Info -->
              <div class="flex items-center space-x-4">
                <!-- Avatar with first letter -->
                <div 
                  class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg group-hover:shadow-emerald-500/25 transition-all duration-300 group-hover:scale-110"
                >
                  <span class="text-white font-bold text-lg">
                    {{ getInitials(payment.member_name || 'Membre inconnu') }}
                  </span>
                </div>
                
                <div class="space-y-1">
                  <p class="text-base font-semibold text-white group-hover:text-emerald-200 transition-colors">
                    {{ payment.member_name || 'Membre inconnu' }}
                  </p>
                  <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-400 flex items-center gap-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                      </svg>
                      {{ formatDate(payment.date)  }}
                    </p>
                    <span class="text-xs text-gray-500">•</span>
                    <p class="text-sm text-gray-400 flex items-center gap-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                      </svg>
                      {{ formatDate(payment.paid_at || payment.created_at) }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Right Section - Amount & Status -->
              <div class="text-right space-y-2">
                <span 
                  :class="getPaymentStatusBadgeClass(payment.status)" 
                  class="px-3 py-1.5 text-xs rounded-full font-semibold border transition-all duration-300 group-hover:scale-105"
                >
                  {{ getStatusText(payment.status) }}
                </span>
                
                <div class="space-y-1">
                  <p class="text-2xl font-bold text-white group-hover:text-emerald-200 transition-colors">
                    {{ formatAmount(payment.amount) }} TND
                  </p>
                  
                </div>
              </div>
            </div>

            <!-- Additional Info on Hover -->
            
          </li>

          <!-- Empty State -->
          <li v-if="payments.length === 0" class="px-6 py-12 text-center text-gray-400">
            <div class="flex flex-col items-center space-y-4">
              <div class="h-20 w-20 rounded-full bg-gray-700/50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
              </div>
              <div class="space-y-2">
                <p class="text-lg font-semibold">Aucun paiement trouvé</p>
                <p class="text-sm">Commencez par ajouter votre premier paiement</p>
              </div>
              <button 
                @click="router.visit('/payments/new')"
                class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-semibold transition-colors duration-300 mt-2"
              >
                Créer un paiement
              </button>
            </div>
          </li>
        </ul>
      </div>
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

defineEmits(['filter-change', 'export', 'payment-click'])

// Get initials from member name
const getInitials = (name) => {
  if (!name || name === 'Membre inconnu') return '?'
  return name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('')
}

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'Date inconnue'
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

// Format amount
const formatAmount = (amount) => {
  const numAmount = parseFloat(amount) || 0
  return new Intl.NumberFormat('fr-FR').format(numAmount)
}

// Get status display text
const getStatusText = (status) => {
  const statusMap = {
    'succeeded': 'Réussi',
    'pending': 'En attente',
    'failed': 'Échoué',
    'en attente': 'En attente'
  }
  return statusMap[status] || status || 'Inconnu'
}

// Status badge classes
const getPaymentStatusBadgeClass = (status) => {
  const classes = {
    'succeeded': 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
    'pending': 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
    'failed': 'bg-red-500/20 text-red-400 border-red-500/30',
    'en attente': 'bg-blue-500/20 text-blue-400 border-blue-500/30'
  }
  return classes[status] || 'bg-gray-500/20 text-gray-400 border-gray-500/30'
}

// Handle payment click
const handlePaymentClick = (payment) => {
  // You can emit an event or navigate to payment details
  console.log('Payment clicked:', payment)
  // Example: router.visit(`/payments/${payment.id}`)
}
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(75, 85, 99, 0.2);
  border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(16, 185, 129, 0.4);
  border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(16, 185, 129, 0.6);
}
</style>