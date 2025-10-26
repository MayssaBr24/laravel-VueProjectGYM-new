<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { Bell, RefreshCw, Eye, ExternalLink, X, CheckCheck } from 'lucide-vue-next'

const showDropdown = ref(false)
const notifications = ref([])
const loading = ref(false)
const hasNewNotifications = ref(false)

const fetchNotifications = async () => {
  loading.value = true
  try {
    const { data } = await axios.get('/client/notifications')
    console.log("Notifications récupérées :", data)
    notifications.value = data
    hasNewNotifications.value = data.length > 0
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

const toggleDropdown = async () => {
  showDropdown.value = !showDropdown.value
  if (showDropdown.value) {
    await fetchNotifications()
  }
}

const markAsRead = async (id, e) => {
  e?.stopPropagation()
  try {
    await axios.post(`/client/notifications/${id}/read`)
    notifications.value = notifications.value.filter(n => n.id !== id)
    if (notifications.value.length === 0) {
      hasNewNotifications.value = false
    }
  } catch (error) {
    console.error(error)
  }
}

const markAllAsRead = async () => {
  try {
    await axios.post('/client/notifications/mark-all-read')
    notifications.value = []
    hasNewNotifications.value = false
  } catch (error) {
    console.error(error)
  }
}

const closeDropdown = (event) => {
  if (!event.target.closest('.notification-panel')) {
    showDropdown.value = false
  }
}

onMounted(() => {
  fetchNotifications()
  document.addEventListener('click', closeDropdown)
})

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown)
})
</script>

