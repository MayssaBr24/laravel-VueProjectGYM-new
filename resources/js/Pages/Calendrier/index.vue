<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-900 to-purple-900 py-6 px-4 font-sans">
    <div class="max-w-7xl mx-auto">
      <!-- Header minimaliste avec navigation -->
      <header class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="text-center md:text-left">
            <h1 class="text-3xl md:text-5xl font-bold mb-2">
              <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent">
                FITPLAN
              </span>
            </h1>
            <p class="text-gray-300 text-sm md:text-base">Votre agenda fitness personnalisé</p>
          </div>
          
          <!-- Navigation pills -->
         
        </div>
      </header>

      <!-- Contenu dynamique selon l'onglet -->
      
      <!-- Onglet Calendrier -->
      <div v-if="activeTab === 'calendar'" class="transition-all duration-300">
        <!-- Vos statistiques rapides -->
        <div class="grid grid-cols-3 md:grid-cols-3 gap-4 mb-8">
          <div class="bg-gray-800/30 backdrop-blur-sm rounded-2xl p-4 border border-gray-700/50">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">{{ stats.sessionsThisMonth }}</p>
                <p class="text-xs text-gray-400">Cours ce mois</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800/30 backdrop-blur-sm rounded-2xl p-4 border border-gray-700/50">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">{{ stats.attendanceRate }}%</p>
                <p class="text-xs text-gray-400">Remplissage moyen</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800/30 backdrop-blur-sm rounded-2xl p-4 border border-gray-700/50">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">{{ stats.coachesCount }}</p>
                <p class="text-xs text-gray-400">Coachs</p>
              </div>
            </div>
          </div>

        </div>

        <div class="grid lg:grid-cols-4 gap-6">
          <!-- Sidebar avec filtres -->
          <div class="lg:col-span-1">
            <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-6 border border-gray-700/50 sticky top-6">
              <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
                </svg>
                Filtres
              </h3>

              <div class="space-y-6">
                <!-- Filtre Coach -->
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-3">Coach</label>
                  <div class="space-y-2">
                    <button 
                      v-for="coach in initialCoaches" 
                      :key="coach"
                      @click="toggleCoachFilter(coach)"
                      class="w-full flex items-center justify-between p-3 rounded-xl transition-all duration-200"
                      :class="filters.coach === coach 
                        ? 'bg-blue-500/20 border border-blue-500/30' 
                        : 'bg-gray-700/50 border border-gray-600/30 hover:border-gray-500'"
                    >
                      <span class="text-gray-200 text-sm">{{ coach }}</span>
                      <div 
                        class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                        :class="filters.coach === coach 
                          ? 'bg-blue-500 border-blue-500' 
                          : 'border-gray-400'"
                      >
                        <svg 
                          v-if="filters.coach === coach" 
                          class="w-3 h-3 text-white" 
                          fill="currentColor" 
                          viewBox="0 0 20 20"
                        >
                          <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                      </div>
                    </button>
                  </div>
                </div>

                <!-- Filtre Horaire -->
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-3">Horaire</label>
                  <div class="grid grid-cols-2 gap-2">
                    <button 
                      v-for="timeSlot in timeSlots" 
                      :key="timeSlot.value"
                      @click="toggleTimeFilter(timeSlot.value)"
                      class="p-3 rounded-xl text-center transition-all duration-200"
                      :class="filters.time === timeSlot.value 
                        ? 'bg-purple-500/20 border border-purple-500/30' 
                        : 'bg-gray-700/50 border border-gray-600/30 hover:border-gray-500'"
                    >
                      <div 
                        class="w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center"
                        :class="filters.time === timeSlot.value 
                          ? 'bg-purple-500/20 text-purple-300' 
                          : 'bg-gray-600/50 text-gray-400'"
                      >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                          <path :d="timeSlot.icon"/>
                        </svg>
                      </div>
                      <span class="text-xs text-gray-200 block">{{ timeSlot.label }}</span>
                    </button>
                  </div>
                </div>

                <!-- Bouton reset -->
                <button 
                  @click="resetFilters" 
                  class="w-full py-3 bg-gray-700/50 hover:bg-gray-600/50 border border-gray-600/30 rounded-xl text-gray-300 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2"
                >
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                  </svg>
                  Réinitialiser
                </button>
              </div>
            </div>
          </div>

          <!-- Calendrier principal -->
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

      <!-- Onglet Mes Séances -->
      <div v-if="activeTab === 'workouts'" class="transition-all duration-300">
        <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-8 border border-gray-700/50">
          <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM10 4h4v2h-4V4zm10 16H4V8h16v12z"/>
            </svg>
            Mes Séances Réservées
          </h2>
          
          <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM10 4h4v2h-4V4zm10 16H4V8h16v12z"/>
            </svg>
            <p class="text-gray-400">Aucune séance réservée pour le moment</p>
            <button @click="setActiveTab('calendar')" 
                    class="mt-4 px-6 py-2 bg-purple-500 hover:bg-purple-600 rounded-xl text-white transition-colors">
              Parcourir les cours
            </button>
          </div>
        </div>
      </div>

      <!-- Onglet Progression -->
      <div v-if="activeTab === 'progress'" class="transition-all duration-300">
        <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-8 border border-gray-700/50">
          <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
            </svg>
            Progression
          </h2>
          
          <div class="grid lg:grid-cols-2 gap-8">
            <!-- Graphique de progression -->
            <div class="bg-gray-700/30 rounded-2xl p-6 border border-cyan-500/20">
              <h3 class="text-lg font-semibold text-white mb-4">Évolution des séances</h3>
              <div class="h-64 relative">
                <svg class="w-full h-full" viewBox="0 0 400 200" preserveAspectRatio="none">
                  <!-- Lignes de grille -->
                  <line x1="0" y1="40" x2="400" y2="40" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  <line x1="0" y1="80" x2="400" y2="80" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  <line x1="0" y1="120" x2="400" y2="120" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  <line x1="0" y1="160" x2="400" y2="160" stroke="#4b5563" stroke-width="1" stroke-dasharray="4" />
                  
                  <!-- Courbe de progression -->
                  <path 
                    d="M0,200 L100,150 L200,120 L300,80 L400,50" 
                    fill="none" 
                    stroke="#06b6d4" 
                    stroke-width="3" 
                    stroke-linecap="round" 
                  />
                  
                  <!-- Points sur la courbe -->
                  <circle cx="0" cy="200" r="4" fill="#06b6d4" />
                  <circle cx="100" cy="150" r="4" fill="#06b6d4" />
                  <circle cx="200" cy="120" r="4" fill="#06b6d4" />
                  <circle cx="300" cy="80" r="4" fill="#06b6d4" />
                  <circle cx="400" cy="50" r="4" fill="#06b6d4" />
                </svg>
                
                <!-- Labels des semaines -->
                <div class="absolute bottom-0 left-0 right-0 flex justify-between text-xs text-gray-400 px-2">
                  <span>Sem 1</span>
                  <span>Sem 2</span>
                  <span>Sem 3</span>
                  <span>Sem 4</span>
                </div>
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
                    <p class="text-cyan-400 text-sm mt-2 text-center" v-if="progressPercentage < 100">
                      Plus que {{ stats.targetMonthlySessions - stats.goalsCompleted }} séances !
                    </p>
                    <p class="text-green-400 text-sm font-semibold mt-2 text-center" v-else>
                      🎉 Objectif atteint !
                    </p>
                  </div>
                </div>
              </div>

              <div class="bg-gray-700/30 rounded-2xl p-6 border border-purple-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Résumé du Mois</h3>
                <div class="space-y-3">
                  <div class="flex justify-between items-center">
                    <span class="text-gray-400">Séances totales</span>
                    <span class="text-white font-semibold">{{ stats.userReservations }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-400">Taux de réussite</span>
                    <span class="text-white font-semibold">{{ progressPercentage }}%</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-400">Prochaine séance</span>
                    <span class="text-white font-semibold">Demain</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal pour les détails d'événement -->
    <div v-if="selectedEvent" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-50 p-4" @click="closeModal">
      <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-8 max-w-md w-full border border-blue-500/30 shadow-2xl transform transition-all" @click.stop>
        <div class="flex items-start justify-between mb-6">
          <div>
            <h3 class="text-2xl font-bold text-white mb-2">{{ selectedEvent.title }}</h3>
            <div class="flex items-center gap-2 text-blue-400">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
              </svg>
              <span class="text-sm">{{ formatEventTime(selectedEvent) }}</span>
            </div>
          </div>
          <button @click="closeModal" class="w-8 h-8 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
              <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center justify-between p-4 bg-gray-700/50 rounded-2xl">
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

          <button class="w-full py-4 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 rounded-2xl text-white font-semibold transition-all duration-200 transform hover:scale-[1.02] shadow-lg shadow-blue-500/25">
            Réserver cette séance
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import frLocale from '@fullcalendar/core/locales/fr'

const props = defineProps({
  initialEvents: {
    type: Array,
    default: () => []
  },
  initialCoaches: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      sessionsThisMonth: 0,
      attendanceRate: 0,
      coachesCount: 0,
      goalsCompleted: 0,
      targetMonthlySessions: 8,
      userReservations: 0
    })
  }
})

