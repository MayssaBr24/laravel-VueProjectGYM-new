<template>
  <section id="exercices" class="py-20 bg-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-black text-white mb-4">EXERCICES DU <span class="text-green-400">JOUR</span></h2>
        <p class="text-xl text-gray-300">Découvrez une sélection d'exercices adaptés à vos objectifs</p>
      </div>

      <div class="relative">
        <div class="overflow-hidden rounded-2xl">
          <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
            <div v-for="(exercise, index) in exercises" :key="index" class="w-full flex-shrink-0">
              <div class="bg-gray-800 p-8 mx-2 rounded-xl border border-gray-700">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                  <div>
                    <div class="w-full h-64 rounded-xl overflow-hidden mb-4 border border-gray-600">
                      <img :src="exercise.image" :alt="exercise.name" class="w-full h-full object-cover">
                    </div>
                  </div>
                  <div>
                    <h3 class="text-3xl font-black text-white mb-4">{{ exercise.name }}</h3>
                    <p class="text-gray-300 mb-6 text-lg">{{ exercise.description }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                      <div class="bg-gray-700 p-4 rounded-lg text-center border border-gray-600">
                        <div class="text-2xl font-black text-green-400">{{ exercise.duration }}</div>
                        <div class="text-sm text-gray-400">DURÉE</div>
                      </div>
                      <div class="bg-gray-700 p-4 rounded-lg text-center border border-gray-600">
                        <div class="text-2xl font-black text-red-400">{{ exercise.calories }}</div>
                        <div class="text-sm text-gray-400">CALORIES</div>
                      </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                      <span v-for="benefit in exercise.benefits" :key="benefit" 
                            class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-bold border border-green-500/30">
                        {{ benefit }}
                      </span>
                    </div>
                    
                    <button class="bg-gradient-to-r from-green-500 to-green-400 text-white px-8 py-3 rounded-lg font-bold hover:from-green-600 hover:to-green-500 transform hover:scale-105 transition-all duration-300">
                      COMMENCER L'EXERCICE
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <button @click="prevSlide" 
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-gray-800/80 hover:bg-gray-700 text-green-400 p-3 rounded-full border border-gray-600 hover:border-green-500 transition-all duration-200">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        
        <button @click="nextSlide" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-gray-800/80 hover:bg-gray-700 text-green-400 p-3 rounded-full border border-gray-600 hover:border-green-500 transition-all duration-200">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>

        <div class="flex justify-center mt-8 space-x-2">
          <button v-for="(exercise, index) in exercises" :key="index" 
                  @click="currentSlide = index"
                  class="w-3 h-3 rounded-full transition-all duration-200"
                  :class="currentSlide === index ? 'bg-green-400' : 'bg-gray-600'">
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const currentSlide = ref(0)
let autoSlideInterval = null

const exercises = [
  {
    name: "POMPES",
    description: "Exercice complet pour renforcer le haut du corps, les bras, la poitrine et les épaules.",
    duration: "15 MIN",
    calories: "120 CAL",
    benefits: ["RENFORCEMENT", "CARDIO", "ENDURANCE"],
    image: "https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
  },
  {
    name: "SQUATS",
    description: "Parfait pour tonifier les jambes, les fessiers et améliorer la stabilité du core.",
    duration: "20 MIN",
    calories: "150 CAL",
    benefits: ["JAMBES", "FESSIERS", "ÉQUILIBRE"],
    image: "https://images.unsplash.com/photo-1534258936925-c58bed479fcb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
  },
  {
    name: "PLANCHE",
    description: "Exercice isométrique excellent pour renforcer les abdominaux et stabiliser le tronc.",
    duration: "10 MIN",
    calories: "80 CAL",
    benefits: ["CORE", "STABILITÉ", "POSTURE"],
    image: "https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
  }
]

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % exercises.length
}

const prevSlide = () => {
  currentSlide.value = currentSlide.value === 0 ? exercises.length - 1 : currentSlide.value - 1
}

onMounted(() => {
  autoSlideInterval = setInterval(nextSlide, 5000)
})

onUnmounted(() => {
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval)
  }
})
</script>