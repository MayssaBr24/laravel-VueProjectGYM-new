<template>
  <!-- Version moderne et épurée -->
  <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
    <div class="px-5 py-4 border-b border-gray-600 bg-gray-750">
      <h3 class="text-lg font-semibold text-white">Vos cours</h3>
    </div>
    <div class="p-5">
      <p class="text-sm text-gray-300 mb-4">
        Consultez votre calendrier pour découvrir plus de cours
      </p>
      <Link
        href="/client/calendar"
      class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 border border-emerald-500/30 rounded-xl text-sm font-semibold text-white transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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