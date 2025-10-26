<template>
<!-- Messages flash -->
  <div v-if="$page.props.flash.success && showFlash" class="fixed top-4 right-4 z-50">
    <div class="bg-green-500 text-white px-6 py-3 rounded-2xl shadow-lg flex items-center gap-3">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
      </svg>
      {{ $page.props.flash.success }}
      <button @click="hideFlash" class="text-white hover:text-gray-200">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>
  </div>

  <div v-if="$page.props.flash.error && showFlash" class="fixed top-4 right-4 z-50">
    <div class="bg-red-500 text-white px-6 py-3 rounded-2xl shadow-lg flex items-center gap-3">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
      </svg>
      {{ $page.props.flash.error }}
      <button @click="hideFlash" class="text-white hover:text-gray-200">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>
  </div>
  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-900 to-purple-900 py-6 px-4 font-sans">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <header class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="text-center md:text-left">
            <h1 class="text-3xl md:text-5xl font-bold mb-2">
              <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent">
                MON ESPACE FITNESS
              </span>
            </h1>
            <p class="text-gray-300 text-sm md:text-base">Suivez vos cours et votre progression</p>
          </div>
          
          <!-- Navigation -->
          <nav class="flex justify-center md:justify-end">
            <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl p-1.5 flex gap-1">
              <button 
                @click="setActiveTab('calendar')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeTab === 'calendar' 
                  ? 'bg-blue-500/20 text-blue-300 shadow-lg shadow-blue-500/10' 
                  : 'text-gray-400 hover:text-gray-200'"
              >
                Calendrier
              </button>
              <button 
                @click="setActiveTab('mySessions')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeTab === 'mySessions' 
                  ? 'bg-purple-500/20 text-purple-300 shadow-lg shadow-purple-500/10' 
                  : 'text-gray-400 hover:text-gray-200'"
              >
                Mes Réservations
              </button>
              <button 
                @click="setActiveTab('progress')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeTab === 'progress' 
                  ? 'bg-cyan-500/20 text-cyan-300 shadow-lg shadow-cyan-500/10' 
                  : 'text-gray-400 hover:text-gray-200'"
              >
                Ma Progression
              </button>
            </div>
          </nav>
        </div>
      </header>

      <!-- Statistiques client -->
      <div v-if="activeTab === 'calendar'" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-gray-800/30 backdrop-blur-sm rounded-2xl p-4 border border-gray-700/50">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-white">{{ stats.sessionsThisMonth }}</p>
              <p class="text-xs text-gray-400">Séances ce mois</p>
            </div>
          </div>
        </div>

       

        <div class="bg-gray-800/30 backdrop-blur-sm rounded-2xl p-4 border border-gray-700/50">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-white">{{ stats.goalsCompleted }}/{{ stats.targetMonthlySessions }}</p>
              <p class="text-xs text-gray-400">Objectifs</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/30 backdrop-blur-sm rounded-2xl p-4 border border-gray-700/50">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-white">{{ stats.nextSession }}</p>
              <p class="text-xs text-gray-400">Prochaine séance</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Onglet Calendrier -->
      <div v-if="activeTab === 'calendar'" class="transition-all duration-300">
        <div class="grid lg:grid-cols-4 gap-6">
          <!-- Filtres -->
          <div class="lg:col-span-1">
            <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-6 border border-gray-700/50 sticky top-6">
              <h3 class="text-lg font-semibold text-white mb-6">Filtres</h3>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Coach</label>
                  <select v-model="filters.coach" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl p-3 text-white text-sm">
                    <option value="">Tous les coachs</option>
                    <option v-for="coach in initialCoaches" :key="coach" :value="coach">{{ coach }}</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                  <select v-model="filters.status" class="w-full bg-gray-700/50 border border-gray-600 rounded-xl p-3 text-white text-sm">
                    <option value="">Tous les cours</option>
                    <option value="available">Places disponibles</option>
                    <option value="reserved">Mes réservations</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Calendrier -->
          <div class="lg:col-span-3">
            <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-6 border border-gray-700/50">
              <FullCalendar
                ref="calendar"
                :options="calendarOptions"
                class="modern-blue-calendar"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Onglet Mes Réservations -->
      <div v-if="activeTab === 'mySessions'" class="transition-all duration-300">
        <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-8 border border-gray-700/50">
          <h2 class="text-2xl font-bold text-white mb-6">Mes Réservations</h2>
          
          <div v-if="userReservations.length > 0" class="space-y-4">
            <div v-for="reservation in userReservations" :key="reservation.id" 
                 class="bg-gray-700/30 rounded-2xl p-6 border border-green-500/20 hover:border-green-400/40 transition-all">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-lg font-semibold text-white">{{ reservation.title.replace('✓ ', '') }}</h3>
                  <p class="text-gray-400 text-sm mt-1">Coach: {{ reservation.extendedProps.coach }}</p>
                  <p class="text-gray-400 text-sm">{{ formatDate(reservation.start) }} • {{ formatTime(reservation.start) }}</p>
                </div>
                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">
                  Réservé
                </span>
              </div>
              <div class="mt-4 flex gap-3">
  <button 
    @click="openCancelModal(reservation.extendedProps.reservation_id)" 
    class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 rounded-xl text-red-400 text-sm transition-colors"
  >
    Annuler
  </button>
  <button class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-xl text-blue-400 text-sm transition-colors">
    Détails
  </button>
