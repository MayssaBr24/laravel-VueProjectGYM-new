<template>
  <AppLayout title="Réservation de Cours">
    <template #header>
      <h2 class="text-3xl font-bold bg-gradient-to-r from-green-400 to-teal-400 bg-clip-text text-transparent">Réservation de cours</h2>
    </template>

    <div class="py-10 bg-gray-900 min-h-screen">
      <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
        <!-- Filtres améliorés -->
        <div class="bg-gray-800/50 backdrop-blur-sm p-6 rounded-xl border border-gray-700 shadow-lg">
          <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1">
              <input
                v-model="search"
                type="text"

          
                placeholder="Rechercher un cours, coach..."
                class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 text-green-300 border border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-transparent placeholder-gray-500"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <MagnifyingGlassIcon class="h-5 w-5 text-green-400/80" />
              </div>
            </div>
            <select
              v-model="filter"
              class="flex-1 md:flex-none px-4 py-2.5 bg-gray-900/70 text-green-300 border border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-transparent"
            >
              <option value="all">Tous les cours</option>
              <option value="available">Places disponibles</option>
              
            </select>
          </div>
        </div>

        <!-- Liste des cours -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="course in filteredCourses"
            :key="course.id"
            class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg hover:shadow-green-500/10 transition-all duration-300 group"
          >
            <div class="p-6 space-y-5">
              <div class="flex items-start justify-between">
                <div>
                  <h3 class="text-xl font-bold text-green-400 group-hover:text-green-300 transition-colors">
                    {{ course.name }}
                  </h3>
                                <p class="text-gray-400 text-sm">{{ course.description || 'Aucune description.' }}</p>

                  <!-- Affichage du coach avec icône -->
                  <div class="flex items-center mt-1 text-sm text-gray-400">
                    <UserIcon class="h-4 w-4 mr-1 text-green-400" />
                    <span>{{ course.coach_name  }}</span>
                  </div>
                </div>
                <span
                  class="px-2 py-1 text-xs rounded-full font-medium"
                  :class="{
                    'bg-green-900/30 text-green-400': new Date(course.start_time) > new Date(),
                    'bg-gray-700 text-gray-400': new Date(course.start_time) <= new Date()
                  }"
                >
                  {{ new Date(course.start_time) > new Date() ? 'À venir' : 'Terminé' }}
                </span>
              </div>


              <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3 text-green-300">
                  <CalendarIcon class="h-5 w-5 text-green-400/80 flex-shrink-0" />
                  <span>{{ formatDate(course.start_time) }}</span>
                </div>
                <div class="flex items-center gap-3 text-green-300">
                  <ClockIcon class="h-5 w-5 text-green-400/80 flex-shrink-0" />
                  <span>{{ formatTime(course.start_time) }} - {{ formatTime(course.end_time) }}</span>
                </div>
              </div>

              <!-- Progression améliorée -->
              <div class="pt-2">
                <div class="flex justify-between text-xs mb-1.5 font-medium">
                  <span class="text-green-400">Places</span>
                  <span class="text-gray-400">
                    {{ course.max_participants - course.current_participants }} disponibles
                  </span>
                </div>
                <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                  <div
                    class="h-2 bg-gradient-to-r from-green-400 to-teal-400"
                    :style="`width: ${(course.current_participants / course.max_participants) * 100}%`"
                  ></div>
                </div>
              </div>

              <!-- Boutons améliorés -->
             <div class="flex gap-3 pt-4">
  <!-- Bouton Réserver -->
  <button
    v-if="!course.user_has_reserved"
    @click="confirmReserve(course)"
    :disabled="!canReserve(course) || course.participants_count >= course.max_participants"
    class="w-full py-2 px-4 bg-gradient-to-r from-green-600 to-teal-600 
           hover:from-green-500 hover:to-teal-500 
           disabled:opacity-50 disabled:cursor-not-allowed 
           rounded-lg text-white font-medium 
           transition-all duration-300 shadow-md disabled:shadow-none"
  >
    <!-- Texte dynamique -->
    {{ course.participants_count >= course.max_participants 
        ? 'Complet' 
        : 'Réserver' }}
  </button>

  <!-- Bouton Annuler -->
  <button
    v-else
    @click="openCancelModal(course.reservation_id)"
    class="w-full py-2 px-4 bg-gradient-to-r from-red-600 to-rose-600 
           hover:from-red-500 hover:to-rose-500 
           rounded-lg text-white font-medium transition-all shadow-md"
  >
    Annuler
  </button>
