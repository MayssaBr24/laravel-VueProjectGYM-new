<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6">
        
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/50 p-6 md:p-8 shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20 transform hover:scale-105 transition-transform">
              <Dumbbell class="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 class="text-3xl font-bold text-balance">{{ course.name }}</h1>
          <p class="text-slate-400 mt-1">{{ formatDate(course.start_time) }}</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
           
            <button
              @click="refreshParticipants"
              class="px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-lg shadow-emerald-500/20 flex items-center gap-2 transform hover:scale-105"
            >
              <RefreshCw :class="['w-4 h-4', isRefreshing && 'animate-spin']" />
              <span class="font-medium">Actualiser</span>
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/50 p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-3">
              <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                <Users class="w-6 h-6 text-blue-400" />
              </div>
              <span class="text-3xl font-bold">{{ stats.total }}</span>
            </div>
            <p class="text-slate-400 text-sm">Total Participants</p>
          </div>
        </div>

          
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-emerald-700/50 p-6 shadow-xl hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-3">
              <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                <CheckCircle2 class="w-6 h-6 text-emerald-400" />
              </div>
              <span class="text-3xl font-bold text-emerald-400">{{ stats.present }}</span>
            </div>
            <p class="text-slate-400 text-sm">Présents</p>
            <div class="mt-2 h-2 bg-slate-700/50 rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-500" :style="{ width: stats.presentPercentage + '%' }"></div>
            </div>
          </div>
        </div>

        
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-red-700/50 p-6 shadow-xl hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-3">
              <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center animate-pulse">
                <ShieldAlert class="w-6 h-6 text-red-400" />
              </div>
              <span class="text-3xl font-bold text-red-400">{{ stats.intrus }}</span>
            </div>
            <p class="text-slate-400 text-sm">Intrus Détectés</p>
            <div v-if="stats.intrus > 0" class="mt-2 flex items-center gap-2 text-xs text-red-400">
              <AlertTriangle class="w-3 h-3" />
              <span>Attention requise</span>
            </div>
          </div>
        </div>

         
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-purple-700/50 p-6 shadow-xl hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-3">
              <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                <BadgeCheck class="w-6 h-6 text-purple-400" />
              </div>
              <span class="text-3xl font-bold text-purple-400">{{ stats.verified }}</span>
            </div>
            <p class="text-slate-400 text-sm">Vérifiés</p>
          </div>
        </div>
      </div>

        
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/50 shadow-2xl">
        <div class="p-6 border-b border-slate-700/50 flex items-center justify-between">
          <h2 class="text-xl font-bold flex items-center gap-2">
            <ClipboardList class="w-5 h-5 text-blue-400" />
            Liste des présences
          </h2>
          <div class="flex items-center gap-2">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher..."
              class="px-4 py-2 rounded-xl bg-slate-900/50 border border-slate-700/50 text-sm focus:outline-none focus:border-blue-500/50 transition-colors"
            />
            <Search class="w-4 h-4 text-slate-400" />
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-900/50 text-slate-300 text-sm uppercase tracking-wider">
              <tr>
                <th class="px-6 py-4 text-left">#</th>
                <th class="px-6 py-4 text-left">Participant</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Statut</th>
                <th class="px-6 py-4 text-left">Action</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-700/30">
              <tr
                v-for="(attendance, index) in filteredParticipants"
                :key="attendance.id"
                class="group hover:bg-slate-800/30 transition-colors"
              >
                <td class="px-6 py-4 text-slate-400">{{ index + 1 }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-sm shadow-lg">
                      {{ getInitials(attendance.user?.name) }}
                    </div>
                    <span class="font-medium">{{ attendance.user?.name }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-slate-400">{{ attendance.user?.email }}</td>
                <td class="px-6 py-4">
                  <span
                    :class="getStatusClass(attendance.status)"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-sm font-semibold shadow-lg"
                  >
                    <component :is="getStatusIcon(attendance.status)" class="w-4 h-4" />
                    {{ getStatusLabel(attendance.status) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <select
                    v-model="attendance.status"
                    @change="updateStatus(attendance.id, attendance.status)"
                    class="px-3 py-2 rounded-xl bg-slate-900/50 border border-slate-700/50 text-sm focus:outline-none focus:border-blue-500/50 transition-colors cursor-pointer hover:bg-slate-900"
                  >
                    <option value="present">✓ Présent</option>
                    <option value="absent">✗ Absent</option>
                    <option value="intrus">⚠ Intrus</option>
                    <option value="verified">✓ Vérifié</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>

          <div v-if="!filteredParticipants.length" class="p-12 text-center">
            <UserX class="w-16 h-16 text-slate-600 mx-auto mb-4" />
            <p class="text-slate-400 text-lg">Aucun participant trouvé</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  Dumbbell,
  RefreshCw,
  Users,
  CheckCircle2,
  ShieldAlert,
  BadgeCheck,
  ClipboardList,
  Search,
  UserX,
  AlertTriangle,
  XCircle,
  Clock
} from 'lucide-vue-next'

const props = defineProps({
  course: Object,
  attendances: Array,
})

const participants = ref(props.attendances || [])
const searchQuery = ref('')
const isRefreshing = ref(false)

// Computed statistics
const stats = computed(() => {
  const total = participants.value.length
  const present = participants.value.filter(a => a.status === 'present').length
  const intrus = participants.value.filter(a => a.status === 'intrus').length
  const verified = participants.value.filter(a => a.status === 'verified').length
  const absent = participants.value.filter(a => a.status === 'absent').length
  
  return {
    total,
    present,
    intrus,
    verified,
    absent,
    presentPercentage: total > 0 ? Math.round((present / total) * 100) : 0
  }
})

// Filtered participants based on search
const filteredParticipants = computed(() => {
  if (!searchQuery.value) return participants.value
  
  const query = searchQuery.value.toLowerCase()
  return participants.value.filter(a => 
    a.user?.name?.toLowerCase().includes(query) ||
    a.user?.email?.toLowerCase().includes(query)
  )
})

// Get user initials
const getInitials = (name) => {
  if (!name) return '?'
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

// Get status styling
const getStatusClass = (status) => {
  const classes = {
    present: 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
    intrus: 'bg-red-500/20 text-red-400 border border-red-500/30 animate-pulse',
    absent: 'bg-slate-500/20 text-slate-400 border border-slate-500/30',
    verified: 'bg-purple-500/20 text-purple-400 border border-purple-500/30'
  }
  return classes[status] || classes.absent
}

// Get status icon
const getStatusIcon = (status) => {
  const icons = {
    present: CheckCircle2,
    intrus: ShieldAlert,
    absent: XCircle,
    verified: BadgeCheck
  }
  return icons[status] || Clock
}

// Get status label
const getStatusLabel = (status) => {
  const labels = {
    present: 'Présent',
    intrus: 'Intrus',
    absent: 'Absent',
    verified: 'Vérifié'
  }
  return labels[status] || status
}

// Update attendance status
const updateStatus = async (attendanceId, status) => {
  try {
    await router.post(`/coach/attendances/${attendanceId}/update-status`, { status }, {
      preserveScroll: true,
      onSuccess: () => {
        console.log('✅ Statut mis à jour')
      }
    })
  } catch (err) {
    alert('Erreur lors de la mise à jour du statut.')
    console.error(err)
  }
}

// Refresh participants list
const refreshParticipants = async () => {
  isRefreshing.value = true
  try {
    const response = await fetch(`/coach/courses/${props.course.id}/participants-json`, {
      headers: { Accept: 'application/json' },
      credentials: 'include',
    })
    const data = await response.json()
    participants.value = data.participants
  } catch (err) {
    console.error('Erreur lors du rafraîchissement', err)
  } finally {
    setTimeout(() => {
      isRefreshing.value = false
    }, 500)
  }
}

// Format date
const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  const date = new Date(dateStr)
  return date.toLocaleString('fr-FR', {
    dateStyle: 'medium',
    timeStyle: 'short'
  })
}
</script>
