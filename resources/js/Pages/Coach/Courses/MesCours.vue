<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    
    

    <!-- Main Content -->
    <div class=" p-8">
      <!-- Header -->
      <div class="mb-8 flex justify-between items-start">
      <Link 
            :href="route('coach.courses.index')"
            class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-800/50 rounded-xl transition-all border border-gray-700/50 hover:border-gray-600/50"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Retour </span>
      </Link>
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">
            Tous mes <span class="bg-gradient-to-r from-emerald-400 to-emerald-600 bg-clip-text text-transparent">Cours</span>
          </h1>
          <p class="text-gray-400">Gérez l'ensemble de vos cours avec des outils avancés</p>
        </div>
        
        <Link 
          :href="route('coach.courses.create')"
          class="group relative px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-medium transition-all duration-300 flex items-center gap-2 hover:transform hover:scale-105 hover:shadow-lg hover:shadow-emerald-500/25"
        >
          <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span>Nouveau cours</span>
        </Link>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm">Total</p>
              <p class="text-2xl font-bold text-white">{{ stats.total }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm">À venir</p>
              <p class="text-2xl font-bold text-white">{{ stats.upcoming }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm">En cours</p>
              <p class="text-2xl font-bold text-white">{{ stats.active }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm">Terminés</p>
              <p class="text-2xl font-bold text-white">{{ stats.completed }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 mb-8">
        <div class="flex flex-col lg:flex-row gap-6 justify-between items-start lg:items-center">
          <!-- Status Filters -->
          <div class="flex flex-wrap gap-3">
            <button 
              @click="updateFilter('status', 'all')"
              :class="currentFilters.status === 'all' || !currentFilters.status ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-gray-700/50 text-gray-300 border-gray-600/50'"
              class="px-4 py-2 rounded-xl font-medium transition-all border hover:border-emerald-500/50"
            >
              Tous ({{ stats.total }})
            </button>
            <button 
              @click="updateFilter('status', 'upcoming')"
              :class="currentFilters.status === 'upcoming' ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-gray-700/50 text-gray-300 border-gray-600/50'"
              class="px-4 py-2 rounded-xl font-medium transition-all border hover:border-emerald-500/50"
            >
              À venir ({{ stats.upcoming }})
            </button>
            <button 
              @click="updateFilter('status', 'active')"
              :class="currentFilters.status === 'active' ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-gray-700/50 text-gray-300 border-gray-600/50'"
              class="px-4 py-2 rounded-xl font-medium transition-all border hover:border-emerald-500/50"
            >
              En cours ({{ stats.active }})
            </button>
            <button 
              @click="updateFilter('status', 'completed')"
              :class="currentFilters.status === 'completed' ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-gray-700/50 text-gray-300 border-gray-600/50'"
              class="px-4 py-2 rounded-xl font-medium transition-all border hover:border-emerald-500/50"
            >
              Terminés ({{ stats.completed }})
            </button>
          </div>
          
          <!-- Search and Sort -->
          <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <div class="relative">
              <input
                v-model="searchQuery"
                @input="debounceSearch"
                type="text"
                placeholder="Rechercher un cours..."
                class="w-full sm:w-64 px-4 py-2 pl-10 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
              />
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            
            <select 
              v-model="sortBy"
              @change="updateFilter('sort', sortBy)"
              class="px-4 py-2 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
            >
              <option value="latest">Plus récents</option>
              <option value="name">Nom A-Z</option>
              <option value="date">Date de début</option>
              <option value="participants">Plus de participants</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Courses Grid -->
      <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-8 border border-gray-700/50">
        <div v-if="courses.data.length > 0">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            <AdvancedCourseCard 
              v-for="(course, index) in courses.data"
              :key="course.id"
              :course="course"
              :index="index"
              @view="viewCourse(course.id)"
              @delete="openDeleteModal(course.id)"
              class="animate-fade-in"
              :style="{ animationDelay: `${index * 0.05}s` }"
            />
            <DeleteConfirmationModal
             :show="showDeleteModal"
             title="Supprimer ce cours"
             message="Êtes-vous sûr(e) de vouloir supprimer ce cours ? Cette action est irréversible."
             @close="showDeleteModal = false"
             @confirm="confirmDelete"
            />

          </div>
          
          <!-- Pagination -->
          <div v-if="courses.last_page > 1" class="flex justify-center">
            <nav class="flex items-center gap-2">
              <Link 
                v-if="courses.prev_page_url"
                :href="courses.prev_page_url"
                class="px-4 py-2 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-white rounded-xl transition-all border border-gray-600/50"
              >
                Précédent
              </Link>
              
              <span class="px-4 py-2 text-gray-400">
                Page {{ courses.current_page }} sur {{ courses.last_page }}
              </span>
              
              <Link 
                v-if="courses.next_page_url"
                :href="courses.next_page_url"
                class="px-4 py-2 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-white rounded-xl transition-all border border-gray-600/50"
              >
                Suivant
              </Link>
            </nav>
          </div>
        </div>
        
        <div v-else class="text-center py-20">
          <div class="w-32 h-32 bg-gradient-to-br from-gray-700/50 to-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-600/50">
            <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-white mb-4">Aucun cours trouvé</h3>
          <p class="text-gray-400 text-lg mb-8">
            {{ currentFilters.search ? 'Aucun cours ne correspond à votre recherche' : 'Commencez par créer votre premier cours' }}
          </p>
          <Link 
            :href="route('coach.courses.mescours')" 
            class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-medium transition-all duration-300 hover:transform hover:scale-105 hover:shadow-lg hover:shadow-emerald-500/25"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Créer un nouveau cours</span>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdvancedCourseCard from './Partials/AdvancedCourseCard.vue'
import { ref, computed, watch } from 'vue'
import { debounce } from 'lodash'
import DeleteConfirmationModal from '@/Components/Modals/DeleteConfirmationModal.vue'

const props = defineProps({
  courses: {
    type: Object,
    required: true
  },
  stats: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

const searchQuery = ref(props.filters.search || '')
const sortBy = ref(props.filters.sort || 'latest')
const currentFilters = ref(props.filters)

const viewCourse = (id) => {
  router.visit(route('coach.courses.show', id))
}



const updateFilter = (key, value) => {
  const newFilters = { ...currentFilters.value, [key]: value }
  
  // Nettoyer les filtres vides
  Object.keys(newFilters).forEach(k => {
    if (!newFilters[k] || newFilters[k] === 'all') {
      delete newFilters[k]
    }
  })
  
  router.get(route('coach.courses.all'), newFilters, {
    preserveState: true,
    preserveScroll: true
  })
}

const debounceSearch = debounce(() => {
  updateFilter('search', searchQuery.value)
}, 500)

// Mettre à jour les filtres actuels quand les props changent
watch(() => props.filters, (newFilters) => {
  currentFilters.value = newFilters
  searchQuery.value = newFilters.search || ''
  sortBy.value = newFilters.sort || 'latest'
})
const showDeleteModal = ref(false)
const courseToDeleteId = ref(null)

const openDeleteModal = (id) => {
  courseToDeleteId.value = id
  showDeleteModal.value = true
}

const confirmDelete = () => {
  if (courseToDeleteId.value) {
    router.delete(`/coach/courses/${courseToDeleteId.value}`, {
      preserveScroll: true
    })
    showDeleteModal.value = false
  }
}

</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out forwards;
  opacity: 0;
}
</style>