</div>

            </div>
          </div>
        </div>

        <!-- Aucun cours -->
        <div
          v-if="filteredCourses.length === 0"
          class="bg-gray-800/50 border border-gray-700 p-6 rounded-xl text-center"
        >
          <p class="text-green-400/80 font-medium">Aucun cours ne correspond à vos critères.</p>
          <Link
            href="/cours"
            class="mt-2 inline-block text-sm bg-gradient-to-r from-green-400 to-teal-400 bg-clip-text text-transparent hover:from-green-300 hover:to-teal-300 transition-all duration-300"
          >
            Voir tous les cours disponibles
          </Link>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation d'annulation -->
    <div v-if="showCancelModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6">
          <h3 class="text-xl font-bold text-green-400 mb-2">Confirmer l'annulation</h3>
          <p class="text-gray-300">
            Êtes-vous sûr de vouloir annuler votre réservation pour ce cours ?
          </p>
        </div>
        <div class="bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
          <button
            @click="showCancelModal = false"
            class="px-4 py-2 rounded-lg border border-gray-500 text-gray-300 hover:bg-gray-600/50 transition-colors"
          >
            Non, garder
          </button>
          <button
            @click="confirmCancel"
            class="px-4 py-2 rounded-lg bg-gradient-to-r from-red-600 to-rose-600 text-white hover:from-red-500 hover:to-rose-500 transition-colors font-medium"
          >
            Oui, annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de réservation -->
    <div
      v-if="showReserveModal"
      class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    >
      <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6">
          <h3 class="text-xl font-bold text-green-400 mb-2">Confirmer la réservation</h3>
          <p class="text-gray-300">
            Souhaitez-vous réserver le cours <span class="text-green-300 font-medium">{{ selectedCourse?.name }}</span> avec 
            <span class="text-green-300 font-medium">{{ selectedCourse?.coach_name || 'le coach' }}</span> ?
          </p>
        </div>
        <div class="bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
          <button
            @click="showReserveModal = false"
            class="px-4 py-2 rounded-lg border border-gray-500 text-gray-300 hover:bg-gray-600 transition-colors"
          >
            Annuler
          </button>
          <button
            @click="reserveConfirmed"
            class="px-4 py-2 rounded-lg bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-500 hover:to-teal-500 text-white font-medium transition-all shadow-md"
          >
            Confirmer
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import {
  MagnifyingGlassIcon,
  UserIcon,
  CalendarIcon,
  ClockIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({ 
  courses: {
    type: Array,
    default: () => [],
    required: true
  } 
});

const search = ref('');
const filter = ref('all');
const showReserveModal = ref(false);
const showCancelModal = ref(false);
const selectedCourse = ref(null);
const reservationToCancel = ref(null);

const filteredCourses = computed(() => {
  let results = props.courses;

  // Filtre de recherche
  if (search.value) {
    const term = search.value.toLowerCase();
    results = results.filter(course =>
      course.name.toLowerCase().includes(term) ||
      (course.description && course.description.toLowerCase().includes(term)) ||
      (course.coach_name && course.coach_name.toLowerCase().includes(term))
    );
  }

  // Filtres supplémentaires
  switch (filter.value) {
    case 'available':
      results = results.filter(course => course.current_participants < course.max_participants);
      break;
    case 'upcoming':
      results = results.filter(course => new Date(course.start_time) > new Date());
      break;
    case 'past':
      results = results.filter(course => new Date(course.start_time) <= new Date());
      break;
  }

  return results.sort((a, b) => new Date(a.start_time) - new Date(b.start_time));
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long'
  });
};

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const canReserve = (course) => {
  return course.current_participants < course.max_participants && 
         new Date(course.start_time) > new Date();
};

const confirmReserve = (course) => {
  selectedCourse.value = course;
  showReserveModal.value = true;
};

const reserveConfirmed = () => {
  if (!selectedCourse.value) return;
  
  router.post(route('reservations.store'), {
    course_id: selectedCourse.value.id
  }, {
    onSuccess: () => {
      showReserveModal.value = false;
      router.visit(route('client.dashboard'));
    },
    onError: (errors) => {
      alert(errors.message || "Une erreur est survenue");
    }
  });
};

const openCancelModal = (reservationId) => {
  reservationToCancel.value = reservationId;
  showCancelModal.value = true;
};

const confirmCancel = () => {
  if (!reservationToCancel.value) return;
  
  router.delete(route('reservations.cancel', { id: reservationToCancel.value }), {
    onSuccess: () => {
      showCancelModal.value = false;
      router.reload();
    },
    onError: () => {
      alert("Erreur lors de l'annulation");
      showCancelModal.value = false;
    }
  });
};
</script>