const calendar = ref(null)
const events = ref(props.initialEvents)
const coaches = ref(props.initialCoaches)
const filters = ref({ coach: '', time: '' })
const selectedEvent = ref(null)
const activeTab = ref('calendar')
const windowWidth = ref(window.innerWidth)

const timeSlots = ref([
  { value: 'morning', label: 'Matin', icon: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h2v-6h-2v6zm0-8h2V7h-2v2z' },
  { value: 'afternoon', label: 'Après-midi', icon: 'M6 2v6h.01L6 8.01 10 12l-4 4 .01.01H6V22h12v-5.99h-.01L18 16l-4-4 4-3.99-.01-.01H18V2H6z' },
  { value: 'evening', label: 'Soir', icon: 'M2 12c0 5.52 4.48 10 10 10s10-4.48 10-10S17.52 2 12 2 2 6.48 2 12zm10-1h4v2h-4v3l-4-4 4-4v3z' }
])

const calendarOptions = {
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek',
  locale: frLocale,
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: window.innerWidth < 768 ? 'timeGridDay,timeGridWeek' : 'dayGridMonth,timeGridWeek,timeGridDay'
  },
  events: events.value,
  eventClick: handleEventClick,
  height: 'auto',
  slotMinTime: '05:00:00',
  slotMaxTime: '24:00:00',
  allDaySlot: false,
  nowIndicator: true,
  eventTimeFormat: {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  },
  dayHeaderFormat: { weekday: 'short', day: 'numeric' },
  slotLabelFormat: {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  }
}

