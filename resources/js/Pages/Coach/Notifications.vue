<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

import { 
  Bell, 
  RefreshCw, 
  Search, 
  Plus, 
  Edit, 
  Trash2, 
  Check, 
  Activity,
  FileText
} from 'lucide-vue-next'

const props = defineProps({
  historiques: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

const historiques = ref(props.historiques || [])
const loading = ref(false)
const searchQuery = ref('')
const activeFilter = ref('all')

// Filtres disponibles
const filterOptions = [
  { key: 'all', label: 'Toutes' },
  { key: 'unread', label: 'Non lues' },
  { key: 'add', label: 'Ajouts' },
  { key: 'update', label: 'Modifications' },
  { key: 'delete', label: 'Suppressions' }
]

// Statistiques calculées
const stats = computed(() => {
  return {
    ajouts: historiques.value.filter(h => h.action === 'add').length,
    modifications: historiques.value.filter(h => h.action === 'update').length,
    suppressions: historiques.value.filter(h => h.action === 'delete').length
  }
})

// Filtrage des historiques
const filteredHistoriques = computed(() => {
  let filtered = historiques.value

  // Filtre par type
  if (activeFilter.value === 'unread') {
    filtered = filtered.filter(h => !h.is_read)
  } else if (activeFilter.value !== 'all') {
    filtered = filtered.filter(h => h.action === activeFilter.value)
  }

  // Filtre par recherche
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(h => 
      h.course?.name?.toLowerCase().includes(query) ||
      h.details?.toLowerCase().includes(query) ||
      getActionLabel(h.action).toLowerCase().includes(query)
    )
  }

  return filtered
})

// Fonctions utilitaires
const getActionIcon = (action) => {
  const icons = {
    add: Plus,
    update: Edit,
    delete: Trash2
  }
  return icons[action] || Bell
}

const getActionColor = (action) => {
  const colors = {
    add: { bg: 'bg-emerald-500/10', text: 'text-emerald-400' },
    update: { bg: 'bg-amber-500/10', text: 'text-amber-400' },
    delete: { bg: 'bg-red-500/10', text: 'text-red-400' }
  }
  return colors[action] || { bg: 'bg-emerald-500/10', text: 'text-emerald-400' }
}

const getActionLabel = (action) => {
 const labels = {
  add: 'Cours ajouté',
  update: 'Cours modifié',
  delete: 'Cours supprimé',
  reservation: 'Réservation de cours',
  delete_reservation: 'Annulation de réservation', 
}

  return labels[action] || 'Action inconnue'
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const setFilter = (filter) => {
  activeFilter.value = filter
}

// Actions
const fetchHistoriques = async () => {
  loading.value = true
  try {
    // Recharger la page via Inertia pour avoir les données fraîches
    router.reload()
  } catch (error) {
    console.error('Erreur lors du chargement:', error)
  } finally {
    loading.value = false
  }
}

const markAsRead = async (id) => {
  try {
    await axios.post(`/client/historique/${id}/read`) // ✅ Correction de la route
    // Mettre à jour localement
    const historique = historiques.value.find(h => h.id === id)
    if (historique) {
      historique.is_read = true
    }
  } catch (error) {
    console.error('Erreur:', error)
  }
}

onMounted(() => {
  // Les données sont déjà fournies par Inertia via les props
  console.log('Historiques chargés:', historiques.value)
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- En-tête -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white mb-2">Historique des Activités</h1>
            <p class="text-gray-400">Suivez toutes les modifications de vos cours par les coachs</p>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              @click="fetchHistoriques"
              class="flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
            >
              <RefreshCw class="w-4 h-4 mr-2" />
              Actualiser
            </button>
          </div>
        </div>
      </div>

      <!-- Statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <div class="flex items-center">
            <div class="p-3 bg-blue-500/10 rounded-lg mr-4">
              <Activity class="w-6 h-6 text-blue-400" />
            </div>
            <div>
              <p class="text-sm text-gray-400">Total des activités</p>
              <p class="text-2xl font-bold text-white">{{ historiques.length }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <div class="flex items-center">
            <div class="p-3 bg-emerald-500/10 rounded-lg mr-4">
              <Plus class="w-6 h-6 text-emerald-400" />
            </div>
            <div>
              <p class="text-sm text-gray-400">Ajouts récents</p>
              <p class="text-2xl font-bold text-white">{{ stats.ajouts }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
          <div class="flex items-center">
            <div class="p-3 bg-amber-500/10 rounded-lg mr-4">
              <Edit class="w-6 h-6 text-amber-400" />
            </div>
            <div>
              <p class="text-sm text-gray-400">Modifications</p>
              <p class="text-2xl font-bold text-white">{{ stats.modifications }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
          <div class="flex flex-wrap gap-4">
            <button 
              v-for="filter in filterOptions"
              :key="filter.key"
              @click="setFilter(filter.key)"
              :class="[
                'px-4 py-2 rounded-lg transition-all font-medium',
                activeFilter === filter.key 
                  ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg'
                  : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
              ]"
            >
              {{ filter.label }}
            </button>
          </div>
          
          <div class="relative w-full sm:w-64">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher..."
              class="w-full pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
        </div>
      </div>

      <!-- Liste des historiques -->
      <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <!-- En-tête du tableau -->
        <div class="px-6 py-4 border-b border-gray-700 bg-gray-750">
          <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-300">
            <div class="col-span-4">Action</div>
            <div class="col-span-3">Cours</div>
            <div class="col-span-3">Date</div>
            <div class="col-span-2 text-center">Statut</div>
          </div>
        </div>

        <!-- Contenu -->
        <div class="divide-y divide-gray-700">
          <div 
            v-for="historique in filteredHistoriques" 
            :key="historique.id"
            class="px-6 py-4 hover:bg-gray-750 transition-colors group"
          >
            <div class="grid grid-cols-12 gap-4 items-center">
              <!-- Action -->
              <div class="col-span-4">
                <div class="flex items-center space-x-3">
                  <div 
                    :class="[
                      'p-2 rounded-lg',
                      getActionColor(historique.action).bg
                    ]"
                  >
                    <component 
                      :is="getActionIcon(historique.action)" 
                      :class="[
                        'w-4 h-4',
                        getActionColor(historique.action).text
                      ]" 
                    />
                  </div>
                  <div>
                    <p class="text-white font-medium">{{ getActionLabel(historique.action) }}</p>
                    <p class="text-sm text-gray-400">Par {{ historique.user?.name || 'le coach' }}</p>
                  </div>
                </div>
              </div>

              <!-- Cours -->
              <div class="col-span-3">
                <p class="text-white font-medium">{{ historique.course?.name || 'Cours non spécifié' }}</p>
                <p class="text-sm text-gray-400">{{ historique.course?.category || 'Fitness' }}</p>
              </div>

              <!-- Date -->
              <div class="col-span-3">
                <p class="text-white">{{ formatDate(historique.action_at) }}</p>
                <p class="text-sm text-gray-400">{{ formatTime(historique.action_at) }}</p>
              </div>

              <!-- Statut -->
              <div class="col-span-2 text-center">
                <span 
                  :class="[
                    'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                    historique.is_read 
                      ? 'bg-gray-700 text-gray-300' 
                      : 'bg-blue-500/20 text-blue-300'
                  ]"
                >
                  {{ historique.is_read ? 'Lu' : 'Non lu' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- État vide -->
        <div v-if="filteredHistoriques.length === 0" class="text-center py-12">
          <div class="mx-auto h-16 w-16 bg-gray-700 rounded-full flex items-center justify-center mb-4">
            <FileText class="w-8 h-8 text-gray-500" />
          </div>
          <h3 class="text-lg font-medium text-white mb-2">Aucune activité</h3>
          <p class="text-gray-400">Aucune activité récente à afficher.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>