<template>
  <div class="relative notification-panel">
    <!-- Bouton alarme premium -->
    <button
      @click="toggleDropdown"
      class="relative p-3 rounded-2xl bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl border border-gray-700/50 hover:border-emerald-500/30 group backdrop-blur-sm"
      :class="{ 'ring-2 ring-emerald-500/50': showDropdown }"
      aria-label="Notifications"
    >
      <div class="relative">
        <Bell class="w-5 h-5 text-emerald-400 group-hover:text-emerald-300 transition-all duration-300 group-hover:scale-110" />
        <span 
          v-if="hasNewNotifications" 
          class="absolute -top-2 -right-2 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center shadow-lg animate-ping-once"
        >
          <span class="text-[10px] font-bold text-white">{{ notifications.length > 9 ? '9+' : notifications.length }}</span>
        </span>
      </div>
    </button>

    <!-- Dropdown notifications premium -->
    <transition name="notification-slide">
      <div
        v-if="showDropdown"
        class="absolute right-0 mt-3 w-96 bg-gray-900/95 rounded-2xl shadow-2xl ring-2 ring-emerald-500/20 z-50 max-h-96 overflow-hidden border border-gray-700/50 backdrop-blur-xl"
      >
        <!-- Header premium -->
        <div class="px-6 py-4 border-b border-gray-700/50 bg-gradient-to-r from-gray-800/80 to-gray-900/80">
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-xl bg-emerald-500/10 ring-1 ring-emerald-500/20">
                <Bell class="w-5 h-5 text-emerald-400" />
              </div>
              <div>
                <h3 class="text-lg font-bold text-white">Notifications</h3>
                <p class="text-xs text-gray-400 mt-1">{{ notifications.length }} activité(s) récente(s)</p>
              </div>
            </div>
            <div class="flex items-center gap-1">
              <button 
                v-if="notifications.length > 0"
                @click="markAllAsRead"
                class="p-2 rounded-xl bg-gray-800/50 hover:bg-emerald-500/10 transition-all duration-200 group"
                title="Tout marquer comme lu"
              >
                <CheckCheck class="w-4 h-4 text-gray-400 group-hover:text-emerald-400" />
              </button>
              <button 
                @click="fetchNotifications" 
                class="p-2 rounded-xl bg-gray-800/50 hover:bg-gray-700/50 transition-all duration-200 group"
                :class="{ 'animate-spin': loading }"
                title="Rafraîchir"
              >
                <RefreshCw class="w-4 h-4 text-gray-400 group-hover:text-emerald-400" />
              </button>
              <button 
                @click="showDropdown = false"
                class="p-2 rounded-xl bg-gray-800/50 hover:bg-gray-700/50 transition-all duration-200"
                title="Fermer"
              >
                <X class="w-4 h-4 text-gray-400 hover:text-white" />
              </button>
            </div>
          </div>
        </div>

        <!-- Contenu -->
        <div class="overflow-y-auto max-h-80 custom-scrollbar">
          <!-- Loading state premium -->
          <div v-if="loading" class="p-6 space-y-4">
            <div v-for="i in 3" :key="i" class="animate-pulse">
              <div class="flex gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-700/50"></div>
                <div class="flex-1 space-y-2">
                  <div class="h-3 bg-gray-700/50 rounded w-3/4"></div>
                  <div class="h-2 bg-gray-700/50 rounded w-1/2"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Aucun résultat premium -->
          <div v-if="!loading && !notifications.length" class="p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-800/50 flex items-center justify-center ring-1 ring-gray-700/50">
              <Bell class="w-6 h-6 text-gray-500" />
            </div>
            <p class="text-gray-400 text-sm font-medium">Aucune notification</p>
            <p class="text-gray-500 text-xs mt-1">Tout est à jour !</p>
          </div>

          <!-- Liste notifications premium -->
          <ul v-else class="divide-y divide-gray-800/50">
            <li 
              v-for="n in notifications" 
              :key="n.id" 
              class="px-6 py-4 hover:bg-gray-800/30 transition-all duration-200 group cursor-pointer border-l-4 border-l-transparent hover:border-l-emerald-500 relative"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-r-lg"></div>
              
              <div class="flex items-start justify-between relative z-10">
                <div class="flex items-start gap-3 flex-1 min-w-0">
                  <!-- Avatar premium -->
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-lg ring-2 ring-emerald-500/20 flex-shrink-0">
                    {{ n.user?.name?.charAt(0).toUpperCase() || 'U' }}
                  </div>

                  <!-- Contenu premium -->
                  <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-200 leading-relaxed">
                      <span class="font-semibold text-white bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                        {{ n.user?.name || 'Un utilisateur' }}
                      </span>
                      
                      <template v-if="n.action === 'add'">
                        a ajouté un nouveau cours
                      </template>
                      <template v-else-if="n.action === 'update'">
                        a mis à jour le cours
                      </template>
                      <template v-else-if="n.action === 'delete'">
                        a supprimé le cours
                      </template>
                      <template v-else-if="n.action === 'delete_reservation'">
                        a annulé sa réservation pour
                      </template>
                      <template v-else>
                        a réservé une place dans
                      </template>

                      <span class="font-bold bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent ml-1">
                        « {{ n.course?.name || 'cours inconnu' }} »
                      </span>
                    </p>

                    <div class="flex items-center gap-2 mt-2">
                      <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full flex-shrink-0"></span>
                      <p class="text-xs text-gray-500 font-medium">
                        {{ new Date(n.action_at).toLocaleString('fr-FR', { 
                          day: 'numeric',
                          month: 'short',
                          hour: '2-digit',
                          minute: '2-digit'
                        }) }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Bouton marquer comme lu premium -->
                <button
                  @click="markAsRead(n.id, $event)"
                  class="ml-3 p-2 rounded-xl bg-gray-800/50 hover:bg-emerald-500/10 transition-all duration-200 opacity-70 group-hover:opacity-100 border border-gray-700/50 hover:border-emerald-500/30 flex-shrink-0 transform group-hover:scale-110"
                  title="Marquer comme lu"
                >
                  <Eye class="w-3.5 h-3.5 text-emerald-400" />
                </button>
              </div>
            </li>
          </ul>
        </div>

        <!-- Footer premium -->
        <div class="px-6 py-4 border-t border-gray-700/50 bg-gradient-to-r from-gray-800/80 to-gray-900/80">
          <a 
            href="/client/notifications/all" 
            class="w-full py-3 px-4 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 rounded-xl text-sm text-white font-semibold transition-all duration-200 flex items-center justify-center gap-2 group border border-gray-700/50 hover:border-emerald-500/30 hover:shadow-lg hover:shadow-emerald-500/10"
          >
            <span>Voir toutes les notifications</span>
            <ExternalLink class="w-3.5 h-3.5 text-emerald-400 group-hover:scale-110 transition-transform" />
          </a>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.notification-slide-enter-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.notification-slide-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 1, 1);
}

.notification-slide-enter-from {
  opacity: 0;
  transform: translateY(-8px) scale(0.95);
}

.notification-slide-leave-to {
  opacity: 0;
  transform: translateY(8px) scale(0.95);
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-ping-once {
  animation: ping-once 1s cubic-bezier(0, 0, 0.2, 1);
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

@keyframes ping-once {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  75%, 100% {
    transform: scale(2);
    opacity: 0;
  }
}

/* Scrollbar personnalisée premium */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(31, 41, 55, 0.5);
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #10b981, #059669);
  border-radius: 4px;
  border: 2px solid rgba(31, 41, 55, 0.5);
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #34d399, #10b981);
}

/* Effets de backdrop */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
}

.backdrop-blur-xl {
  backdrop-filter: blur(20px);
}
</style>