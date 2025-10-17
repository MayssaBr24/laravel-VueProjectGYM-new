<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-gray-900 to-emerald-950 py-4 md:py-8 px-3 md:px-4 font-sans">
    <div class="max-w-7xl mx-auto">
      <!-- Header Premium avec effet glassmorphism -->
      <div class="relative mb-6 md:mb-8 rounded-2xl md:rounded-3xl bg-gradient-to-r from-emerald-600/20 via-teal-600/20 to-green-600/20 backdrop-blur-xl border border-emerald-400/20 overflow-hidden">
        <!-- Effet de particules animées -->
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 via-transparent to-teal-500/10 animate-pulse"></div>
        
        <!-- Icônes flottantes avec animations (masquées sur mobile) -->
        <div class="hidden md:block absolute left-4 md:left-8 top-4 md:top-8 animate-bounce">
          <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl md:rounded-2xl flex items-center justify-center shadow-2xl">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
        </div>
        
        <div class="hidden md:block absolute right-4 md:right-8 top-4 md:top-8 animate-pulse">
          <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-teal-400 to-green-500 rounded-xl md:rounded-2xl flex items-center justify-center shadow-2xl">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
        </div>

        <div class="text-center py-8 md:py-16 relative z-10">
          <h1 class="text-4xl md:text-6xl font-black mb-3 md:mb-4">
            <span class="bg-gradient-to-r from-emerald-300 via-teal-300 to-green-300 bg-clip-text text-transparent drop-shadow-2xl">
              FITNESS
            </span>
          </h1>
          <p class="text-base md:text-xl text-gray-300 font-medium tracking-wide">Planifiez vos séances d'entraînement</p>
          <div class="mt-4 md:mt-6 h-1 w-24 md:w-32 mx-auto bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full"></div>
        </div>
      </div>

      <!-- Bouton de filtre mobile -->
      <div class="md:hidden mb-4">
        <button 
          @click="showMobileFilters = !showMobileFilters"
          class="w-full flex items-center justify-between px-4 py-3 bg-gray-800/60 backdrop-blur-sm border border-emerald-500/30 rounded-xl text-gray-200"
        >
          <span class="font-semibold">Filtres</span>
          <svg 
            class="w-5 h-5 text-emerald-400 transition-transform duration-300" 
            :class="{ 'rotate-180': showMobileFilters }"
            fill="currentColor" viewBox="0 0 20 20"
          >
            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
          </svg>
        </button>
      </div>

      <!-- Filtres avec design cards premium -->
      <div 
        class="grid gap-4 md:gap-6 mb-6 md:mb-8 transition-all duration-300 overflow-hidden"
        :class="{
          'grid-rows-[0fr] md:grid-rows-[1fr]': !showMobileFilters && windowWidth < 768,
          'grid-rows-[1fr]': showMobileFilters || windowWidth >= 768
        }"
      >
        <div class="min-h-0 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
          <div class="group">
            <label class="block text-sm font-semibold text-emerald-300 mb-2 md:mb-3 uppercase tracking-wider">Coach</label>
            <div class="relative">
              <select v-model="filters.coach" class="w-full px-4 md:px-6 py-3 md:py-4 bg-gray-800/60 backdrop-blur-sm border border-emerald-500/30 rounded-xl md:rounded-2xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 focus:border-emerald-400/50 transition-all duration-300 group-hover:border-emerald-400/40 text-sm md:text-base">
                <option value="" class="bg-gray-900">Tous les coachs</option>
                <option v-for="coach in coaches" :key="coach" :value="coach" class="bg-gray-900">{{ coach }}</option>
              </select>
              <div class="absolute right-3 md:right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="group">
            <label class="block text-sm font-semibold text-emerald-300 mb-2 md:mb-3 uppercase tracking-wider">Horaire</label>
            <div class="relative">
              <select v-model="filters.time" class="w-full px-4 md:px-6 py-3 md:py-4 bg-gray-800/60 backdrop-blur-sm border border-emerald-500/30 rounded-xl md:rounded-2xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 focus:border-emerald-400/50 transition-all duration-300 group-hover:border-emerald-400/40 text-sm md:text-base">
                <option value="" class="bg-gray-900">Toutes les heures</option>
                <option value="morning" class="bg-gray-900">Matin (5h-12h)</option>
                <option value="afternoon" class="bg-gray-900">Après-midi (12h-18h)</option>
                <option value="evening" class="bg-gray-900">Soir (18h-24h)</option>
              </select>
              <div class="absolute right-3 md:right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Calendrier avec design premium -->
      <div class="rounded-xl md:rounded-3xl bg-gray-800/40 backdrop-blur-xl border border-gray-700/50 p-4 md:p-8 shadow-2xl">
        <FullCalendar
          ref="calendar"
          :options="calendarOptions"
          class="premium-green-calendar"
        />
      </div>
    </div>

    <!-- Modal pour les détails d'événement -->
    <div v-if="selectedEvent" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-3 md:p-4" @click="closeModal">
      <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl md:rounded-3xl p-5 md:p-8 max-w-md w-full border border-emerald-500/30 shadow-2xl transform transition-all max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-4 md:mb-6">
          <h3 class="text-xl md:text-2xl font-bold text-emerald-300">{{ selectedEvent.title }}</h3>
          <button @click="closeModal" class="w-7 h-7 md:w-8 md:h-8 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
            <svg class="w-3 h-3 md:w-4 md:h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
              <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
            </svg>
          </button>
        </div>
        
        <div class="space-y-3 md:space-y-4">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 md:w-5 md:h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
            </div>
            <div>
              <p class="text-xs md:text-sm text-gray-400">Coach</p>
              <p class="text-gray-200 font-semibold text-sm md:text-base">{{ selectedEvent.extendedProps?.coach }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-teal-500/20 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 md:w-5 md:h-5 text-teal-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </div>
            <div>
              <p class="text-xs md:text-sm text-gray-400">Places disponibles</p>
              <p class="text-gray-200 font-semibold text-sm md:text-base">{{ selectedEvent.extendedProps?.spotsLeft }}/{{ selectedEvent.extendedProps?.maxParticipants }}</p>
            </div>
          </div>
          
          <div class="bg-gray-700/50 rounded-xl md:rounded-2xl p-3 md:p-4 mt-4 md:mt-6">
            <p class="text-xs md:text-sm text-gray-400 mb-2">Description</p>
            <p class="text-gray-200 text-sm md:text-base">{{ selectedEvent.extendedProps?.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import frLocale from '@fullcalendar/core/locales/fr'

const props = defineProps({
  initialEvents: Array,
  initialCoaches: Array
})

const calendar = ref(null)
const events = ref(props.initialEvents || [])
const coaches = ref(props.initialCoaches || [])
const filters = ref({ coach: '', time: '' })
const selectedEvent = ref(null)
const showMobileFilters = ref(false)
const windowWidth = ref(window.innerWidth)

// Exemple d'événements si aucun n'est fourni
if (events.value.length === 0) {
  events.value = [
    {
      id: '1',
      title: 'Yoga Matinal',
      start: new Date(new Date().setHours(8, 0, 0, 0)),
      end: new Date(new Date().setHours(9, 0, 0, 0)),
      extendedProps: {
        coach: 'Sophie Martin',
        spotsLeft: 5,
        maxParticipants: 12,
        description: 'Séance de yoga doux pour bien commencer la journée'
      }
    },
    {
      id: '2',
      title: 'CrossFit Intense',
      start: new Date(new Date().setHours(18, 0, 0, 0)),
      end: new Date(new Date().setHours(19, 30, 0, 0)),
      extendedProps: {
        coach: 'Marc Dubois',
        spotsLeft: 3,
        maxParticipants: 10,
        description: 'Entraînement intensif pour tous niveaux'
      }
    }
  ]
}

// Exemple de coachs si aucun n'est fourni
if (coaches.value.length === 0) {
  coaches.value = ['Sophie Martin', 'Marc Dubois', 'Laura Petit', 'Thomas Moreau']
}

const calendarOptions = {
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek',
  locale: frLocale,
  headerToolbar: {
    left: 'prev,next',
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
  }
}

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
.premium-green-calendar {
  --fc-border-color: rgba(16, 185, 129, 0.2);
  --fc-page-bg-color: transparent;
  --fc-today-bg-color: rgba(16, 185, 129, 0.1);
}

.premium-green-calendar .fc-toolbar-title {
  font-weight: 900;
  font-size: 1.5rem;
  background: linear-gradient(135deg, #10b981, #14b8a6, #22c55e);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  letter-spacing: -0.02em;
}

@media (min-width: 768px) {
  .premium-green-calendar .fc-toolbar-title {
    font-size: 2rem;
  }
}

.premium-green-calendar .fc-event {
  background: linear-gradient(135deg, #10b981, #14b8a6) !important;
  border: 1px solid rgba(16, 185, 129, 0.3) !important;
  border-radius: 8px !important;
  font-size: 0.75rem !important;
  font-weight: 600 !important;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(8px);
  padding: 2px 4px;
  margin: 1px;
}

@media (min-width: 768px) {
  .premium-green-calendar .fc-event {
    border-radius: 12px !important;
    font-size: 0.85rem !important;
    padding: 4px 6px;
    margin: 2px;
  }
}

.premium-green-calendar .fc-event:hover {
  transform: translateY(-2px) scale(1.02);
  box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
  background: linear-gradient(135deg, #059669, #0d9488) !important;
}

.premium-green-calendar .fc-col-header-cell {
  background: rgba(31, 41, 55, 0.8);
  backdrop-filter: blur(10px);
  border-bottom: 2px solid rgba(16, 185, 129, 0.3);
}

.premium-green-calendar .fc-col-header-cell-cushion {
  text-transform: uppercase;
  font-weight: 800;
  color: #d1fae5;
  padding: 8px 0;
  font-size: 0.75rem;
  letter-spacing: 0.1em;
}

@media (min-width: 768px) {
  .premium-green-calendar .fc-col-header-cell-cushion {
    padding: 12px 0;
    font-size: 0.85rem;
  }
}

.premium-green-calendar .fc-daygrid-day-number,
.premium-green-calendar .fc-timegrid-slot-label {
  font-weight: 700;
  color: #e5e7eb;
  font-size: 0.8rem;
}

.premium-green-calendar .fc-daygrid-day {
  background: rgba(17, 24, 39, 0.6);
  border: 1px solid rgba(16, 185, 129, 0.1);
  transition: all 0.3s ease;
}

.premium-green-calendar .fc-daygrid-day:hover {
  background: rgba(16, 185, 129, 0.05);
}

.premium-green-calendar .fc-day-today {
  background: rgba(16, 185, 129, 0.15) !important;
  border: 1px solid rgba(16, 185, 129, 0.3) !important;
}

.premium-green-calendar .fc-button {
  background: linear-gradient(135deg, #374151, #4b5563) !important;
  border: 1px solid rgba(16, 185, 129, 0.3) !important;
  color: #f3f4f6 !important;
  text-transform: uppercase;
  font-weight: 700;
  border-radius: 8px !important;
  padding: 6px 12px !important;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  letter-spacing: 0.05em;
  font-size: 0.75rem;
}

@media (min-width: 768px) {
  .premium-green-calendar .fc-button {
    border-radius: 12px !important;
    padding: 8px 16px !important;
    font-size: 0.85rem;
  }
}

.premium-green-calendar .fc-button:hover {
  background: linear-gradient(135deg, #10b981, #14b8a6) !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
}

.premium-green-calendar .fc-button:focus {
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3) !important;
}

.premium-green-calendar .fc-button-active {
  background: linear-gradient(135deg, #059669, #0d9488) !important;
}

/* Améliorations pour mobile */
@media (max-width: 767px) {
  .premium-green-calendar .fc-toolbar {
    flex-direction: column;
    gap: 10px;
  }
  
  .premium-green-calendar .fc-toolbar-chunk {
    width: 100%;
    display: flex;
    justify-content: center;
  }
  
  .premium-green-calendar .fc-header-toolbar {
    margin-bottom: 10px !important;
  }
  
  .premium-green-calendar .fc-timegrid-slot-label {
    font-size: 0.7rem;
  }
}

/* Animation pour les filtres mobiles */
.grid {
  transition: grid-template-rows 0.3s ease;
}
</style>