<template>
  <div class="lg:w-1/3 space-y-6">
  
    <!-- Planning Hebdomadaire -->
    <div
      class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm"
    >
      <div class="px-6 py-4 border-b border-gray-700/50 bg-gray-800/50">
        <h3 class="text-lg font-bold text-white">Planning Hebdomadaire</h3>
      </div>

      <div
        class="p-6 divide-y divide-gray-700/50 max-h-[400px] overflow-y-auto"
      >
        <template v-if="!weeklyCourses || weeklyCourses.length === 0">
          <div class="text-center text-gray-400 py-8">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-16 w-16 mx-auto mb-4 text-gray-600"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
            <p class="text-lg">Aucun cours cette semaine</p>
          </div>
        </template>

        <template v-else>
          <div
            v-for="course in weeklyCourses"
            :key="course.id"
            class="py-4"
          >
            <div
              class="flex justify-between items-center mb-1"
            >
              <p class="text-white font-semibold">{{ course.name }}</p>
              <p class="text-sm text-gray-400">
                {{ formatDate(course.start_time) }}
              </p>
            </div>
            <p class="text-sm text-gray-400 mb-1">
              {{ formatTime(course.start_time) }} - {{ formatTime(course.end_time) }} |
              Coach :
              <span class="text-emerald-400"
                >{{ course.coach?.name || course.coach_name || 'N/A' }}</span
              >
            </p>
            <p class="text-xs text-gray-500 italic">
              {{ course.description }}
            </p>
          </div>
        </template>
      </div>
    </div>

    <!-- Dernières Réservations -->
    <div
      class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm"
    >
      <div class="px-6 py-4 border-b border-gray-700/50 bg-gray-800/50">
        <h3 class="text-lg font-bold text-white">Dernières Réservations</h3>
      </div>
      <div
        class="divide-y divide-gray-700/50 max-h-96 overflow-y-auto"
      >
        <template v-if="!latestReservations || latestReservations.length === 0">
          <div class="px-6 py-8 text-center text-gray-400">
            Aucune réservation récente
          </div>
        </template>

        <template v-else>
          <div
            v-for="reservation in latestReservations"
            :key="reservation.id"
            class="px-6 py-4 text-white flex justify-between items-center"
          >
            <div>
              <p class="font-semibold">{{ reservation.user.name }}</p>
              <p class="text-gray-400 text-sm">{{ reservation.course.name }}</p>
              <p class="text-gray-400 text-xs">
                {{ formatDateTime(reservation.reservation_date) }}
              </p>
            </div>
            <div>
              
            </div>
          </div>
        </template>
      </div>
      <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-800/50">
        <a
          href="/admin/reservations"
          class="text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition-colors"
          >Voir toutes les réservations →</a
        >
      </div>
    </div>
    <!-- Nouvelle Card Scanner QR Code -->
<div
  class="bg-gradient-to-br from-emerald-800 to-blue-800 rounded-2xl border border-emerald-700/50 overflow-hidden shadow-2xl backdrop-blur-sm"
>
  <div class="px-6 py-4 border-b border-emerald-700/50 bg-emerald-800/50">
    <h3 class="text-lg font-bold text-white">Scanner Présence</h3>
  </div>
  <div class="p-6">
    <p class="text-sm text-emerald-200 mb-4">
      Scanner les QR codes des membres pour enregistrer leur présence aux cours
    </p>
    <a
      :href="route('admin.scan.qr')"
      class="w-full flex items-center justify-center px-4 py-3 bg-white/10 hover:bg-white/20 border border-emerald-300/30 rounded-xl text-sm font-semibold text-white transition-all duration-300 backdrop-blur-sm hover:shadow-lg hover:shadow-emerald-500/20"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
      </svg>
      Ouvrir le Scanner
    </a>
  </div>
</div>

  <!-- Card avec le même dégradé bleu que le calendrier -->
  <div class="bg-gradient-to-br from-emerald-800 via-emerald-700 to-blue-800 rounded-2xl border border-emerald-500/30 overflow-hidden shadow-2xl backdrop-blur-sm">
    <div class="px-6 py-4 border-b border-emerald-500/30 bg-gray-900/40">
      <h3 class="text-lg font-bold text-white">Calendrier Administrateur</h3>
    </div>
    <div class="p-6">
      <p class="text-sm text-gray-200 mb-4">
        Ouvrir votre calendrier pour avoir plus de cours
      </p>
      <Link
        href="/calendar"
        class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 border border-emerald-400/30 rounded-xl text-sm font-semibold text-white transition-all duration-300 transform hover:scale-105 shadow-lg shadow-emerald-500/25"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Ouvrir le Calendrier
      </Link>
    </div>
  </div>


   </div>
</template>

<script setup>
import { Link} from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import axios from 'axios'
defineProps({
  weeklyCourses: {
    type: Array,
    default: () => [],
  },
  latestReservations: {
    type: Array,
    default: () => [],
  },
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
  });
};

const formatTime = (date) => {
  return new Date(date).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit',
  });
};

const formatDateTime = (date) => {
  return new Date(date).toLocaleString('fr-FR', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const latestAction = ref(null)

onMounted(async () => {
  const response = await axios.get('/admin/historique/latest')
  latestAction.value = response.data
})
</script>
