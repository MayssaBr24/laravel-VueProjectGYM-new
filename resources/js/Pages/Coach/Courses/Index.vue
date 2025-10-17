<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <!-- Sidebar (same as Dashboard) -->
    <div class="fixed left-0 top-0 h-full w-64 bg-gray-900/95 backdrop-blur-xl border-r border-gray-700/50 z-50">
      <!-- Same sidebar content as Dashboard -->
      <div class="p-6">
        <div class="flex items-center gap-3 mb-8">
          <div class="w-10 h-10 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h1 class="text-xl font-bold text-white">CoachHub</h1>
        </div>

        <nav class="space-y-2">
          <Link :href="route('coach.dashboard')" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800/50 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
            </svg>
            <span>Dashboard</span>
          </Link>
          <a href="#" class="flex items-center gap-3 px-4 py-3 text-white bg-emerald-600/20 border border-emerald-500/30 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span class="font-medium">Mes Cours</span>
          </a>
        </nav>
      </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
      <!-- Header -->
      <div class="mb-8 flex justify-between items-start">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">
            Mes <span class="bg-gradient-to-r from-emerald-400 to-emerald-600 bg-clip-text text-transparent">Cours</span>
          </h1>
          <p class="text-gray-400">Gérez vos cours et participants avec style</p>
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

      <!-- Courses Grid -->
      <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-8 border border-gray-700/50">
        <!-- Filters and Search -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
          <div class="flex gap-3">
            <button 
              @click="filterStatus = 'all'"
              :class="filterStatus === 'all' ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-gray-700/50 text-gray-300 border-gray-600/50'"
              class="px-4 py-2 rounded-xl font-medium transition-all border hover:border-emerald-500/50"
            >
              Tous ({{ courses.length }})
            </button>
            <button 
              @click="filterStatus = 'upcoming'"
              :class="filterStatus === 'upcoming' ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-gray-700/50 text-gray-300 border-gray-600/50'"
              class="px-4 py-2 rounded-xl font-medium transition-all border hover:border-emerald-500/50"
            >
              À venir
            </button>
            <button 
              @click="filterStatus = 'active'"
              :class="filterStatus === 'active' ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/30' : 'bg-gray-700/50 text-gray-300 border-gray-600/50'"
              class="px-4 py-2 rounded-xl font-medium transition-all border hover:border-emerald-500/50"
            >
              En cours
            </button>
          </div>
          
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher un cours..."
              class="w-64 px-4 py-2 pl-10 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
            />
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>

        <!-- Courses Grid -->
        <div v-if="filteredCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <ModernCourseCard 
            v-for="(course, index) in filteredCourses"
            :key="course.id"
            :course="course"
            :index="index"
            @view="viewCourse(course.id)"
            @delete="confirmDelete(course.id)"
            class="animate-fade-in"
            :style="{ animationDelay: `${index * 0.1}s` }"
          />
        </div>
        
        <div v-else class="text-center py-20">
          <div class="w-32 h-32 bg-gradient-to-br from-gray-700/50 to-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-600/50">
            <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-white mb-4">Aucun cours créé</h3>
          <p class="text-gray-400 text-lg mb-8">Commencez par créer votre premier cours pour partager votre expertise</p>
          <Link 
            :href="route('coach.courses.create')" 
            class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-medium transition-all duration-300 hover:transform hover:scale-105 hover:shadow-lg hover:shadow-emerald-500/25"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Créer votre premier cours</span>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ModernCourseCard from './Partials/ModernCourseCard.vue'
import { router} from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  courses: {
    type: Array,
    default: () => []
  }
})

const courses = props.courses

const filterStatus = ref('all')
const searchQuery = ref('')

const filteredCourses = computed(() => {
  let filtered = courses || []
  
  // Filter by search query
  if (searchQuery.value) {
    filtered = filtered.filter(course => 
      course.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      course.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  // Filter by status
  if (filterStatus.value !== 'all') {
    const now = new Date()
    filtered = filtered.filter(course => {
      const startTime = new Date(course.start_time)
      const endTime = new Date(course.end_time)
      
      if (filterStatus.value === 'upcoming') {
        return now < startTime
      } else if (filterStatus.value === 'active') {
        return now >= startTime && now <= endTime
      }
      return true
    })
  }
  
  return filtered
})

const viewCourse = (id) => {
  router.visit(route('coach.courses.show', id));
};

const confirmDelete = (id) => {
  if (confirm('Confirmer la suppression ?')) {
    router.delete(`/coach/courses/${id}`)
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
