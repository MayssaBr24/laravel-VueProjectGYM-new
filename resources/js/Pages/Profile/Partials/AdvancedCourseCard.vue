<template>
  <div class="group relative bg-gradient-to-br from-gray-800/60 to-gray-900/60 backdrop-blur-xl rounded-2xl overflow-hidden border border-gray-700/50 hover:border-emerald-500/50 transition-all duration-500 hover:transform hover:scale-105">
    <!-- Status Badge -->
    <div class="absolute top-4 right-4 z-10">
      <span 
        class="px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm"
        :class="statusClasses"
      >
        {{ statusText }}
      </span>
    </div>
    
    <!-- 3D Effect Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-transparent to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    
    <!-- Course Header -->
    <div class="relative h-48 bg-gradient-to-br from-emerald-600 via-emerald-500 to-blue-600 overflow-hidden">
      <div class="absolute inset-0 bg-black/20"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
      
      <!-- Floating Elements -->
      <div class="absolute top-4 left-4 w-8 h-8 bg-white/20 rounded-full animate-pulse"></div>
      <div class="absolute top-8 left-16 w-4 h-4 bg-emerald-300/30 rounded-full animate-bounce" style="animation-delay: 0.5s"></div>
      <div class="absolute bottom-6 right-8 w-6 h-6 bg-blue-300/30 rounded-full animate-pulse" style="animation-delay: 1s"></div>
      
      <!-- Course Info Overlay -->
      <div class="absolute bottom-0 left-0 right-0 p-6">
        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-emerald-300 transition-colors line-clamp-2">
          {{ course.name }}
        </h3>
        <div class="flex items-center gap-4 text-white/80 text-sm">
          <div class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <span>{{ course.current_participants }}/{{ course.max_participants }}</span>
          </div>
          <div class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z" />
            </svg>
            <span>{{ formatDate(course.start_time) }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Card Body -->
    <div class="relative p-6">
      <p class="text-gray-300 text-sm line-clamp-3 mb-4 group-hover:text-gray-200 transition-colors">
        {{ course.description }}
      </p>
      
      <!-- Course Details -->
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-gray-800/30 rounded-lg p-3">
          <div class="flex items-center gap-2 mb-1">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs text-gray-400">Horaire</span>
          </div>
          <p class="text-white text-sm font-medium">
            {{ formatTime(course.start_time) }}
          </p>
        </div>
        
        <div class="bg-gray-800/30 rounded-lg p-3">
          <div class="flex items-center gap-2 mb-1">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs text-gray-400">Places</span>
          </div>
          <p class="text-white text-sm font-medium">
            {{ course.remaining_slots }} restantes
          </p>
        </div>
      </div>
      
      <!-- Progress Bar -->
      <div class="mb-4">
        <div class="flex justify-between text-xs text-gray-400 mb-2">
          <span>Remplissage</span>
          <span class="text-emerald-400 font-medium">{{ calculateFillRate(course) }}%</span>
        </div>
        <div class="w-full bg-gray-700/50 rounded-full h-2 overflow-hidden">
          <div 
            class="h-2 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all duration-1000 ease-out"
            :style="`width: ${calculateFillRate(course)}%`"
          ></div>
        </div>
      </div>
      
      <!-- Alert for Low Slots -->
      <div 
        v-if="course.remaining_slots < 3 && course.remaining_slots > 0"
        class="bg-gradient-to-r from-red-500/20 to-orange-500/20 border border-red-500/30 rounded-xl p-3 mb-4 backdrop-blur-sm"
      >
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
          <p class="text-sm text-red-300 font-medium">
            Plus que {{ course.remaining_slots }} place(s) !
          </p>
        </div>
      </div>
      
      <!-- Actions -->
      <div class="flex justify-between gap-3 pt-4 border-t border-gray-700/50">
        <button 
          @click="$emit('view')"
          class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 rounded-xl font-medium transition-all duration-300 border border-emerald-500/30 hover:border-emerald-500/50"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          <span>Voir détails</span>
        </button>
        <button 
          @click="$emit('delete', course.id)"
          class="flex items-center justify-center gap-2 px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 hover:text-red-300 rounded-xl font-medium transition-all duration-300 border border-red-500/30 hover:border-red-500/50"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>
    
    <!-- 3D Glow Effect -->
    <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
      <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-emerald-500/20 via-transparent to-blue-500/20 blur-xl"></div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  course: {
    type: Object,
    required: true
  },
  index: {
    type: Number,
    default: 0
  }
})

defineEmits(['view', 'delete'])

const statusText = computed(() => {
  switch (props.course.status) {
    case 'upcoming':
      return 'À venir'
    case 'active':
      return 'En cours'
    case 'completed':
      return 'Terminé'
    default:
      return 'Inconnu'
  }
})

const statusClasses = computed(() => {
  switch (props.course.status) {
    case 'upcoming':
      return 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
    case 'active':
      return 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30'
    case 'completed':
      return 'bg-gray-500/20 text-gray-300 border border-gray-500/30'
    default:
      return 'bg-gray-500/20 text-gray-300 border border-gray-500/30'
  }
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const calculateFillRate = (course) => {
  return Math.round((course.current_participants / course.max_participants) * 100)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
