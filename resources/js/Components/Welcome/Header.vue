<template>
  <header class="relative z-50 bg-gray-800/95 backdrop-blur-md border-b border-gray-700 sticky top-0">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">
        <div class="flex items-center">
          <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-400 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <span class="ml-3 text-xl font-bold text-white">HealthFit</span>
        </div>

        <div class="hidden md:flex items-center space-x-8">
          <a href="#accueil" class="text-gray-300 hover:text-green-400 transition-colors duration-200 font-medium">HOME</a>
          <a href="#imc" class="text-gray-300 hover:text-green-400 transition-colors duration-200 font-medium">ABOUT</a>
          <a href="#exercices" class="text-gray-300 hover:text-green-400 transition-colors duration-200 font-medium">CONTACT</a>
          <a href="#conseils" class="text-gray-300 hover:text-green-400 transition-colors duration-200 font-medium">SERVICES</a>
        </div>

        <div>
          <Link
            :href="route('login')"
            class="bg-gradient-to-r from-green-500 to-green-400 text-white px-6 py-2 rounded-full font-medium hover:from-green-600 hover:to-green-500 transform hover:scale-105 transition-all duration-200 inline-block"
          >
            Connexion
          </Link>
        </div>


        <div class="md:hidden">
          <button @click="toggleMobileMenu" class="text-gray-300 hover:text-green-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
      </div>

      <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-700">
        <div class="flex flex-col space-y-4">
          <a href="#accueil" class="text-gray-300 hover:text-green-400 transition-colors duration-200">HOME</a>
          <a href="#imc" class="text-gray-300 hover:text-green-400 transition-colors duration-200">ABOUT</a>
          <a href="#exercices" class="text-gray-300 hover:text-green-400 transition-colors duration-200">CONTACT</a>
          <a href="#conseils" class="text-gray-300 hover:text-green-400 transition-colors duration-200">SERVICES</a>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const mobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value
}
</script>