<template>
  <AdminLayout title="Gestion des Réservations">
    <template #header>
      <h2 class="text-2xl font-bold text-green-400">Gestion des Réservations</h2>
    </template>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-gray-900 min-h-screen">
      <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-gray-700">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-700">
          <h3 class="text-lg leading-6 font-medium text-green-400">Toutes les réservations</h3>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-400 uppercase tracking-wider">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-400 uppercase tracking-wider">Cours</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-400 uppercase tracking-wider">Coach</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-400 uppercase tracking-wider">Date réservation</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-green-400 uppercase tracking-wider">Statut</th>
              </tr>
            </thead>
            <tbody class="bg-gray-900 divide-y divide-gray-700">
              <tr v-for="reservation in reservations.data" :key="reservation.id" class="hover:bg-gray-700/50 transition-colors duration-150 cursor-pointer">
                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                  <div class="flex flex-col">
                    <span class="font-semibold">{{ reservation.user?.name || 'Utilisateur inconnu' }}</span>
                    <span class="text-gray-400 text-sm">{{ reservation.user?.email || '' }}</span>
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                  <div>
                    <div class="font-semibold">{{ reservation.course?.name || 'Cours inconnu' }}</div>
                    <div class="text-gray-400 text-sm">
                      {{ formatTime(reservation.course?.start_time) }} - {{ formatTime(reservation.course?.end_time) }}
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                  {{ reservation.course?.coach_name || 'Coach inconnu' }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-gray-400 text-sm">
                  {{ formatDate(reservation.reservation_date) }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-green-900 text-green-300': reservation.status === 'confirmée',
                      'bg-yellow-900 text-yellow-300': reservation.status === 'en attente',
                      'bg-red-900 text-red-300': reservation.status === 'annulée',
                      'bg-gray-700 text-gray-300': !reservation.status
                    }"
                  >
                    {{ reservation.status || 'Inconnu' }}
                  </span>
                </td>
              </tr>

              
            </tbody>
          </table>
        </div>

        <div class="px-4 py-3 bg-gray-800 border-t border-gray-700 flex items-center justify-between sm:px-6">
          <button
            :disabled="!reservations.prev_page_url"
            @click="goToPage(reservations.prev_page_url)"
            class="px-4 py-2 text-sm font-medium text-green-400 bg-gray-900 border border-green-600 rounded-md disabled:opacity-50 hover:bg-green-700 hover:text-white transition"
          >
            Précédent
          </button>

          <div class="text-sm text-green-400">
            Page {{ reservations.current_page }} sur {{ reservations.last_page }}
          </div>

          <button
            :disabled="!reservations.next_page_url"
            @click="goToPage(reservations.next_page_url)"
            class="px-4 py-2 text-sm font-medium text-green-400 bg-gray-900 border border-green-600 rounded-md disabled:opacity-50 hover:bg-green-700 hover:text-white transition"
          >
            Suivant
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  reservations: {
    type: Object,
    default: () => ({ data: [], current_page: 1, last_page: 1 })
  }
});

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const formatTime = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const goToPage = (url) => {
  if (url) {
    router.get(url);
  }
};
</script>
