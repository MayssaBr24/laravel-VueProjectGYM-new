<template>
  <div class="group bg-gradient-to-r from-gray-800/40 to-gray-900/40 backdrop-blur-sm rounded-xl p-4 border border-gray-700/30 hover:border-emerald-500/30 transition-all duration-300 hover:transform hover:scale-105">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
          {{ participant.name.charAt(0).toUpperCase() }}
        </div>
        <div>
          <h3 class="font-semibold text-white group-hover:text-emerald-300 transition-colors">
            {{ participant.name }}
          </h3>
          <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">
            {{ participant.email }}
          </p>
        </div>
      </div>
      <div class="text-right">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z" />
          </svg>
          <span>Inscrit le</span>
        </div>
        <p class="text-sm text-gray-300 font-medium">
          {{ formatDate(participant.reserved_at) }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  participant: {
    type: Object,
    required: true
  },
  index: {
    type: Number,
    default: 0
  }
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}
</script>
