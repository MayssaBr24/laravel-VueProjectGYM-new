<template>
  <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm">
    <div class="px-6 py-4 border-b border-gray-700/50 bg-gray-800/50 flex justify-between items-center">
      <h3 class="text-lg font-bold text-white">Mes Dernières Réservations</h3>
      <Link :href="route('reservations.my')" class="text-sm text-emerald-400 hover:text-emerald-300">Voir tout</Link>
    </div>

    <div class="divide-y divide-gray-700/50 max-h-96 overflow-y-auto">
      <div
        v-for="reservation in filteredReservations"
        :key="reservation.id"
        class="px-6 py-4 hover:bg-gray-700/30 transition-colors"
      >
        <div class="flex items-center justify-between">
          <div>
            <h4 class="text-white font-medium">{{ reservation.course?.name || 'Cours inconnu' }}</h4>
            <p v-if="reservation.course?.start_time" class="text-sm text-gray-400 mt-1">
              {{ formatDate(reservation.course.start_time) }} •
              {{ formatTime(reservation.course.start_time) }} - {{ formatTime(reservation.course.end_time) }}
            </p>
          </div>

          <div class="flex items-center space-x-3">
            <span
              v-if="reservation.course?.start_time"
              class="px-3 py-1 text-xs rounded-full font-semibold"
              :class="{
                'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30': new Date(reservation.course.start_time) > new Date(),
                'bg-gray-500/20 text-gray-400 border border-gray-500/30': new Date(reservation.course.start_time) <= new Date()
              }"
            >
              {{ new Date(reservation.course.start_time) > new Date() ? 'À venir' : 'Terminé' }}
            </span>

            <button
              v-if="new Date(reservation.course.start_time) > new Date()"
              @click="openModal(reservation.id)"
              class="text-xs text-red-400 hover:text-red-300 flex items-center"
              title="Annuler cette réservation"
            >
              <TrashIcon class="h-4 w-4 mr-1" />
              Annuler
            </button>
          </div>
        </div>
      </div>

      <div v-if="!filteredReservations.length" class="px-6 py-12 text-center text-gray-400">
        Vous n'avez aucune réservation récente.
      </div>
    </div>

    <!-- MODAL DE CONFIRMATION -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div class="bg-gray-800 text-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Confirmation</h2>
        <p>Êtes-vous sûr de vouloir annuler cette réservation ?</p>
        <div class="mt-6 flex justify-end space-x-4">
          <button @click="showModal = false" class="px-4 py-2 rounded bg-gray-600 hover:bg-gray-500 text-sm">Annuler</button>
          <button @click="cancelReservation" class="px-4 py-2 rounded bg-red-500 hover:bg-red-400 text-sm">Confirmer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { TrashIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  reservations: Array
})

const showModal = ref(false)
const selectedReservationId = ref(null)
const localReservations = ref([...props.reservations])

const filteredReservations = computed(() => {
  return localReservations.value.filter(reservation => {
    return reservation.course !== null // Filtrer les réservations avec cours null
  })
})

const openModal = (id) => {
  selectedReservationId.value = id
  showModal.value = true
}

const cancelReservation = () => {
  if (!selectedReservationId.value) return
  
  router.delete(route('reservations.destroy', selectedReservationId.value), {
    preserveScroll: true,
    onSuccess: () => {
      // Mettre à jour localement la liste des réservations
      localReservations.value = localReservations.value.filter(
        reservation => reservation.id !== selectedReservationId.value
      )
      showModal.value = false
      selectedReservationId.value = null
    },
    onError: () => {
      alert("Erreur lors de l'annulation de la réservation.")
      showModal.value = false
    },
  })
}

const formatDate = (date) =>
  new Date(date).toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long'
  })

const formatTime = (date) =>
  new Date(date).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  })
</script>