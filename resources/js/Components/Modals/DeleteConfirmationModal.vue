<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click="closeModal"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal -->
        <Transition
          enter-active-class="transition-all duration-300"
          enter-from-class="opacity-0 scale-95 translate-y-4"
          enter-to-class="opacity-100 scale-100 translate-y-0"
          leave-active-class="transition-all duration-200"
          leave-from-class="opacity-100 scale-100 translate-y-0"
          leave-to-class="opacity-0 scale-95 translate-y-4"
        >
          <div 
            v-if="show"
            class="relative bg-gradient-to-br from-gray-800/95 to-gray-900/95 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl max-w-md w-full mx-4"
            @click.stop
          >
            <!-- Glow Effect -->
            <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-red-500/20 via-transparent to-orange-500/20 blur-xl opacity-50"></div>
            
            <!-- Content -->
            <div class="relative p-8">
              <!-- Icon -->
              <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-red-500/20 to-orange-500/20 rounded-full flex items-center justify-center border border-red-500/30">
                  <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  </svg>
                </div>
              </div>
              
              <!-- Title -->
              <h3 class="text-2xl font-bold text-white text-center mb-4">
                Supprimer le cours
              </h3>
              
              <!-- Course Info -->
              <div v-if="course" class="bg-gray-800/50 rounded-xl p-4 mb-6 border border-gray-700/50">
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-semibold text-white">{{ course.name }}</h4>
                    <p class="text-sm text-gray-400">{{ course.current_participants }} participants inscrits</p>
                  </div>
                </div>
              </div>
              
              <!-- Warning Message -->
              <div class="text-center mb-8">
                <p class="text-gray-300 text-lg mb-2">
                  Êtes-vous sûr de vouloir supprimer ce cours ?
                </p>
                <p class="text-red-400 text-sm font-medium">
                  Cette action est irréversible et supprimera toutes les données associées.
                </p>
              </div>
              
              <!-- Actions -->
              <div class="flex flex-col sm:flex-row gap-3">
                <button
                  @click="closeModal"
                  class="flex-1 px-6 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-white rounded-xl font-medium transition-all duration-300 border border-gray-600/50 hover:border-gray-500/50 flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  <span>Annuler</span>
                </button>
                
                <button
                  @click="confirmDelete"
                  :disabled="isDeleting"
                  class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 disabled:from-gray-600 disabled:to-gray-700 text-white font-semibold rounded-xl transition-all duration-300 hover:transform hover:scale-105 disabled:transform-none disabled:cursor-not-allowed flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-red-500/25"
                >
                  <svg 
                    v-if="isDeleting" 
                    class="w-5 h-5 animate-spin" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <svg 
                    v-else
                    class="w-5 h-5" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  <span>{{ isDeleting ? 'Suppression...' : 'Supprimer' }}</span>
                </button>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  course: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'confirm'])

const isDeleting = ref(false)

const closeModal = () => {
  if (!isDeleting.value) {
    emit('close')
  }
}

const confirmDelete = async () => {
  isDeleting.value = true
  try {
    await emit('confirm')
  } finally {
    isDeleting.value = false
  }
}

// Fermer avec Escape
watch(() => props.show, (newValue) => {
  if (newValue) {
    const handleEscape = (e) => {
      if (e.key === 'Escape') {
        closeModal()
      }
    }
    document.addEventListener('keydown', handleEscape)
    
    // Cleanup
    return () => {
      document.removeEventListener('keydown', handleEscape)
    }
  }
})
</script>
