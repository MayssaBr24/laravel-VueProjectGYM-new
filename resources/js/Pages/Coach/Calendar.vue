<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-800 via-emerald-700 to-blue-800 py-6 px-4 font-sans">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <header class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="text-center md:text-left">
            <h1 class="text-3xl md:text-5xl font-bold mb-2">
              <span class="bg-gradient-to-r from-emerald-300 via-cyan-300 to-blue-300 bg-clip-text text-transparent">
                ESPACE COACH
              </span>
            </h1>
            <p class="text-gray-200 text-sm md:text-base">Gérez vos cours et suivez vos performances</p>
          </div>
          
          <!-- Navigation -->
          <nav class="flex justify-center md:justify-end">
            <div class="bg-gray-900/60 backdrop-blur-lg rounded-2xl p-1.5 flex gap-1">
              <button 
                @click="setActiveTab('calendar')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeTab === 'calendar' 
                  ? 'bg-emerald-500/30 text-emerald-200 shadow-lg shadow-emerald-500/20' 
                  : 'text-gray-300 hover:text-white'"
              >
                Calendrier
              </button>
              <button 
                @click="setActiveTab('myCourses')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeTab === 'myCourses' 
                  ? 'bg-blue-500/30 text-blue-200 shadow-lg shadow-blue-500/20' 
                  : 'text-gray-300 hover:text-white'"
              >
                Mes Cours
              </button>
              <button 
                @click="setActiveTab('progress')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeTab === 'progress' 
                  ? 'bg-cyan-500/30 text-cyan-200 shadow-lg shadow-cyan-500/20' 
                  : 'text-gray-300 hover:text-white'"
              >
                Performance
              </button>
            </div>
          </nav>
        </div>
      </header>

      <!-- Statistiques Coach -->
      <div v-if="activeTab === 'calendar'" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-4 border border-emerald-500/20">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-500/30 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-white">{{ stats.coursesThisMonth }}</p>
              <p class="text-xs text-gray-300">Cours ce mois</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-4 border border-green-500/20">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500/30 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-white">{{ stats.averageOccupancy }}%</p>
              <p class="text-xs text-gray-300">Remplissage moyen</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-4 border border-cyan-500/20">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-cyan-500/30 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-cyan-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-white">{{ stats.totalReservations }}</p>
              <p class="text-xs text-gray-300">Total réservations</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-4 border border-blue-500/20">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/30 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-white">{{ stats.nextCourse }}</p>
              <p class="text-xs text-gray-300">Prochain cours</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Onglet Calendrier -->
      <div v-if="activeTab === 'calendar'" class="transition-all duration-300">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-3xl p-6 border border-emerald-500/20">
          <FullCalendar
            ref="calendar"
            :options="calendarOptions"
            class="modern-emerald-calendar"
          />
        </div>
      </div>

      <!-- Onglet Mes Cours -->
      <div v-if="activeTab === 'myCourses'" class="transition-all duration-300">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-3xl p-8 border border-blue-500/20">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
              <svg class="w-6 h-6 text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM10 4h4v2h-4V4zm10 16H4V8h16v12z"/>
              </svg>
              Mes Cours
            </h2>
          </div>
          
          <div v-if="myCourses.length > 0" class="space-y-4">
            <div v-for="course in myCourses" :key="course.id" 
                 class="bg-gray-800/40 rounded-2xl p-6 border border-gray-700/50 hover:border-blue-500/40 transition-all duration-300">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-lg font-semibold text-white">{{ course.title.replace(/ \(\d+ places\)/, '') }}</h3>
                  <p class="text-gray-300 text-sm mt-1">{{ formatDate(course.start) }} • {{ formatTime(course.start) }}</p>
                  <p class="text-gray-300 text-sm">Participants: {{ course.extendedProps.reservationsCount }}/{{ course.extendedProps.maxParticipants }}</p>
                </div>
                <div class="text-right">
                  <span class="px-3 py-1 rounded-full text-xs font-medium"
                        :class="course.extendedProps.occupancyRate >= 80 ? 'bg-emerald-500/30 text-emerald-300' : 
                               course.extendedProps.occupancyRate >= 50 ? 'bg-blue-500/30 text-blue-300' : 'bg-cyan-500/30 text-cyan-300'">
                    {{ course.extendedProps.occupancyRate }}% rempli
                  </span>
                  <p class="text-gray-300 text-sm mt-2">{{ course.extendedProps.spotsLeft }} places libres</p>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM10 4h4v2h-4V4zm10 16H4V8h16v12z"/>
            </svg>
            <p class="text-gray-300">Aucun cours programmé</p>
            <button @click="openCreateModal" 
                    class="mt-4 px-6 py-2 bg-blue-500 hover:bg-blue-600 rounded-xl text-white transition-colors shadow-lg shadow-blue-500/30">
              Créer votre premier cours
            </button>
          </div>
        </div>
      </div>

      <!-- Onglet Performance -->
      <div v-if="activeTab === 'progress'" class="transition-all duration-300">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-3xl p-8 border border-cyan-500/20">
          <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-cyan-300" fill="currentColor" viewBox="0 0 24 24">
              <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
            </svg>
            Mes Performances
          </h2>
          
          <div class="grid lg:grid-cols-2 gap-8">
            <!-- Graphique de performance -->
            <div class="bg-gray-800/40 rounded-2xl p-6 border border-cyan-500/30">
              <h3 class="text-lg font-semibold text-white mb-4">Évolution du taux de remplissage</h3>
              <div class="h-64 relative">
                <svg class="w-full h-full" viewBox="0 0 400 200" preserveAspectRatio="none">
                  <!-- Lignes de grille -->
                  <line x1="0" y1="40" x2="400" y2="40" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  <line x1="0" y1="80" x2="400" y2="80" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  <line x1="0" y1="120" x2="400" y2="120" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  <line x1="0" y1="160" x2="400" y2="160" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  
                  <!-- Courbe de performance -->
                  <path 
                    d="M0,200 L100,150 L200,120 L300,80 L400,50" 
                    fill="none" 
                    stroke="#22d3ee" 
                    stroke-width="3" 
                    stroke-linecap="round" 
                  />
                </svg>
              </div>
            </div>

            <!-- Statistiques détaillées -->
            <div class="space-y-6">
              <div class="bg-gray-800/40 rounded-2xl p-6 border border-emerald-500/30">
                <h3 class="text-lg font-semibold text-white mb-4">Aperçu des Réservations</h3>
                <div class="space-y-3">
                  <div class="flex justify-between items-center">
                    <span class="text-gray-300">Cours ce mois</span>
                    <span class="text-white font-semibold">{{ stats.coursesThisMonth }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-300">Réservations totales</span>
                    <span class="text-white font-semibold">{{ stats.totalReservations }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-300">Remplissage moyen</span>
                    <span class="text-white font-semibold">{{ stats.averageOccupancy }}%</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-300">Revenus estimés</span>
                    <span class="text-white font-semibold">{{ stats.estimatedRevenue }} €</span>
                  </div>
                </div>
              </div>

              <div class="bg-gray-800/40 rounded-2xl p-6 border border-green-500/30">
                <h3 class="text-lg font-semibold text-white mb-4">Objectifs</h3>
                <div class="space-y-4">
                  <div>
                    <div class="flex justify-between text-sm text-gray-300 mb-2">
                      <span>Taux de remplissage cible</span>
                      <span>{{ stats.averageOccupancy }}%/80%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-3">
                      <div 
                        class="bg-gradient-to-r from-emerald-500 to-green-500 h-3 rounded-full transition-all duration-1000" 
                        :style="{ width: Math.min(stats.averageOccupancy, 100) + '%' }"
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

    <!-- Modal Détails Cours -->
    <div v-if="selectedEvent" class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-50 p-4" @click="closeModal">
      <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-8 max-w-md w-full border border-emerald-500/30 shadow-2xl" @click.stop>
        <div class="flex items-start justify-between mb-6">
          <div>
            <h3 class="text-2xl font-bold text-white mb-2">{{ selectedEvent.title }}</h3>
            <p class="text-emerald-300 text-sm">{{ formatEventTime(selectedEvent) }}</p>
          </div>
          <button @click="closeModal" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div class="flex justify-between items-center p-4 bg-gray-800/60 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-emerald-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm text-gray-300">Coach</p>
                <p class="text-white font-semibold">{{ selectedEvent.extendedProps?.coach }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-300">Participants</p>
              <p class="text-white font-semibold">{{ selectedEvent.extendedProps?.reservationsCount }}/{{ selectedEvent.extendedProps?.maxParticipants }}</p>
            </div>
          </div>
          
          <div class="bg-gray-800/60 rounded-2xl p-4">
            <p class="text-sm text-gray-300 mb-2">Description</p>
            <p class="text-white">{{ selectedEvent.extendedProps?.description }}</p>
          </div>

          <div class="flex gap-3">
            <button 
              @click="editCourse(selectedEvent)"
              class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 rounded-xl text-white font-medium transition-all duration-200 shadow-lg shadow-emerald-500/30"
            >
              Modifier
            </button>
            <button 
              @click="deleteCourse(selectedEvent.id)"
              class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-xl text-white font-medium transition-all duration-200 shadow-lg shadow-red-500/30"
            >
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import frLocale from '@fullcalendar/core/locales/fr'

const props = defineProps({
  initialEvents: Array,
  stats: Object,
  myCourses: Array
})

const calendar = ref(null)
const activeTab = ref('calendar')
const selectedEvent = ref(null)

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

function setActiveTab(tab) {
  activeTab.value = tab
}

function handleEventClick(info) {
  selectedEvent.value = info.event
}

function closeModal() {
  selectedEvent.value = null
}

function openCreateModal() {
  // Implémentez la création de cours
  alert('Fonctionnalité de création de cours à implémenter')
}

function editCourse(course) {
  // Implémentez l'édition de cours
  alert('Édition du cours: ' + course.title)
}

function deleteCourse(courseId) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) {
    router.delete(`/coach/courses/${courseId}`, {
      onSuccess: () => {
        router.reload()
      }
    })
  }
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
</script>

<style>
.modern-emerald-calendar .fc-toolbar {
  @apply text-white;
}
.modern-emerald-calendar .fc-toolbar-title {
  @apply text-xl font-semibold;
}
.modern-emerald-calendar .fc-button {
  @apply bg-gray-800 border-gray-700 text-white hover:bg-gray-700;
}
.modern-emerald-calendar .fc-button-active {
  @apply bg-emerald-600 border-emerald-600;
}
.modern-emerald-calendar .fc-daygrid-day-number,
.modern-emerald-calendar .fc-col-header-cell-cushion {
  @apply text-white;
}
.modern-emerald-calendar .fc-day-today {
  @apply bg-emerald-500/20;
}
</style>