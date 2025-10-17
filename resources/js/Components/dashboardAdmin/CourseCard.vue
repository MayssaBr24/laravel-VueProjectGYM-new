<template>
  <div class="group relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700/50 p-6 rounded-2xl hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 transform hover:scale-105">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    
    <!-- Header -->
    <div class="relative flex justify-between items-start mb-4">
      <div class="flex-1">
        <h3 class="font-bold text-xl text-white mb-2 group-hover:text-emerald-400 transition-colors duration-300">
          {{ course.name }}
        </h3>
        <p class="text-sm text-emerald-400 font-medium flex items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span>{{ course.coach_name || 'Coach non spécifié' }}</span>
        </p>
      </div>
      
      <!-- Actions Menu -->
      <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <button
          @click="$emit('edit', course)"
          class="p-2 bg-gray-700/50 hover:bg-emerald-500/20 text-gray-400 hover:text-emerald-400 rounded-lg transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
          </svg>
        </button>
        <button
          @click="$emit('delete', course)"
          class="p-2 bg-gray-700/50 hover:bg-red-500/20 text-gray-400 hover:text-red-400 rounded-lg transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Description -->
    <p class="text-sm text-gray-400 line-clamp-3 mb-6">
      {{ course.description || 'Aucune description disponible' }}
    </p>

    <!-- Course Info -->
    <div class="space-y-4">
      <!-- Participants -->
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span class="text-sm">Participants</span>
        </div>
        <div class="flex items-center space-x-2">
          <span class="text-white font-semibold">{{ course.current_participants || 0 }}</span>
          <span class="text-gray-400">/</span>
          <span class="text-emerald-400 font-semibold">{{ course.max_participants || 'Illimité' }}</span>
        </div>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-gray-700 rounded-full h-2">
        <div
          class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full"
          :style="`width: ${calculateProgress(course)}%`"
        ></div>
      </div>

      <!-- Date and Time -->
      <div class="flex items-center justify-between pt-4 border-t border-gray-700/50">
        <div class="flex items-center space-x-2 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span class="text-sm">{{ formatDate(course.start_time) }}</span>
        </div>
        <div class="flex items-center space-x-2 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-sm">{{ formatTime(course.start_time) }} - {{ formatTime(course.end_time) }}</span>
        </div>
      </div>

      <!-- Status -->
      <div class="flex justify-center pt-2">
        <span :class="getStatusBadgeClass(course)" class="px-3 py-1 text-xs rounded-full font-semibold">
          {{ getStatusText(course) }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  course: {
    type: Object,
    required: true
  }
})

defineEmits(['edit', 'delete'])

const calculateProgress = (course) => {
  if (!course.max_participants) return 0
  return Math.round((course.current_participants / course.max_participants) * 100)
}

const formatDate = (dateString) => {
  if (!dateString) return 'Date non disponible'
  try {
    const options = { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }
    return new Date(dateString).toLocaleDateString('fr-FR', options)
  } catch (error) {
    return 'Date invalide'
  }
}

const formatTime = (dateString) => {
  if (!dateString) return ''
  try {
    return new Date(dateString).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
  } catch (error) {
    return ''
  }
}

const getStatusBadgeClass = (course) => {
  const now = new Date()
  const startTime = new Date(course.start_time)
  const endTime = new Date(course.end_time)
  
  if (now < startTime) {
    return 'bg-blue-500/20 text-blue-400'
  } else if (now >= startTime && now <= endTime) {
    return 'bg-emerald-500/20 text-emerald-400'
  } else {
    return 'bg-gray-500/20 text-gray-400'
  }
}

const getStatusText = (course) => {
  const now = new Date()
  const startTime = new Date(course.start_time)
  const endTime = new Date(course.end_time)
  
  if (now < startTime) {
    return 'À venir'
  } else if (now >= startTime && now <= endTime) {
    return 'En cours'
  } else {
    return 'Terminé'
  }
}
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
