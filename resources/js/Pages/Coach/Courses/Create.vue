<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <header class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border-b border-gray-700/50">
      <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-blue-500/10"></div>
      <div class="relative flex flex-col space-y-6 md:flex-row md:justify-between md:items-center px-6 py-8">
        <div class="space-y-3">
          <div class="flex items-center space-x-4">
            <Link 
              href="/coach/courses"
              class="p-3 bg-gray-800/50 hover:bg-gray-700/50 rounded-xl transition-all duration-300 backdrop-blur-sm"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </Link>
            <div class="p-4 bg-emerald-500/20 rounded-2xl backdrop-blur-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </div>
          </div>
          <h1 class="text-5xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
            Créer un Nouveau Cours
          </h1>
          <p class="text-emerald-400 text-xl font-medium flex items-center space-x-2">
            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
            <span>Ajoutez un cours à votre planning et inspirez vos membres</span>
          </p>
        </div>
      </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Formulaire -->
      <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm">
        <form @submit.prevent="submitForm" class="p-8 space-y-8">
          <!-- Section Informations de base -->
          <div class="space-y-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-emerald-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h2 class="text-xl font-bold text-white">Informations de base</h2>
            </div>

            <!-- Nom du cours -->
            <!-- Cour Select - Improved Design -->
              
                <label class="block text-sm font-semibold text-gray-300/90">
                  Nom du cour <span class="text-red-400">*</span>
                </label>
                
                <div class="relative group">
                  <select
                    v-model="form.course_type_id"
                    class="w-full pl-12 pr-10 py-3.5 bg-gray-800/70 border-2 border-gray-700 rounded-xl text-white/90 placeholder-gray-400/80 
                          focus:ring-2 focus:ring-emerald-400/90 focus:border-emerald-500 focus:bg-gray-800/80
                          transition-all duration-200 ease-out backdrop-blur-sm appearance-none
                          group-hover:border-gray-600 group-hover:bg-gray-800/80
                          shadow-lg shadow-gray-900/10"
                  >
                    <option value="" disabled selected class="text-gray-400 bg-gray-800">Sélectionnez un cour</option>
                    <option 
                          v-for="type in types" 
                          :key="type.id" 
                          :value="type.id"
    
                      class="bg-gray-800 text-white hover:bg-emerald-500 hover:text-white"
                    >
                      {{ type.name }}
                    </option>
                  </select>
                  
                  <!-- User Icon Only - Removed duplicate arrow -->
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none
                              transition-transform duration-200 group-focus-within:translate-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400/90 group-hover:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  
                  <!-- Single Dropdown Arrow (positioned right) -->
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400/90 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </div>
                </div>
              

            <!-- Description -->
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-300">
                Description <span class="text-red-400">*</span>
              </label>
              <textarea 
                v-model="form.description" 
                rows="4" 
                required
                class="w-full px-6 py-4 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 backdrop-blur-sm resize-none"
                placeholder="Décrivez votre cours : objectifs, niveau requis, équipements nécessaires..."
              ></textarea>
              <div v-if="form.errors.description" class="flex items-center space-x-2 text-red-400 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ form.errors.description }}</span>
              </div>
            </div>
          </div>

          <div class="space-y-6">
            

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Sélection du coach -->
             
              <!-- Participants max -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-300">
                  Nombre maximal de participants <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.max_participants" 
                    type="number" 
                    min="1"
                    max="50"
                    required
                    class="w-full pl-12 pr-6 py-4 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 backdrop-blur-sm"
                    placeholder="Ex: 15"
                  />
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                </div>
                <div v-if="form.errors.max_participants" class="flex items-center space-x-2 text-red-400 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>{{ form.errors.max_participants }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Section Horaires -->
          <div class="space-y-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-purple-500/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h2 class="text-xl font-bold text-white">Planification</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Heure de début -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-300">
                  Heure de début <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.start_time" 
                    type="datetime-local" 
                    required
                    class="w-full pl-12 pr-6 py-4 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 backdrop-blur-sm"
                  />
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </div>
                <div v-if="form.errors.start_time" class="flex items-center space-x-2 text-red-400 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>{{ form.errors.start_time }}</span>
                </div>
              </div>

              <!-- Heure de fin -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-300">
                  Heure de fin <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.end_time" 
                    type="datetime-local" 
                    required
                    class="w-full pl-12 pr-6 py-4 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 backdrop-blur-sm"
                  />
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <div v-if="form.errors.end_time" class="flex items-center space-x-2 text-red-400 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>{{ form.errors.end_time }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Boutons d'action -->
          <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-700/50">
            <button 
              type="submit" 
              :disabled="form.processing"
              class="group relative flex-1 px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/25 flex items-center justify-center space-x-3 overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              <svg v-else class="animate-spin h-5 w-5 relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="relative z-10">{{ form.processing ? 'Création en cours...' : 'Créer le Cours' }}</span>
            </button>
            
            <Link 
              :href="route('courses.index')" 
              class="flex-1 px-8 py-4 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 text-center flex items-center justify-center space-x-3 backdrop-blur-sm"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <span>Annuler</span>
            </Link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'  // Added watch import here
import axios from 'axios'

// Define props
const props = defineProps({
  types: {
    type: Array,
    required: true
  }
})

const coaches = ref([])

const form = useForm({
  name: '',
  description: '',
  course_type_id: '',
  max_participants: 10,
  start_time: '',
  end_time: '',
})

// Watcher pour synchroniser le nom avec le type de cours
watch(() => form.course_type_id, (newTypeId) => {
  const selectedType = props.types.find(type => type.id == newTypeId);
  form.name = selectedType ? selectedType.name : '';
}, { immediate: true });

const submitForm = () => {
  form.post(route('coach.courses.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
    onError: (errors) => {
      console.log('Erreurs de validation:', errors)
    }
  })
}
</script>



<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Scrollbar personnalisée pour le thème sombre */
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

/* Effet de focus moderne */
input:focus, textarea:focus, select:focus {
  transform: translateY(-1px);
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1), 0 4px 12px -2px rgba(16, 185, 129, 0.25);
}

/* Animation de pulsation */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Effet de backdrop blur */
.backdrop-blur-sm {
  backdrop-filter: blur(4px);
}

/* Gradient text */
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}

/* Animation de rotation */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>