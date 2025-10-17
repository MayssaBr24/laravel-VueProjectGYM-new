<template>
  <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm">
    <div class="px-6 py-4 border-b border-gray-700/50 bg-gray-800/50 flex justify-between items-center">
      <h3 class="text-lg font-bold text-white">Mon Prochain Cours</h3>
      <Link :href="route('client.courses')" class="text-emerald-400 hover:underline ml-2">
        Réserver maintenant
      </Link>      
    </div>
    
    

    <div v-if="course" class="p-6 flex flex-col md:flex-row md:items-center gap-6">
      <div class="flex-1">
        <h4 class="text-xl font-bold text-white">{{ course.name }}</h4>

        <div class="flex items-center text-gray-400 mt-4 space-x-4">
          <div class="flex items-center">
            <ClockIcon class="h-5 w-5 text-emerald-400 mr-2" />
            <span>{{ formatTime(course.start_time) }} - {{ formatTime(course.end_time) }}</span>
          </div>
          <div class="flex items-center">
            <MapPinIcon class="h-5 w-5 text-emerald-400 mr-2" />
            <span>Salle {{ course.location || 'Non spécifiée' }}</span>
          </div>
        </div>

        <p class="text-gray-400 mt-4">{{ course.description || 'Aucune description disponible' }}</p>

        <div class="mt-4">
          <div class="flex justify-between text-sm mb-1">
            <span class="text-gray-400">Places occupées</span>
            <span class="font-medium text-emerald-400">
              {{ course.current_participants }}/{{ course.max_participants }}
            </span>
          </div>
          <div class="w-full bg-gray-700 rounded-full h-2">
            <div
              class="bg-emerald-500 h-2 rounded-full"
              :style="`width: ${(course.current_participants / course.max_participants) * 100}%`"
            ></div>
          </div>
        </div>

    <div class="mt-6" v-if="course && new Date(course.start_time) > new Date()">
    <Link
        :href="route('client.qrcode', { course_id: course.id })"
        class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm"
    >
        Afficher QR Code
    </Link>
</div>

      </div>
    </div>
    

    <div v-else class="p-6 text-center text-gray-400">
      Vous n'avez pas de cours programmé.
    </div>
  </div>
  
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ClockIcon, MapPinIcon } from '@heroicons/vue/24/outline'

defineProps({
  course: Object
})

const formatTime = (datetime) => {
  const d = new Date(datetime)
  return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}
</script>
