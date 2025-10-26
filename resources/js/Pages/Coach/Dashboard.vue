<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <!-- Header Component -->
    <DashboardHeader />

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Welcome Section -->
     

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="group relative bg-gradient-to-br from-gray-800/60 to-gray-900/60 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 hover:border-emerald-500/50 transition-all duration-300 hover:transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z" />
                </svg>
              </div>
              <span class="text-emerald-400 text-sm font-medium bg-emerald-500/20 px-2 py-1 rounded-full">+12%</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ stats.active_courses }}</h3>
            <p class="text-gray-400 text-sm">Cours actifs ce mois</p>
          </div>
        </div>

        <div class="group relative bg-gradient-to-br from-gray-800/60 to-gray-900/60 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 hover:border-blue-500/50 transition-all duration-300 hover:transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
              </div>
              <span class="text-blue-400 text-sm font-medium bg-blue-500/20 px-2 py-1 rounded-full">+8%</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ stats.total_participants }}</h3>
            <p class="text-gray-400 text-sm">Total participants</p>
          </div>
        </div>

        <div class="group relative bg-gradient-to-br from-gray-800/60 to-gray-900/60 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 hover:border-emerald-500/50 transition-all duration-300 hover:transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <span class="text-emerald-400 text-sm font-medium bg-emerald-500/20 px-2 py-1 rounded-full">{{ stats.fill_rate }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ stats.fill_rate }}%</h3>
            <p class="text-gray-400 text-sm">Taux de remplissage</p>
          </div>
        </div>

       
      </div>
     
       <CalendrierCard />
     
      




      <!-- Quick Actions -->
      <div class="mt-8 bg-gradient-to-r from-gray-800/60 to-gray-900/60 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-2xl font-bold text-white mb-2">Actions rapides</h2>
            <p class="text-gray-400">Gérez vos cours efficacement</p>
          </div>
          <div class="flex flex-wrap gap-3">
            <Link 
              :href="route('coach.courses.create')"
              class="group relative px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-medium transition-all duration-300 flex items-center gap-2 hover:transform hover:scale-105 hover:shadow-lg hover:shadow-emerald-500/25"
            >
              <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              <span>Nouveau cours</span>
            </Link>
            <Link 
              :href="route('coach.courses.mescours')"
              class="px-6 py-3 bg-gray-700/60 hover:bg-gray-600/60 text-gray-300 hover:text-white rounded-xl font-medium transition-all duration-300 flex items-center gap-2 border border-gray-600/50 hover:border-gray-500/50"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              <span>Voir tous</span>
            </Link>
          </div>
        </div>
      </div>

      <!-- Recent Courses Section -->
      <div class="bg-gradient-to-r from-gray-800/60 to-gray-900/60 backdrop-blur-xl rounded-2xl p-8 border border-gray-700/50">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h2 class="text-2xl font-bold text-white mb-2">Cours récents</h2>
            <p class="text-gray-400">Gérez vos derniers cours créés</p>
          </div>
         
        </div>
        
        <div v-if="recentCourses.length > 0" class="relative">
          <div class="carousel-container" ref="carouselContainer">
            <div class="carousel-track" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
              <div 
                v-for="(course, index) in recentCourses" 
                :key="course.id"
                class="carousel-slide"
              >
                <ModernCourseCard 
                  :course="course"
                  :index="index"
                  @view="viewCourse(course.id)"
                  @delete="openDeleteModal(course)"
                />
              </div>
            </div>
          </div>
          
          <!-- Carousel Controls -->
          <div class="flex justify-center gap-4 mt-8">
            <button 
              @click="prevSlide"
              class="w-12 h-12 bg-gray-700/60 hover:bg-gray-600/60 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all border border-gray-600/50 hover:border-emerald-500/50 group"
            >
              <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <div class="flex gap-2 items-center">
              <span 
                v-for="(_, index) in recentCourses" 
                :key="index"
                class="w-2 h-2 rounded-full transition-all cursor-pointer hover:scale-125"
                :class="index === currentSlide ? 'bg-emerald-500 w-8' : 'bg-gray-600 hover:bg-gray-500'"
                @click="currentSlide = index"
              ></span>
            </div>
            <button 
              @click="nextSlide"
              class="w-12 h-12 bg-gray-700/60 hover:bg-gray-600/60 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all border border-gray-600/50 hover:border-emerald-500/50 group"
            >
              <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
    
        
        <div v-else class="text-center py-16">
          <div class="w-24 h-24 bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-600/50">
            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">Aucun cours récent</h3>
          <p class="text-gray-400 mb-6">Commencez par créer votre premier cours</p>
          <Link 
            :href="route('coach.courses.create')" 
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-medium transition-all duration-300 hover:transform hover:scale-105 shadow-lg hover:shadow-emerald-500/25"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Créer votre premier cours
          </Link>
        </div>
      </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <DeleteConfirmationModal
      :show="showDeleteModal"
      :course="courseToDelete"
      @close="closeDeleteModal"
      @confirm="confirmDelete"
    />
    

  </div>
 
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import DashboardHeader from '@/Components/Layout/DashboardHeader.vue'
import ModernCourseCard from './Courses/Partials/ModernCourseCard.vue'
import DeleteConfirmationModal from '@/Components/Modals/DeleteConfirmationModal.vue'
import CalendrierCard from '@/Components/Coach/CalendrierCard.vue'

import { router } from '@inertiajs/vue3'
onMounted(async () => {
  const response = await axios.get('/calendar/courses')
})

const props = defineProps({
  stats: {
    type: Object,
    required: true,
    default: () => ({
      active_courses: 0,
      total_participants: 0,
      fill_rate: 0
    })
  },
  recentCourses: {
    type: Array,
    default: () => []
  }
})
const currentSlide = ref(0)
const carouselContainer = ref(null)
const showDeleteModal = ref(false)
const courseToDelete = ref(null)

const viewCourse = (id) => {
  router.visit(`/coach/courses/${id}/details`);
};

const openDeleteModal = (course) => {
  courseToDelete.value = course
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  courseToDelete.value = null
}

const confirmDelete = async () => {
  if (courseToDelete.value) {
    try {
      await router.delete(`/coach/courses/${courseToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          closeDeleteModal()
        },
        onError: () => {
          // Handle error
        }
      })
    } catch (error) {
      console.error('Erreur lors de la suppression:', error)
    }
  }
}

const nextSlide = () => {
  if (currentSlide.value < props.recentCourses.length - 1) {
    currentSlide.value++
  } else {
    currentSlide.value = 0
  }
}

const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--
  } else {
    currentSlide.value = props.recentCourses.length - 1
  }
}


onMounted(() => {
  // Auto-play carousel
  setInterval(() => {
    if (props.recentCourses.length > 1) {
      nextSlide()
    }
  }, 5000)
})
</script>

<style scoped>
.carousel-container {
  overflow: hidden;
  border-radius: 1rem;
}

.carousel-track {
  display: flex;
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.carousel-slide {
  min-width: 100%;
  padding: 0 1rem;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.group:hover .animate-float {
  animation: float 2s ease-in-out infinite;
}
</style>
