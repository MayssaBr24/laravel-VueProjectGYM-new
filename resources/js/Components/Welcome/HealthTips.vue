<template>
  <section id="conseils" class="py-20 bg-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-black text-white mb-4">CONSEILS <span class="text-green-400">SANTÉ</span></h2>
        <p class="text-xl text-gray-300">Des recommandations expertes pour améliorer votre bien-être</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <div v-for="(tip, index) in healthTips" :key="index" 
             class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-green-500 transform hover:-translate-y-2 transition-all duration-300">
          <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-500 to-green-400 rounded-full mb-4 mx-auto">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tip.icon"></path>
            </svg>
          </div>
          <h3 class="text-xl font-black text-white mb-3 text-center">{{ tip.title }}</h3>
          <p class="text-gray-300 text-center mb-4">{{ tip.description }}</p>
          <div class="text-center">
            <span class="inline-block bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-bold border border-green-500/30">
              {{ tip.category }}
            </span>
          </div>
        </div>
      </div>

      <div class="bg-gray-900 rounded-2xl p-8 border border-gray-700">
        <h3 class="text-2xl font-black text-white mb-6 text-center">MES NOTES <span class="text-green-400">PERSONNELLES</span></h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div>
            <label class="block text-sm font-bold text-gray-300 mb-2">AJOUTER UNE NOTE</label>
            <textarea v-model="newNote" 
                      placeholder="Écrivez vos observations, objectifs ou rappels..."
                      class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 h-32 resize-none"></textarea>
            <button @click="addNote" 
                    class="mt-3 bg-gradient-to-r from-green-500 to-green-400 text-white px-6 py-2 rounded-lg font-bold hover:from-green-600 hover:to-green-500 transition-all duration-200">
              AJOUTER LA NOTE
            </button>
          </div>
          <div>
            <h4 class="font-bold text-gray-300 mb-3">MES NOTES RÉCENTES</h4>
            <div class="space-y-3 max-h-64 overflow-y-auto">
              <div v-for="(note, index) in personalNotes" :key="index" 
                   class="bg-gray-800 p-3 rounded-lg border-l-4 border-green-500">
                <p class="text-gray-300 text-sm">{{ note.text }}</p>
                <div class="flex justify-between items-center mt-2">
                  <span class="text-xs text-gray-500">{{ formatDate(note.date) }}</span>
                  <button @click="removeNote(index)" 
                          class="text-red-400 hover:text-red-300 text-xs font-bold">
                    SUPPRIMER
                  </button>
                </div>
              </div>
              <div v-if="personalNotes.length === 0" class="text-gray-500 text-center py-8">
                Aucune note pour le moment
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const newNote = ref('')
const personalNotes = ref([])

const healthTips = [
  {
    title: "HYDRATATION",
    description: "Buvez au moins 8 verres d'eau par jour pour maintenir une hydratation optimale.",
    category: "NUTRITION",
    icon: "M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547A1.934 1.934 0 014 17.5c0 .775.301 1.52.828 2.047l8.38 8.38a2 2 0 002.784 0l8.38-8.38A2.898 2.898 0 0025 17.5c0-.775-.301-1.52-.828-2.047z"
  },
  {
    title: "SOMMEIL",
    description: "Dormez 7-9 heures par nuit pour permettre à votre corps de récupérer.",
    category: "RÉCUPÉRATION",
    icon: "M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
  },
  {
    title: "ALIMENTATION",
    description: "Privilégiez les aliments non transformés, riches en nutriments.",
    category: "NUTRITION",
    icon: "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
  },
  {
    title: "STRESS",
    description: "Pratiquez la méditation ou le yoga pour réduire le stress quotidien.",
    category: "MENTAL",
    icon: "M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
  }
]

const addNote = () => {
  if (newNote.value.trim()) {
    personalNotes.value.unshift({
      text: newNote.value.trim(),
      date: new Date().toISOString()
    })
    newNote.value = ''
    saveNotes()
  }
}

const removeNote = (index) => {
  personalNotes.value.splice(index, 1)
  saveNotes()
}

const saveNotes = () => {
  localStorage.setItem('healthNotes', JSON.stringify(personalNotes.value))
}

const loadNotes = () => {
  const saved = localStorage.getItem('healthNotes')
  if (saved) {
    personalNotes.value = JSON.parse(saved)
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadNotes()
})
</script>