const progressPercentage = computed(() => {
  if (!props.stats.targetMonthlySessions) return 0
  return Math.round((props.stats.goalsCompleted / props.stats.targetMonthlySessions) * 100)
})

const filteredEvents = computed(() => {
  return events.value.filter(event => {
    const coachFilter =
      filters.value.coach === '' || event.extendedProps?.coach === filters.value.coach

    const timeFilter = (() => {
      if (filters.value.time === '') return true
      const hour = new Date(event.start).getHours()
      if (filters.value.time === 'morning') return hour >= 5 && hour < 12
      if (filters.value.time === 'afternoon') return hour >= 12 && hour < 18
      if (filters.value.time === 'evening') return hour >= 18 && hour < 24
      return true
    })()

    return coachFilter && timeFilter
  })
})

watch(filteredEvents, (newVal) => {
  if (calendar.value) {
    const calendarApi = calendar.value.getApi()
    calendarApi.removeAllEvents()
    calendarApi.addEventSource(newVal)
  }
})

watch(windowWidth, (newWidth) => {
  if (calendar.value) {
    const calendarApi = calendar.value.getApi()
    if (newWidth < 768) {
      calendarApi.changeView('timeGridDay')
      calendarOptions.headerToolbar.right = 'timeGridDay,timeGridWeek'
    } else {
      calendarApi.changeView('timeGridWeek')
      calendarOptions.headerToolbar.right = 'dayGridMonth,timeGridWeek,timeGridDay'
    }
  }
})



function handleEventClick(info) {
  selectedEvent.value = info.event
}

function closeModal() {
  selectedEvent.value = null
}

function toggleCoachFilter(coach) {
  filters.value.coach = filters.value.coach === coach ? '' : coach
}

function toggleTimeFilter(time) {
  filters.value.time = filters.value.time === time ? '' : time
}

function resetFilters() {
  filters.value = { coach: '', time: '' }
}
function setActiveTab(tab) {
  console.log('Bouton cliqué, tab:', tab) // ← AJOUTEZ CETTE LIGNE
  activeTab.value = tab
}

function formatEventTime(event) {
  const start = new Date(event.start)
  const end = new Date(event.end)
  return `${start.getHours().toString().padStart(2, '0')}:${start.getMinutes().toString().padStart(2, '0')} - ${end.getHours().toString().padStart(2, '0')}:${end.getMinutes().toString().padStart(2, '0')}`
}

function handleResize() {
  windowWidth.value = window.innerWidth
}

onMounted(() => {
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})

</script>

<style>
.modern-blue-calendar .fc-toolbar {
  @apply text-white;
}

.modern-blue-calendar .fc-toolbar-title {
  @apply text-xl font-semibold;
}

.modern-blue-calendar .fc-button {
  @apply bg-gray-700 border-gray-600 text-white hover:bg-gray-600 transition-colors;
}

.modern-blue-calendar .fc-button-active {
  @apply bg-blue-500 border-blue-500;
}

.modern-blue-calendar .fc-daygrid-day-number,
.modern-blue-calendar .fc-col-header-cell-cushion {
  @apply text-white;
}

.modern-blue-calendar .fc-daygrid-day {
  @apply bg-gray-700/20 border-gray-600/30;
}

.modern-blue-calendar .fc-day-today {
  @apply bg-blue-500/10;
}

.modern-blue-calendar .fc-timegrid-slot {
  @apply bg-gray-700/10 border-gray-600/20;
}

.modern-blue-calendar .fc-timegrid-now-indicator-line {
  @apply bg-red-500;
}
</style>