<template>
  <AppLayout title="Mes Réservations">
    <!-- Navigation flottante améliorée -->
   <div class="fixed top-4 left-4 z-40">
  <button 
    @click="goBack"
    class="group flex items-center justify-center w-14 h-14 bg-gradient-to-br from-gray-800 to-gray-900 rounded-full shadow-[inset_2px_2px_5px_rgba(0,0,0,0.3),inset_-2px_-2px_5px_rgba(255,255,255,0.05)] hover:shadow-[inset_3px_3px_7px_rgba(0,0,0,0.4),inset_-3px_-3px_7px_rgba(255,255,255,0.1)] transition-all duration-500 transform hover:scale-105"
  >
    <svg class="w-6 h-6 text-cyan-400 transform group-hover:-translate-x-0.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
    </svg>
  </button>
</div>
   
    <template #header>
      <div class="text-center">
        <h2 class="text-4xl font-bold bg-gradient-to-r from-emerald-300 via-cyan-300 to-blue-300 bg-clip-text text-transparent animate-pulse-slow">
          Mes Réservations
        </h2>
        <p class="text-gray-300 mt-2 text-sm">Gérez toutes vos activités programmées</p>
      </div>
    </template>

    <div class="py-6 bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 min-h-screen">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Message flash premium -->
        <div
          v-if="$page.props.flash.message"
          class="mb-8 p-4 rounded-2xl border backdrop-blur-sm animate-fade-in"
          :class="{
            'bg-gradient-to-r from-emerald-900/50 to-green-900/50 border-emerald-500/30 text-emerald-200 shadow-lg shadow-emerald-500/10': $page.props.flash.type === 'success',
            'bg-gradient-to-r from-rose-900/50 to-red-900/50 border-rose-500/30 text-rose-200 shadow-lg shadow-rose-500/10': $page.props.flash.type === 'error'
          }"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-2 h-2 rounded-full animate-ping" :class="$page.props.flash.type === 'success' ? 'bg-emerald-400' : 'bg-rose-400'"></div>
              <p class="font-medium">{{ $page.props.flash.message }}</p>
            </div>
            <button @click="$page.props.flash.message = null" class="text-lg hover:text-white transition-colors">&times;</button>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
          <div class="bg-gray-900/70 backdrop-blur-sm rounded-2xl p-4 border border-emerald-500/20 shadow-lg">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-emerald-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">{{ upcomingCount }}</p>
                <p class="text-xs text-gray-400">À venir</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-900/70 backdrop-blur-sm rounded-2xl p-4 border border-blue-500/20 shadow-lg">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-blue-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">{{ completedCount }}</p>
                <p class="text-xs text-gray-400">Terminés</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-900/70 backdrop-blur-sm rounded-2xl p-4 border border-cyan-500/20 shadow-lg">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-cyan-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">{{ totalCount }}</p>
                <p class="text-xs text-gray-400">Total</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des réservations premium -->
        <div class="space-y-4">
          <div
            v-for="reservation in reservations.data"
            :key="reservation.id"
            class="group bg-gradient-to-br from-gray-900/80 to-gray-950/80 backdrop-blur-sm border border-gray-700/70 rounded-2xl p-6 shadow-xl hover:shadow-2xl hover:border-emerald-500/40 transition-all duration-500 transform hover:-translate-y-1"
          >
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
              <div class="flex-1">
                <!-- En-tête avec badge -->
                <div class="flex items-start justify-between mb-4">
                  <div>
                    <h3 class="text-xl font-bold text-white group-hover:text-emerald-300 transition-colors">
                      {{ reservation.course?.name || 'Cours supprimé' }}
                    </h3>
                    <div class="flex items-center space-x-2 mt-2">
                      <span class="px-3 py-1 text-xs rounded-full font-medium backdrop-blur-sm border"
                        :class="{
                          'bg-emerald-500/30 text-emerald-300 border-emerald-500/40': new Date(reservation.course?.start_time) > new Date(),
                          'bg-gray-800/70 text-gray-400 border-gray-600/70': new Date(reservation.course?.start_time) <= new Date()
                        }"
                      >
                        {{ new Date(reservation.course?.start_time) > new Date() ? '🟢 À venir' : '⚫ Terminé' }}
                      </span>
                      
                    </div>
                  </div>
                  
                  <!-- Date en cercle -->
                  <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-600/30 to-blue-600/30 rounded-full border border-emerald-500/40 flex flex-col items-center justify-center shadow-lg">
                      <span class="text-white font-bold text-lg">
                        {{ reservation.course?.start_time ? new Date(reservation.course.start_time).getDate() : '?' }}
                      </span>
                      <span class="text-emerald-300 text-xs uppercase">
                        {{ reservation.course?.start_time ? new Date(reservation.course.start_time).toLocaleDateString('fr-FR', {month: 'short'}) : '???' }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Détails du cours -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                  <div class="space-y-2">
                    <div class="flex items-center space-x-3 text-gray-300">
                      <div class="w-8 h-8 bg-emerald-500/30 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                      </div>
                      <span>{{ reservation.course?.coach_name || 'Coach non spécifié' }}</span>
                    </div>
                    
                    <div class="flex items-center space-x-3 text-gray-300">
                      <div class="w-8 h-8 bg-blue-500/30 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                      </div>
                      <span>
                        {{ reservation.course?.start_time ? formatTime(reservation.course.start_time) : '?' }} - 
                        {{ reservation.course?.end_time ? formatTime(reservation.course.end_time) : '?' }}
                      </span>
                    </div>
                  </div>

                  <div class="space-y-2">
                    
                    
                    <div class="flex items-center space-x-3 text-gray-300">
                      <div class="w-8 h-8 bg-cyan-500/30 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                      </div>
                      <span>Réservé le {{ formatDate(reservation.created_at) }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- NOUVEAU BOUTON ANNULER AMÉLIORÉ -->
              <div class="lg:text-right" v-if="reservation.course && new Date(reservation.course.start_time) > new Date()">
  <button
    @click="openCancelModal(reservation.id)"
    class="group relative px-6 py-3 bg-gradient-to-br from-gray-800 to-gray-900 text-rose-400 rounded-xl font-semibold transition-all duration-500 shadow-[inset_2px_2px_5px_rgba(0,0,0,0.3),inset_-2px_-2px_5px_rgba(255,255,255,0.05)] hover:shadow-[inset_3px_3px_7px_rgba(0,0,0,0.4),inset_-3px_-3px_7px_rgba(255,255,255,0.1)] hover:text-rose-300 border border-gray-700/50 hover:border-rose-500/30"
  >
    <span class="relative z-10 flex items-center space-x-2">
      <svg class="w-4 h-4 transform group-hover:rotate-180 transition-transform duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
      </svg>
      <span class="group-hover:tracking-wider transition-all duration-300">Supprimer</span>
    </span>
  </button>
</div>
            </div>
          </div>
        </div>

        <!-- État vide amélioré -->
        <div
          v-if="reservations.length === 0"
          class="text-center py-16"
        >
          <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-emerald-600/30 to-blue-600/30 rounded-full border border-emerald-500/40 flex items-center justify-center shadow-xl">
            <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-white mb-3">Aucune réservation</h3>
          <p class="text-gray-400 mb-6 max-w-md mx-auto">Vous n'avez pas encore réservé de cours. Découvrez notre sélection d'activités et réservez votre première session !</p>
          <Link
            href="/cours"
            class="inline-flex items-center space-x-3 px-6 py-3 bg-gradient-to-r from-emerald-600 to-blue-700 hover:from-emerald-500 hover:to-blue-600 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-emerald-500/30"
          >
            <span>Explorer les cours</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </Link>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation premium -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="showModal = false"></div>
  <div class="relative bg-gradient-to-br from-gray-900 to-gray-950 border border-rose-500/40 rounded-2xl shadow-2xl p-8 w-full max-w-md transform animate-modal-in">
    <div class="text-center mb-6">
      <div class="w-16 h-16 mx-auto mb-4 bg-rose-500/30 rounded-full border border-rose-500/40 flex items-center justify-center shadow-lg">
        <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-white mb-2">Confirmer l'annulation</h2>
      <p class="text-gray-300">Êtes-vous sûr de vouloir annuler cette réservation ? Cette action est irréversible.</p>
    </div>
    <div class="flex justify-center space-x-4">
      <button 
        @click="showModal = false" 
        class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 border border-gray-600/50"
      >
        Retour
      </button>
      <button 
        @click="confirmCancel" 
        class="px-6 py-3 bg-gradient-to-br from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg shadow-rose-500/30 border border-rose-400/30 hover:border-rose-300/50"
      >
        Confirmer l'annulation
      </button>
    </div>
  </div>
</div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  reservations: {
    type: Array,
    default: () => [],
    required: true
  }
});

// Computed properties pour les statistiques
const upcomingCount = computed(() => {
  return props.reservations.data?.filter(r => new Date(r.course?.start_time) > new Date()).length || 0;
});

const completedCount = computed(() => {
  return props.reservations.data?.filter(r => new Date(r.course?.start_time) <= new Date()).length || 0;
});

const totalCount = computed(() => {
  return props.reservations.data?.length || 0;
});

const goBack = () => {
  router.visit(route('client.dashboard'));
};

const showModal = ref(false);
const selectedReservationId = ref(null);

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const openCancelModal = (reservationId) => {
  selectedReservationId.value = reservationId;
  showModal.value = true;
};

const confirmCancel = () => {
  if (!selectedReservationId.value) return;
  
  router.delete(route('reservations.destroy', selectedReservationId.value), {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false;
    },
    onError: () => {
      alert("Erreur lors de l'annulation de la réservation.");
      showModal.value = false;
    },
  });
};
</script>

<style scoped>
.animate-pulse-slow {
  animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

.animate-modal-in {
  animation: modalIn 0.3s ease-out;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.9) translateY(-20px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: rgba(17, 24, 39, 0.8);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #10b981, #3b82f6);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #34d399, #60a5fa);
}
</style>