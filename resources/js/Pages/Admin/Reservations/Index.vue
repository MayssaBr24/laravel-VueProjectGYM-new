<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
    <!-- Header -->
    <div class="border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-sm sticky top-0 z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <div class="flex items-center gap-3 mb-2">
              <div class="p-2 bg-blue-500/10 rounded-lg">
                <BookOpen class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" />
              </div>
              <h1 class="text-2xl sm:text-3xl font-bold text-white">Gestion des Réservations</h1>
            </div>
            <p class="text-slate-400 text-xs sm:text-sm">Gérez et suivez toutes les réservations de cours</p>
          </div>
          
          <!-- Bouton Export PDF -->
          <button
            @click="exportToPdf"
            class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium"
          >
            <Download class="w-4 h-4" />
            Exporter PDF
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
      <!-- Filtres et Total -->
      <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-baseline gap-3">
          <p class="text-slate-400 text-sm font-medium">Total Réservations</p>
          <p class="text-4xl sm:text-5xl font-bold text-blue-400">{{ reservations.data.length }}</p>
        </div>

        <!-- Filtre Coach -->
        <div class="flex items-center gap-3">
          <label class="text-slate-300 text-sm font-medium">Filtrer par coach:</label>
          <select 
            v-model="selectedCoach"
            @change="filterByCoach"
            class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px]"
          >
            <option value="">Tous les coachs</option>
            <option v-for="coach in coaches" :key="coach" :value="coach">
              {{ coach }}
            </option>
          </select>
        </div>
      </div>

      <!-- Table Section -->
      <div class="bg-slate-800/30 border border-slate-700/50 rounded-lg backdrop-blur-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-full">
            <thead>
              <tr class="border-b border-slate-700/50 bg-slate-900/50">
                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                  Client
                </th>
                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                  Cours
                </th>
                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                  Coach
                </th>
                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                  Date
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
              <tr v-if="reservations.data.length === 0">
                <td :colspan="4" class="px-3 sm:px-6 py-8 sm:py-12 text-center">
                  <p class="text-slate-400 text-sm">Aucune réservation trouvée</p>
                </td>
              </tr>
              <tr v-for="reservation in reservations.data" :key="reservation.id" class="hover:bg-slate-700/20 transition-colors group">
                <!-- Client -->
                <td class="px-3 sm:px-6 py-3 sm:py-4">
                  <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                      <User class="w-4 h-4 sm:w-5 sm:h-5 text-blue-400" />
                    </div>
                    <div class="min-w-0">
                      <p class="text-white font-medium text-xs sm:text-sm truncate">
                        {{ reservation.user?.name || 'Utilisateur inconnu' }}
                      </p>
                      <p class="text-slate-400 text-xs truncate">{{ reservation.user?.email || 'N/A' }}</p>
                    </div>
                  </div>
                </td>

                <!-- Cours -->
                <td class="px-3 sm:px-6 py-3 sm:py-4">
                  <div class="min-w-0">
                    <p class="text-white font-medium text-xs sm:text-sm truncate">
                      {{ reservation.course?.name || 'Cours inconnu' }}
                    </p>
                    <div class="flex items-center gap-1 text-slate-400 text-xs mt-1">
                      <Clock class="w-3 h-3 flex-shrink-0" />
                      <span class="truncate">
                        {{ formatTime(reservation.course?.start_time) }} - {{ formatTime(reservation.course?.end_time) }}
                      </span>
                    </div>
                  </div>
                </td>

                <!-- Coach -->
                <td class="px-3 sm:px-6 py-3 sm:py-4">
                  <p class="text-slate-300 text-sm">{{ reservation.course?.coach_name || 'Coach inconnu' }}</p>
                </td>

                <!-- Date -->
                <td class="px-3 sm:px-6 py-3 sm:py-4">
                  <div class="flex items-center gap-2 text-slate-300 text-xs sm:text-sm">
                    <Calendar class="w-3 h-3 sm:w-4 sm:h-4 text-slate-500 flex-shrink-0" />
                    <span class="truncate">{{ formatDate(reservation.reservation_date) }}</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="border-t border-slate-700/50 bg-slate-900/30 px-3 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">
          <button
            :disabled="!reservations.prev_page_url"
            @click="previousPage"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-3 sm:px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 disabled:opacity-50 disabled:cursor-not-allowed text-slate-300 text-xs sm:text-sm font-medium transition-colors"
          >
            <ChevronLeft class="w-4 h-4" />
            Précédent
          </button>

          <div class="flex items-center gap-2">
            <span class="text-slate-400 text-xs sm:text-sm">
              Page <span class="font-semibold text-white">{{ reservations.current_page }}</span> sur
              <span class="font-semibold text-white">{{ reservations.last_page }}</span>
            </span>
          </div>

          <button
            :disabled="!reservations.next_page_url"
            @click="nextPage"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-3 sm:px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 disabled:opacity-50 disabled:cursor-not-allowed text-slate-300 text-xs sm:text-sm font-medium transition-colors"
          >
            Suivant
            <ChevronRight class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight, Calendar, Clock, User, BookOpen, Download } from 'lucide-vue-next'

interface User {
  name: string
  email: string
}

interface Course {
  name: string
  start_time: string
  end_time: string
  coach_name: string
}

interface Reservation {
  id: number
  user?: User
  course?: Course
  reservation_date: string
  status: string
}

interface ReservationsData {
  data: Reservation[]
  current_page: number
  last_page: number
  prev_page_url: string | null
  next_page_url: string | null
}

const props = defineProps<{
  reservations?: ReservationsData
  coaches?: string[]
  filters?: {
    coach?: string
  }
}>()

const reservations = ref<ReservationsData>(
  props.reservations || {
    data: [],
    current_page: 1,
    last_page: 1,
    prev_page_url: null,
    next_page_url: null,
  }
)

const coaches = ref<string[]>(props.coaches || [])
const selectedCoach = ref<string>(props.filters?.coach || '')

const formatDate = (dateString: string): string => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('fr-FR', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  })
}

const formatTime = (dateString: string): string => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit',
  })
}

const filterByCoach = () => {
  router.get(route('admin.reservations.index'), {
    coach: selectedCoach.value
  }, {
    preserveState: true,
    replace: true
  })
}

const previousPage = () => {
  if (reservations.value.prev_page_url) {
    router.visit(reservations.value.prev_page_url)
  }
}

const nextPage = () => {
  if (reservations.value.next_page_url) {
    router.visit(reservations.value.next_page_url)
  }
}

const exportToPdf = () => {
  const url = route('admin.reservations.export.pdf') + (selectedCoach.value ? `?coach=${encodeURIComponent(selectedCoach.value)}` : '')
  window.location.href = url
}

// Mettre à jour les données quand les props changent
watch(() => props.reservations, (newReservations) => {
  if (newReservations) {
    reservations.value = newReservations
  }
})

watch(() => props.filters, (newFilters) => {
  if (newFilters?.coach !== undefined) {
    selectedCoach.value = newFilters.coach || ''
  }
})
</script>