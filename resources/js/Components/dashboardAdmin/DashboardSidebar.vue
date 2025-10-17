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
                {{ formatDateTime(reservation.start_time) }}
              </p>
            </div>
            <div>
              <span
                :class="[
                  'px-2 py-1 rounded',
                  reservation.status === 'confirmed'
                    ? 'bg-green-600'
                    : 'bg-gray-600',
                ]"
              >
                {{ reservation.status }}
              </span>
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
      class="bg-gradient-to-br from-indigo-800 to-indigo-900 rounded-2xl border border-indigo-700/50 overflow-hidden shadow-2xl backdrop-blur-sm"
    >
      <div class="px-6 py-4 border-b border-indigo-700/50 bg-indigo-800/50">
        <h3 class="text-lg font-bold text-white">Scanner Présence</h3>
      </div>
      <div class="p-6">
        <p class="text-sm text-indigo-200 mb-4">
          Scanner les QR codes des membres pour enregistrer leur présence aux cours
        </p>
        <a
          :href="route('admin.scan.qr')"
          class="w-full flex items-center justify-center px-4 py-3 bg-white/10 hover:bg-white/20 border border-indigo-300/30 rounded-xl text-sm font-semibold text-white transition-all duration-300 backdrop-blur-sm"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
          </svg>
          Ouvrir le Scanner
        </a>
      </div>
    </div>

    <!-- Activité du Mois -->
    <div
      class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-500 to-emerald-700 rounded-2xl shadow-2xl"
    >
      <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
      <div class="relative px-6 py-6">
        <h3 class="text-lg font-bold text-white mb-6">Activité du Mois</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-emerald-100">Nouveaux membres</p>
            <p class="text-lg font-bold text-white">24</p>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-emerald-100">Revenus totaux</p>
            <p class="text-lg font-bold text-white">3,450 €</p>
          </div>
          <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-emerald-100">Taux de remplissage</p>
            <p class="text-lg font-bold text-white">78%</p>
          </div>
        </div>
        <div class="mt-6">
          <button
            class="w-full px-4 py-3 bg-white/20 hover:bg-white/30 border border-white/30 rounded-xl text-sm font-semibold text-white transition-all duration-300 backdrop-blur-sm"
          >
            Voir le rapport complet
          </button>
        </div>
      </div>
    </div>
    <!-- Nouvelle Card pour votre calendrier -->
<div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl border border-orange-400/50 overflow-hidden shadow-2xl backdrop-blur-sm">
  <div class="px-6 py-4 border-b border-orange-400/50 bg-orange-500/50">
    <h3 class="text-lg font-bold text-white">Suivre vos cours</h3>
  </div>
  <div class="p-6">
    <p class="text-sm text-amber-100 mb-4">
      Ouvrir votre calendrier pour avoir plus de cours
    </p>
    <Link
      href="/calendar"
      class="w-full flex items-center justify-center px-4 py-3 bg-white/10 hover:bg-white/20 border border-amber-300/40 rounded-xl text-sm font-semibold text-white transition-all duration-300 backdrop-blur-sm"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      Ouvrir le Calendrier
    </Link>
  </div>
</div>

    <!-- Alerte -->
    <div
      class="bg-gradient-to-br from-red-500/20 to-red-600/20 border border-red-500/30 rounded-2xl p-6 backdrop-blur-sm"
    >
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 text-red-400"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-semibold text-red-300">Attention</h3>
          <p class="text-sm text-red-200 mt-1">
            3 abonnements expirent cette semaine
          </p>
          <button
            class="mt-3 text-sm font-medium text-red-400 hover:text-red-300 transition-colors"
          >
            Voir les détails →
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link} from '@inertiajs/vue3'

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
</script>
