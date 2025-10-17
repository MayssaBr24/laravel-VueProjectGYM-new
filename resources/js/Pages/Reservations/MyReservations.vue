<template>
  <AppLayout title="Mes Réservations">
    <!-- Flèche en haut à gauche -->
    <div class="fixed top-1 left-2 z-40">
      <button 
        @click="goBack"
        class="text-3xl  text-white hover:text-green-400 transition-colors"      >
        &lt; 
      </button>
    </div>
   
    <template #header>
      <h2 class="text-2xl font-bold bg-gradient-to-r from-green-400 to-teal-400 bg-clip-text text-transparent">
        Mes Réservations
      </h2>
    </template>

    <div class="py-6 bg-gray-900 min-h-screen">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Message flash amélioré -->
        <div
          v-if="$page.props.flash.message"
          class="mb-6 p-4 rounded-lg border"
          :class="{
            'bg-green-900/30 border-green-700 text-green-300': $page.props.flash.type === 'success',
            'bg-red-900/30 border-red-700 text-red-300': $page.props.flash.type === 'error'
          }"
        >
          <div class="flex items-center justify-between">
            <p>{{ $page.props.flash.message }}</p>
            <button @click="$page.props.flash.message = null" class="text-lg">&times;</button>
          </div>
        </div>

        <!-- Liste des réservations améliorée -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg overflow-hidden">
          <ul class="divide-y divide-gray-700/50">
            <li
              v-for="reservation in reservations.data"
              :key="reservation.id"
              class="px-6 py-5 hover:bg-gray-700/30 transition-colors"
            >
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                  <div class="flex items-start justify-between">
                    <h3 class="text-lg font-semibold text-green-400">
                      {{ reservation.course?.name || 'Cours supprimé' }}
                    </h3>
                    <span
                      class="px-2 py-1 text-xs rounded-full font-medium"
                      :class="{
                        'bg-green-900/30 text-green-400': new Date(reservation.course?.start_time) > new Date(),
                        'bg-gray-700 text-gray-400': new Date(reservation.course?.start_time) <= new Date()
                      }"
                    >
                      {{ new Date(reservation.course?.start_time) > new Date() ? 'À venir' : 'Terminé' }}
                    </span>
                  </div>
                  
                  <div class="mt-2 space-y-1.5 text-sm">
                    <p class="text-gray-400">
                      <span class="text-green-300">Coach:</span> 
                      {{ reservation.course?.coach_name || 'Non spécifié' }}
                    </p>
                    <p class="text-gray-400">
                      <span class="text-green-300">Date:</span> 
                      {{ reservation.course?.start_time ? formatDate(reservation.course.start_time) : 'Date inconnue' }}
                    </p>
                    <p class="text-gray-400">
                      <span class="text-green-300">Heure:</span> 
                      {{ reservation.course?.start_time ? formatTime(reservation.course.start_time) : '?' }} - 
                      {{ reservation.course?.end_time ? formatTime(reservation.course.end_time) : '?' }}
                    </p>
                  </div>
                </div>

                <button
                  v-if="reservation.course && new Date(reservation.course.start_time) > new Date()"
                  @click="openCancelModal(reservation.id)"
                  class="px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white rounded-lg font-medium transition-all duration-300 shadow-md"
                >
                  Annuler
                </button>
                
              </div>
            </li>
          </ul>

          <div
            v-if="reservations.length === 0"
            class="px-6 py-12 text-center"
          >
            <p class="text-gray-400 mb-3">Vous n'avez aucune réservation pour le moment.</p>
            <Link
              href="/cours"
              class="inline-flex items-center text-sm bg-gradient-to-r from-green-400 to-teal-400 bg-clip-text text-transparent hover:from-green-300 hover:to-teal-300 transition-all duration-300 font-medium"
            >
              <span>Voir les cours disponibles</span>
              &rarr; <!-- Flèche simple à droite -->
            </Link>
          </div>
        </div>
      </div>

      <!-- Modal de confirmation -->
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 text-white rounded-xl shadow-lg p-6 w-full max-w-md">
          <h2 class="text-lg font-bold mb-4">Confirmation</h2>
          <p>Êtes-vous sûr de vouloir annuler cette réservation ?</p>
          <div class="mt-6 flex justify-end space-x-4">
            <button @click="showModal = false" class="px-4 py-2 rounded bg-gray-600 hover:bg-gray-500 text-sm">Annuler</button>
            <button @click="confirmCancel" class="px-4 py-2 rounded bg-red-500 hover:bg-red-400 text-sm">Confirmer</button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  reservations: {
    type: Array,
    default: () => [],
    required: true
  }
});

const goBack = () => {
  router.visit(route('client.dashboard')); // Remplacez par votre route réelle
};

const showModal = ref(false);
const selectedReservationId = ref(null);

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long'
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
/* Style optionnel pour améliorer l'apparence des flèches */
button:hover {
  transform: scale(1.1);
  transition: transform 0.2s ease;
}
</style>