<template>
  <div class="bg-gray-800 rounded-2xl border border-gray-600/50 shadow-lg p-6">
    <h3 class="text-lg font-bold text-white mb-4">Réservations en attente de présence</h3>

    <template v-if="pendingAttendances.length === 0">
      <p class="text-gray-400 text-sm">Aucune présence à vérifier.</p>
    </template>

    <template v-else>
      <div
        v-for="attendance in pendingAttendances"
        :key="attendance.id"
        class="bg-gray-900 p-4 rounded-xl mb-3 flex justify-between items-center"
      >
        <div>
          <p class="text-white font-semibold">{{ attendance.user.name }}</p>
          <p class="text-sm text-gray-400">{{ attendance.course?.name }}</p>          <p class="text-xs text-gray-500 italic">
            Début : {{ formatDateTime(attendance.course?.start_time) }}
          </p>
        </div>
        <button
          @click="markAttendance(attendance.id)"
          class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
        >
          Marquer présent
        </button>

      </div>
    </template>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  pendingAttendances: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update-attendance'])

const markAttendance = (attendanceId) => {
  router.post(`/admin/attendances/${attendanceId}/verify`, {}, {
    onSuccess: () => emit('update-attendance')
  })
}


const formatDateTime = (date) => {
  return new Date(date).toLocaleString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
