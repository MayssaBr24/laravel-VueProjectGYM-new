<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <!-- Sidebar (same structure) -->
    <div class="fixed left-0 top-0 h-full w-64 bg-gray-900/95 backdrop-blur-xl border-r border-gray-700/50 z-50">
      <div class="p-6">
        <div class="flex items-center gap-3 mb-8">
          <div class="w-10 h-10 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h1 class="text-xl font-bold text-white">CoachHub</h1>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
      <div class="max-w-6xl mx-auto">
        <!-- Header with back button -->
        <div class="mb-8 flex justify-between items-center">
          <Link 
            :href="route('coach.courses.index')"
            class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-800/50 rounded-xl transition-all border border-gray-700/50 hover:border-gray-600/50"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Retour à la liste</span>
          </Link>
          
          <button 
            @click="confirmDelete"
            class="flex items-center gap-2 px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 hover:text-red-300 rounded-xl font-medium transition-all border border-red-500/30 hover:border-red-500/50"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Supprimer</span>
          </button>
        </div>

        <!-- Course Details -->
        <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-8 border border-gray-700/50 mb-8">
          <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Info -->
            <div class="flex-1">
              <div class="flex items-start gap-4 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <div>
                  <h1 class="text-3xl font-bold text-white mb-2">{{ course.name }}</h1>
                  <p class="text-gray-300 text-lg leading-relaxed">{{ course.description }}</p>
                </div>
              </div>
              
              <!-- Course Details Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-gray-800/30 rounded-xl p-4 border border-gray-700/30">
                  <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <p class="text-sm text-gray-400">Date</p>
                  </div>
                  <p class="text-white font-semibold">{{ formatDate(course.start_time) }}</p>
                </div>
                
                <div class="bg-gray-800/30 rounded-xl p-4 border border-gray-700/30">
                  <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <p class="text-sm text-gray-400">Heure</p>
                  </div>
                  <p class="text-white font-semibold">{{ formatTime(course.start_time) }} - {{ formatTime(course.end_time) }}</p>
                </div>
                
                <div class="bg-gray-800/30 rounded-xl p-4 border border-gray-700/30">
                  <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                      </svg>
                    </div>
                    <p class="text-sm text-gray-400">Participants</p>
                  </div>
                  <p class="text-white font-semibold">{{ course.current_participants }} / {{ course.max_participants }}</p>
                </div>
                
                <div class="bg-gray-800/30 rounded-xl p-4 border border-gray-700/30">
                  <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                      </svg>
                    </div>
                    <p class="text-sm text-gray-400">Places restantes</p>
                  </div>
                  <p class="text-white font-semibold" :class="{'text-red-400': course.remaining_slots < 3}">
                    {{ course.remaining_slots }}
                  </p>
                </div>
              </div>
            </div>
            
            <!-- Status Card -->
            <div class="lg:w-80">
              <div class="bg-gradient-to-br from-gray-800/60 to-gray-900/60 rounded-2xl p-6 border border-gray-700/50">
                <h3 class="text-lg font-semibold text-white mb-4">Statut du cours</h3>
                <div class="flex items-center gap-4 mb-4">
                  <div class="w-4 h-4 rounded-full" :class="statusColor"></div>
                  <span class="text-white font-medium">{{ statusText }}</span>
                </div>
                
                <!-- Progress Ring -->
                <div class="relative w-24 h-24 mx-auto mb-4">
                  <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="8" fill="none" class="text-gray-700" />
                    <circle 
                      cx="50" cy="50" r="40" 
                      stroke="currentColor" 
                      stroke-width="8" 
                      fill="none" 
                      class="text-emerald-500"
                      :stroke-dasharray="`${(course.current_participants / course.max_participants) * 251.2} 251.2`"
                      stroke-linecap="round"
                    />
                  </svg>
                  <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">{{ Math.round((course.current_participants / course.max_participants) * 100) }}%</span>
                  </div>
                </div>
                
                <p class="text-center text-gray-400 text-sm">Taux de remplissage</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Participants List -->
        <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-8 border border-gray-700/50">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">
              Participants inscrits 
              <span class="text-emerald-400">({{ course.participants.length }})</span>
            </h2>
            <div class="flex items-center gap-2 text-gray-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
              <span>{{ course.participants.length }} participants</span>
            </div>
          </div>
          
          <div v-if="course.participants.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <ModernParticipantCard 
              v-for="(participant, index) in course.participants"
              :key="participant.id"
              :participant="participant"
              :index="index"
            />
          </div>
          
          <div v-else class="text-center py-16">
            <div class="w-20 h-20 bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
            </div>
            <p class="text-gray-400 text-lg">Aucun participant inscrit pour le moment</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import ModernParticipantCard from '@/Components/Coach/ModernParticipantCard.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  course: {
    type: Object,
    required: true
  }
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const confirmDelete = () => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) {
    router.delete(`/coach/courses/${props.course.id}`)
  }
}

const now = new Date()
const startTime = new Date(props.course.start_time)
const endTime = new Date(props.course.end_time)

const statusText = computed(() => {
  if (now < startTime) return 'À venir'
  if (now >= startTime && now <= endTime) return 'En cours'
  return 'Terminé'
})

const statusColor = computed(() => {
  if (now < startTime) return 'bg-blue-500'
  if (now >= startTime && now <= endTime) return 'bg-emerald-500'
  return 'bg-gray-500'
})
</script>