</div>
            </div>
          </div>
          
          <div v-else class="text-center py-12">
            <p class="text-gray-400">Aucune réservation pour le moment</p>
          </div>
        </div>
      </div>

      <!-- Onglet Progression -->
      <div v-if="activeTab === 'progress'" class="transition-all duration-300">
        <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-8 border border-gray-700/50">
          <h2 class="text-2xl font-bold text-white mb-6">Ma Progression</h2>
          
          <div class="grid lg:grid-cols-2 gap-8">
            <!-- Graphique -->
            <div class="bg-gray-700/30 rounded-2xl p-6 border border-cyan-500/20">
              <h3 class="text-lg font-semibold text-white mb-4">Évolution mensuelle</h3>
              <div class="h-64 relative">
                <svg class="w-full h-full" viewBox="0 0 400 200" preserveAspectRatio="none">
                  <!-- Courbe de progression -->
                  <path 
                    d="M0,200 L100,150 L200,120 L300,80 L400,50" 
                    fill="none" 
                    stroke="#06b6d4" 
                    stroke-width="3" 
                    stroke-linecap="round" 
                  />
                </svg>
              </div>
            </div>

            <!-- Statistiques -->
            <div class="space-y-6">
              <div class="bg-gray-700/30 rounded-2xl p-6 border border-cyan-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Objectifs</h3>
                <div class="space-y-4">
                  <div>
                    <div class="flex justify-between text-sm text-gray-400 mb-2">
                      <span>Séances ce mois</span>
                      <span>{{ stats.goalsCompleted }}/{{ stats.targetMonthlySessions }}</span>
                    </div>
                    <div class="w-full bg-gray-600 rounded-full h-3">
                      <div 
                        class="bg-cyan-500 h-3 rounded-full transition-all duration-1000" 
                        :style="{ width: progressPercentage + '%' }"
                      ></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Réservation -->
    <div v-if="selectedEvent" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-50 p-4" @click="closeModal">
      <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-8 max-w-md w-full border border-blue-500/30 shadow-2xl" @click.stop>
        <div class="flex items-start justify-between mb-6">
          <div>
            <h3 class="text-2xl font-bold text-white mb-2">{{ selectedEvent.title }}</h3>
            <p class="text-blue-400 text-sm">{{ formatEventTime(selectedEvent) }}</p>
          </div>
          <button @click="closeModal" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div class="flex justify-between items-center p-4 bg-gray-700/50 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm text-gray-400">Coach</p>
                <p class="text-white font-semibold">{{ selectedEvent.extendedProps?.coach }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-400">Places</p>
              <p class="text-white font-semibold">{{ selectedEvent.extendedProps?.spotsLeft }}/{{ selectedEvent.extendedProps?.maxParticipants }}</p>
            </div>
          </div>
          
          <div class="bg-gray-700/50 rounded-2xl p-4">
            <p class="text-sm text-gray-400 mb-2">Description</p>
            <p class="text-white">{{ selectedEvent.extendedProps?.description }}</p>
          </div>

          <button 
            v-if="selectedEvent.extendedProps?.status === 'available' && selectedEvent.extendedProps?.spotsLeft > 0"
            @click="reserveCourse(selectedEvent.id)"
            class="w-full py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-2xl text-white font-semibold transition-all duration-200"
          >
            Réserver ce cours
          </button>

          <div v-else class="w-full py-4 bg-gray-600 rounded-2xl text-center text-gray-300">
            Complet
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal de confirmation d'annulation -->
<div v-if="showCancelModal" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-50 p-4">
  <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-8 max-w-md w-full border border-red-500/30 shadow-2xl">
    <!-- Icône d'alerte -->
    <div class="flex justify-center mb-4">
      <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center">
        <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
      </div>
    </div>

    <!-- Contenu du modal -->
    <div class="text-center">
      <h3 class="text-xl font-bold text-white mb-2">Confirmer l'annulation</h3>
      <p class="text-gray-300 mb-6">
        Êtes-vous sûr de vouloir annuler cette réservation ? 
        <span class="text-red-400 block mt-1 text-sm">Cette action est irréversible.</span>
      </p>

      <!-- Boutons d'action -->
      <div class="flex gap-3">
        <button 
          @click="closeCancelModal"
          class="flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-600 rounded-xl text-gray-300 font-medium transition-colors"
        >
          Retour
        </button>
        <button 
          @click="confirmCancel"
          class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 border border-red-500 rounded-xl text-white font-medium transition-colors flex items-center justify-center gap-2"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          Oui, annuler
        </button>
      </div>
    </div>
  </div>
</div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import frLocale from '@fullcalendar/core/locales/fr'
const showCancelModal = ref(false)
const reservationToCancel = ref(null)
const props = defineProps({
  initialEvents: Array,
  initialCoaches: Array,
  stats: Object,
  userReservations: Array
})
const showFlash = ref(true)

const calendar = ref(null)
const activeTab = ref('calendar')
const selectedEvent = ref(null)
const filters = ref({
  coach: '',
  status: ''
})
// Cacher manuellement
function hideFlash() {
  showFlash.value = false
  if (window.flashTimer) {
    clearTimeout(window.flashTimer)
  }
}
const calendarOptions = {
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'timeGridWeek',
  locale: frLocale,
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
  },
  events: props.initialEvents,
  eventClick: handleEventClick,
  height: 'auto',
  slotMinTime: '06:00:00',
  slotMaxTime: '23:00:00',
  allDaySlot: false,
  nowIndicator: true
}

