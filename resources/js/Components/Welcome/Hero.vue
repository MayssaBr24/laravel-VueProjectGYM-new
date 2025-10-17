<template>
  <section id="accueil" class="relative min-h-screen flex items-center justify-center bg-gray-900">
    <div class="absolute inset-0">
      <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900"></div>
      <div class="absolute top-0 right-0 w-1/2 h-full">
        <div class="absolute inset-0 bg-gradient-to-l from-green-500/20 to-transparent"></div>
        <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-green-500/10 rounded-full blur-3xl"></div>
      </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <h1 class="text-6xl md:text-8xl font-black text-white mb-6 leading-tight">
            BE <span class="text-green-400">STRONG</span><br>
            BE THE<br>
            <span class="text-green-400">CHAMPION!</span>
          </h1>
          
          <p class="text-xl text-gray-300 mb-8 max-w-lg">
            Transformez votre corps, dépassez vos limites et atteignez vos objectifs avec notre plateforme de fitness complète.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 mb-12">
            <button @click="scrollToIMC" class="bg-gradient-to-r from-green-500 to-green-400 text-white px-8 py-4 rounded-full text-lg font-bold hover:from-green-600 hover:to-green-500 transform hover:scale-105 transition-all duration-300">
              CALCULER MON IMC
            </button>
            <button class="border-2 border-green-400 text-green-400 px-8 py-4 rounded-full text-lg font-bold hover:bg-green-400 hover:text-gray-900 transition-all duration-300">
              VOIR LES EXERCICES
            </button>
          </div>

          <div class="flex space-x-8">
            <div class="text-center">
              <div class="text-3xl font-black text-green-400 mb-2">10K+</div>
              <div class="text-gray-400 text-sm">UTILISATEURS</div>
            </div>
            <div class="text-center">
              <div class="text-3xl font-black text-green-400 mb-2">500+</div>
              <div class="text-gray-400 text-sm">EXERCICES</div>
            </div>
            <div class="text-center">
              <div class="text-3xl font-black text-green-400 mb-2">24/7</div>
              <div class="text-gray-400 text-sm">SUPPORT</div>
            </div>
          </div>
        </div>

        <div class="relative">
          <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-green-400/20 rounded-3xl transform rotate-3"></div>
          <div class="relative bg-gray-800 rounded-3xl p-8 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
            <div class="w-full h-96 rounded-2xl overflow-hidden">
              <img src="https://images.unsplash.com/photo-1581009137042-c552e485697a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" 
                   alt="Homme musclé s'entraînant en salle de sport" 
                   class="w-full h-full object-cover">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
      <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
      </svg>
    </div>
  </section>
</template>

<script setup>
const scrollToIMC = () => {
  document.getElementById('imc').scrollIntoView({ behavior: 'smooth' })
}
</script>