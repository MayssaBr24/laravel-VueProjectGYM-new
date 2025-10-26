<template>
  <div class="min-h-screen bg-gray-900 text-white">

    <!-- Header -->
    <ClientHeader :userName="userName" @logout="logout" />

    <!-- Section Statistiques -->
    <div class="max-w-6xl mx-auto px-6 pt-10">
      

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 ">
        <DashboardCard
          title="Mes Réservations"
          :value="stats?.reservations_count || 0"
          icon="calendar"
          gradient="emerald"
          :footer="`+${stats?.weekly_increase || 0} cette semaine`"
          class="card-animated pb-12"
        />

        <DashboardChartCard
          title="Heures par mois"
          icon="clock"
          :data="hoursChartData"
          chart-type="bar"
          footer="Derniers 6 mois"
          class="card-animated pb-12"
        />

        <DashboardCard
          title="Prochain cours"
          :value="nextCourse ? formatDate(nextCourse.start_time) : 'Aucun'"
          icon="calendar-check"
          gradient="purple"
          class="card-animated pb-12"
        />

        <DashboardCard
          title="Cours récents"
          :value="recentReservations?.length || 0"
          icon="check-circle"
          gradient="orange"
          footer="7 derniers jours"
          class="card-animated"
        />
      </div>

      <!-- Calendrier -->
      <div class="mt-10">
        <CalendrierCard class="card-animated" />
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="max-w-6xl mx-auto px-6 mt-12 flex flex-col lg:flex-row gap-8 pb-12">
      <!-- Cours à venir et Réservations -->
      <div class="lg:w-2/3 space-y-8">
        <ClientNextCourse :course="nextCourse" @cancel="cancelReservation" class="card-animated" />
        <ClientRecentReservations :reservations="recentReservations" @cancel="cancelReservation" class="card-animated" />
      </div>

      <!-- Scanner QR -->
      <div class="lg:w-1/3">
        <div class="sticky top-20">
          <ScanQrClient class="card-animated" />
        </div>
      </div>
    </div>

    <!-- Notifications -->
    <NewCourseAlert />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import ClientHeader from '@/Components/dashboardClient/ClientHeader.vue'
import DashboardChartCard from '@/Components/dashboardClient/DashboardChartCard.vue'
import DashboardCard from '@/Components/dashboardClient/DashboardCard.vue'
import ClientNextCourse from '@/Components/dashboardClient/NextCourseCard.vue'
import ClientRecentReservations from '@/Components/dashboardClient/RecentReservationsList.vue'
import NewCourseAlert from '@/Components/Notifications/NewCourseAlert.vue'
import CalendrierCard from '@/Components/dashboardClient/CalendrierCard.vue'
import ScanQrClient from '@/Components/dashboardClient/ScanQrClient.vue'

const props = usePage().props
const stats = computed(() => props.stats || {})
const nextCourse = computed(() => props.nextCourse || null)
const recentReservations = computed(() => props.recentReservations || [])
const userName = props.auth?.user?.name || 'Utilisateur'

const formatDate = (datetime) => {
  return new Date(datetime).toLocaleDateString('fr-FR', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  })
}

const hoursChartData = computed(() => ({
  labels: Object.keys(props.stats?.hours_by_month || {}),
  datasets: [
    {
      label: 'Heures',
      data: Object.values(props.stats?.hours_by_month || {}),
      backgroundColor: 'rgba(16, 185, 129, 0.7)',
      borderColor: 'rgba(16, 185, 129, 1)',
      borderWidth: 1,
      borderRadius: 6,
    },
  ],
}))

const reloadDashboard = () => {
  Inertia.reload({ only: ['recentReservations', 'stats', 'nextCourse'] })
}

const cancelReservation = (id) => {
  if (!confirm("Confirmer l'annulation ?")) return
  Inertia.delete(route('reservations.cancel', { id }), {
    onSuccess: reloadDashboard,
    onError: () => alert("Erreur lors de l'annulation."),
  })
}

const logout = () => {
  Inertia.post(route('logout'))
}
</script>

<style scoped>
/* --- Effets modernes mais discrets --- */
.card-animated {
  @apply bg-gray-800/70 backdrop-blur-md rounded-2xl shadow-md p-6 
  transition-all duration-300 ease-in-out hover:shadow-emerald-500/20 hover:scale-[1.02];
}

/* Animation d’apparition fluide */
.card-animated {
  animation: fadeInUp 0.6s ease-in-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