const progressPercentage = computed(() => {
  return Math.round((props.stats.goalsCompleted / props.stats.targetMonthlySessions) * 100)
})

function setActiveTab(tab) {
  activeTab.value = tab
}

function handleEventClick(info) {
  selectedEvent.value = info.event
}

function closeModal() {
  selectedEvent.value = null
}

function reserveCourse(courseId) {
  router.post(`/client/courses/${courseId}/reserve`, {}, {
    onSuccess: () => {
      closeModal()
      router.reload()
    }
  })
}



function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('fr-FR')
}

function formatTime(dateString) {
  return new Date(dateString).toLocaleTimeString('fr-FR', { 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

function formatEventTime(event) {
  const start = new Date(event.start)
  const end = new Date(event.end)
  return `${start.getHours().toString().padStart(2, '0')}:${start.getMinutes().toString().padStart(2, '0')} - ${end.getHours().toString().padStart(2, '0')}:${end.getMinutes().toString().padStart(2, '0')}`
}

// Fonctions pour gérer le modal d'annulation
function openCancelModal(reservationId) {
  reservationToCancel.value = reservationId
  showCancelModal.value = true
}

function closeCancelModal() {
  showCancelModal.value = false
  reservationToCancel.value = null
}

function confirmCancel() {
  if (reservationToCancel.value) {
    cancelReservation(reservationToCancel.value)
  }
  closeCancelModal()
}



// Fonctions pour les messages (optionnel)
function showSuccessMessage(message) {
  // Vous pouvez utiliser un système de notifications ici
  alert(message) // Temporaire
}

function showErrorMessage(message) {
  alert('Erreur: ' + message) // Temporaire
}

function cancelReservation(reservationId) {
  router.delete(`/client/reservations/${reservationId}`, {
    preserveScroll: true,
    onSuccess: () => {
      // Supprimer la réservation de la liste localement
      const index = userReservations.findIndex(r => 
        r.extendedProps.reservation_id === reservationId
      )
      if (index !== -1) {
        userReservations.splice(index, 1)
      }
      
      // Mettre à jour les stats
      router.reload({ only: ['stats'] })
    },
    onError: (error) => {
      alert('Erreur: ' + (error.message || 'Impossible d\'annuler la réservation'))
    }
  })
}
</script>

<style>
.modern-blue-calendar .fc-toolbar {
  @apply text-white;
}
.modern-blue-calendar .fc-toolbar-title {
  @apply text-xl font-semibold;
}
.modern-blue-calendar .fc-button {
  @apply bg-gray-700 border-gray-600 text-white hover:bg-gray-600;
}
.modern-blue-calendar .fc-button-active {
  @apply bg-blue-500 border-blue-500;
}
.modern-blue-calendar .fc-daygrid-day-number,
.modern-blue-calendar .fc-col-header-cell-cushion {
  @apply text-white;
}
</style>