<template>
  <!-- Version finale avec votre style de bouton -->
  <div class="bg-gradient-to-br from-gray-800 to-green-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm">
    <div class="px-6 py-4 border-b border-green-800/50 bg-gray-800/80">
      <h3 class="text-lg font-bold text-white">Suivre vos cours</h3>
    </div>
    <div class="p-6">
      <p class="text-sm text-green-300 mb-4">
        Ouvrir votre calendrier pour avoir plus de cours
      </p>
      <Link
        href="/calendar"
        class="w-full flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-300"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Ouvrir le Calendrier
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>