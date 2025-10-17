<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <DashboardHeader :user-name="$page.props.auth?.user?.name" @logout="logout" />

    <div class="min-h-screen bg-gray-900 pb-12">
      <!-- Statistiques -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <StatsCard
            title="Membres Actifs"
            :value="membersData.length"
            subtitle="+12% ce mois"
            color="emerald"
            :icon-component="UsersIcon"
            :show-trend="true"
          />
          <StatsCard
            title="Cours Programmés"
            :value="coursesData.length"
            subtitle="Cette semaine"
            color="blue"
            :icon-component="CalendarIcon"
          />
          <StatsCard
            title="Revenus (30j)"
            :value="totalRevenue + ' TND'"
            subtitle="+8% vs mois dernier"
            color="purple"
            :icon-component="CurrencyIcon"
            :show-trend="true"
          />

          <StatsCard
            title="Réservations"
            :value="reservationStats.total"
            color="orange"
            :icon-component="TicketIcon"
            :additional-info="[
              { label: 'Aujourd\'hui', value: reservationStats.today, valueClass: 'text-orange-400' },
              { label: 'À venir', value: reservationStats.upcoming, valueClass: 'text-emerald-400' }
            ]"
          />
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="flex flex-col lg:flex-row gap-8">

          <!-- Colonne principale (table + calendrier) -->
          <div class="lg:w-2/3 space-y-6">
            <!-- Tabs -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm">
              <TabNavigation :active-tab="activeTab" :tabs="tabs" @tab-change="activeTab = $event" />
              <div class="p-8 bg-gray-800/30">
                <MembersTab
                  v-if="activeTab === 'members'"
                  :members="filteredMembers"
                  :loading="loadingMembers"
                  :error-message="errorMessage"
                  :search-term="searchTerm"
                  @search="searchTerm = $event"
                  @delete-member="deleteMember"
                />
                <CoursesTab
                  v-if="activeTab === 'classes'"
                  :courses="coursesData"
                  :loading="loadingCourses"
                  @edit-course="editCourse"
                  @delete-course="confirmDelete"
                />
               <PaymentsTab
                  v-if="activeTab === 'payments'"
                  :payments="filteredPayments"
                  :payment-filter="paymentFilter"
                  @filter-change="paymentFilter = $event"
                  @export="exportPayments"
                />

              </div>
            </div>

           
          </div>

          <!-- Colonne latérale droite -->
            <DashboardSidebar
              :weekly-courses="weeklyCourses"
              :latest-reservations="latestReservations"
            />
            <PendingAttendances
              :pendingAttendances="pendingAttendances"
              @update-attendance="fetchPendingAttendances"
            />
          </div>
      </div>
    </div>

    <!-- Modal de confirmation -->
    <DeleteConfirmationModal
      :show="showDeleteDialog"
      :message="`Êtes-vous sûr de vouloir supprimer le cours ${courseToDelete?.name} ?`"
      title="Supprimer le cours"
      @confirm="deleteCourse"
      @cancel="showDeleteDialog = false"
    />
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import DashboardHeader from '@/Components/dashboardAdmin/DashboardHeader.vue';
import StatsCard from '@/Components/dashboardAdmin/StatsCard.vue';
import TabNavigation from '@/Components/dashboardAdmin/TabNavigation.vue';
import MembersTab from '@/Components/dashboardAdmin/MembersTab.vue';
import CoursesTab from '@/Components/dashboardAdmin/CoursesTab.vue';
import PaymentsTab from '@/Components/dashboardAdmin/PaymentsTab.vue';
import DashboardSidebar from '@/Components/dashboardAdmin/DashboardSidebar.vue';
import DeleteConfirmationModal from '@/Components/dashboardAdmin/DeleteConfirmationModal.vue';
import PendingAttendances from '@/Components/dashboardAdmin/PendingAttendances.vue';

import { UsersIcon, CalendarIcon, CurrencyDollarIcon as CurrencyIcon, TicketIcon } from '@heroicons/vue/24/outline';

const activeTab = ref('members');
const showDeleteDialog = ref(false);
const courseToDelete = ref(null);
const searchTerm = ref('');
const paymentFilter = ref('');
const loadingMembers = ref(false);
const loadingCourses = ref(false);
const errorMessage = ref('');

const page = usePage();
const membersData = ref([]);
const latestReservations = ref(page.props.latestReservations || []);
const reservationStats = ref(page.props.reservationStats || { total: 0, today: 0, upcoming: 0 });
const coursesData = ref(page.props.courses || []);
defineProps({
  weeklyCourses: Array,
  latestReservations: Array,
   totalRevenue: {
    type: Number,
    default: 0
  },
 pendingAttendances: {
    type: Array,
    default: () => []
  }
});

//  Prend les paiements depuis props backend
const allPayments = ref(page.props.payments || []);

//  Filtrage
const filteredPayments = computed(() => {
  if (!paymentFilter.value) return allPayments.value;
  return allPayments.value.filter(payment => payment.status === paymentFilter.value);
});
const markAttendance = (payment) => {
  router.post(`/admin/attendances/${payment.id}/verify`, {}, {
    onSuccess: () => {
      // Inertia gère automatiquement la mise à jour des props
    }
  })
}


const fetchPendingAttendances = () => {
  router.reload({ only: ['pendingAttendances'] });
};


const tabs = [
  { id: 'members', label: 'Membres', icon: UsersIcon },
  { id: 'classes', label: 'Cours', icon: CalendarIcon },
  { id: 'payments', label: 'Paiements', icon: CurrencyIcon },
];

const filteredMembers = computed(() => {
  if (!searchTerm.value) return membersData.value;
  return membersData.value.filter(member =>
    member.name?.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
    member.email?.toLowerCase().includes(searchTerm.value.toLowerCase())
  );
});



const logout = () => {
  router.post('/logout');
};

const editCourse = (course) => {
  router.visit(`/courses/${course.id}/edit`);
};

const confirmDelete = (course) => {
  courseToDelete.value = course;
  showDeleteDialog.value = true;
};

const deleteCourse = () => {
  if (courseToDelete.value) {
    router.delete(`/courses/${courseToDelete.value.id}`, {
      onSuccess: () => {
        showDeleteDialog.value = false;
        courseToDelete.value = null;
      },
    });
  }
};

const deleteMember = (member) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')) {
    router.delete(`/client/members/${member.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        fetchMembers();
      },
    });
  }
};

const exportPayments = () => {
  console.log('Export des paiements...');
};

const fetchMembers = async () => {
  loadingMembers.value = true;
  errorMessage.value = '';
  try {
    const response = await fetch('/admin/members', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    });

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const data = await response.json();
    membersData.value = data || [];
  } catch (error) {
    console.error('Erreur lors de la récupération des membres:', error);
    errorMessage.value = 'Erreur lors du chargement des membres. Veuillez réessayer.';
    membersData.value = [];
  } finally {
    loadingMembers.value = false;
  }
};

onMounted(() => {
  fetchMembers();
});





</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.transition-all {
  transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(55, 65, 81, 0.3);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: rgba(16, 185, 129, 0.5);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(16, 185, 129, 0.7);
}

input:focus, textarea:focus, select:focus {
  transform: translateY(-1px);
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1), 0 4px 12px -2px rgba(16, 185, 129, 0.25);
}
</style>
