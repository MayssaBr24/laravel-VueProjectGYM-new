<template>
  <div
    class="bg-gradient-to-br from-gray-800 via-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm p-6 max-w-sm hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-1"
  >
    <!-- Icone et titre -->
    <div class="flex items-center space-x-3 mb-3">
      <div class="p-2 bg-emerald-500/20 rounded-xl">
        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
      </div>
      <h3 class="text-xl font-bold text-white">Scanner Présence</h3>
    </div>

    <!-- Description -->
    <p class="text-sm text-gray-300 mb-6 leading-relaxed">
      Scanner les QR codes des membres pour enregistrer leur présence aux cours en temps réel.
    </p>

    <!-- Bouton d'action -->
    <button
      @click="$inertia.get('/client/scan-qr')"
      class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 border border-emerald-500/30 rounded-xl text-sm font-semibold text-white transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
      </svg>
      <span>Ouvrir le Scanner</span>
    </button>

   
  </div>
</template>

<script setup>
// Pas de script nécessaire, le bouton utilise Inertia pour la navigation
</script>

<style scoped>
.shadow-3xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Animation fluide pour le bouton */
button {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de profondeur au survol */
.hover\:-translate-y-1:hover {
  transform: translateY(-4px);
}
</style>