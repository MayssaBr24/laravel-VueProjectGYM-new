<template>
  <div class="min-h-screen bg-gray-900 text-white">

    <!-- Header -->
    <ClientHeader :userName="userName" @logout="logout" />
    

    <!-- Statistiques -->
    <div class="max-w-7xl mx-auto px-4 pt-10">
      <h2 class="text-white text-3xl font-bold mb-6">Statistiques</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <DashboardCard
          title="Mes Réservations"
          :value="stats?.reservations_count || 0"
          icon="calendar"
          gradient="emerald"
          :footer="`+${stats?.weekly_increase || 0} cette semaine`"
        />

         <DashboardChartCard
        title="Heures par mois"
        icon="clock"
        :data="hoursChartData"
        chart-type="bar"
        footer="Derniers 6 mois"
      />
        <DashboardCard
          title="Prochain cours"
          :value="nextCourse ? formatDate(nextCourse.start_time) : 'Aucun'"
          icon="calendar-check"
          gradient="purple"
        />

        <DashboardCard
          title="Cours Réservés récemment"
          :value="recentReservations?.length || 0"
          icon="check-circle"
          gradient="orange"
          footer="7 derniers jours"
        />
          <ClientPendingPayments 
          :payments="$page.props.pendingPayments" 
          />
           <CalendrierCard />

      </div>
    </div>

    <!-- Contenu principal -->
    <div class="max-w-7xl mx-auto px-4 mt-10">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Cours à venir -->
        <div class="lg:w-2/3 space-y-8">
          <ClientNextCourse :course="nextCourse" @cancel="cancelReservation" />
          <ClientRecentReservations :reservations="recentReservations" @cancel="cancelReservation" />
        </div>
       
        <!-- Espace pour autre contenu -->
        <div class="lg:w-1/3">
          <!-- Placeholder -->
        </div>
      </div>
    </div>
    <NewCourseAlert />
   

    
<div class="max-w-7xl mx-auto px-4 mt-6">
    <ScanQrClient />
</div>

 
  </div>
  
</template>

<script setup>

import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ClientHeader from '@/Components/dashboardClient/ClientHeader.vue';
import DashboardChartCard from '@/Components/dashboardClient/DashboardChartCard.vue';
import DashboardCard from '@/Components/dashboardClient/DashboardCard.vue';
import ClientNextCourse from '@/Components/dashboardClient/NextCourseCard.vue';
import ClientRecentReservations from '@/Components/dashboardClient/RecentReservationsList.vue';
import ClientPendingPayments from '@/Components/dashboardClient/ClientPendingPayments.vue';
import NewCourseAlert from '@/Components/Notifications/NewCourseAlert.vue'; 
import FlashNotification from '@/Components/Notifications/FlashNotification.vue'; 
import toggleNotifications from '@/Components/Notifications/ToggleNotifications.vue';
import { Link} from '@inertiajs/vue3'
import CalendrierCard from '@/Components/Coach/CalendrierCard.vue';
import ScanQrClient from '@/Components/dashboardClient/ScanQrClient.vue'; 



const props = usePage().props;

const stats = computed(() => props.stats || {});
const nextCourse = computed(() => props.nextCourse || null);
const recentReservations = computed(() => props.recentReservations || []);

const userName = props.auth?.user?.name || 'Utilisateur';

const formatDate = (datetime) => {
  return new Date(datetime).toLocaleDateString('fr-FR', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
};
const reloadDashboard = () => {
  Inertia.reload({ only: ['recentReservations', 'stats', 'nextCourse'] })
}
const cancelReservation = (id) => {
  if (!confirm("Confirmer l'annulation ?")) return
  Inertia.delete(route('reservations.cancel', { id }), {
    onSuccess: reloadDashboard,
    onError: () => alert("Erreur lors de l'annulation.")
  })
}
const hoursChartData = computed(() => ({
  labels: Object.keys(props.stats?.hours_by_month || {}),
  datasets: [{
    label: 'Heures',
    data: Object.values(props.stats?.hours_by_month || {}),
    backgroundColor: 'rgba(16, 185, 129, 0.7)',
    borderColor: 'rgba(16, 185, 129, 1)',
    borderWidth: 1,
  }]
}));

const logout = () => {
  Inertia.post(route('logout'));
};
</script>
