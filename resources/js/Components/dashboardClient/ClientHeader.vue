<template>
  <header class="bg-gradient-to-r from-slate-900/95 via-gray-900/95 to-slate-900/95 backdrop-blur-2xl border-b border-white/10 sticky top-0 z-50 shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-6">
          <div class="relative">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 via-cyan-400 to-blue-500 rounded-2xl flex items-center justify-center shadow-2xl transform hover:scale-105 transition-all duration-300">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
    </div>
          
          <!-- Welcome Message - Version grande et moderne -->
          <div class="hidden md:block ml-4">
            <div class="text-3xl font-black tracking-tight">
              <span class="text-white/80">Bienvenue, </span>
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-cyan-400 to-blue-400 animate-gradient">
                {{ user.name }} 
              </span>
              <span class="text-emerald-400 animate-bounce inline-block text-4xl">!</span>
            </div>
            <div class="text-sm text-white/60 font-medium mt-1">
              Ravi de vous revoir
            </div>
          </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-6">
        <Historique/>
          <!-- Profile Dropdown - Version simplifiée avec image bouton -->
          <div class="relative">
            <button 
              @click="toggleProfileMenu" 
              class="relative group focus:outline-none focus:ring-0 border-0 shadow-none p-0 bg-transparent"
            >
              <!-- Avatar agrandi comme bouton principal -->
              <div class="relative transform group-hover:scale-110 transition-all duration-300">
                <div class="blob-shape w-24 h-20 mt-2 bg-gradient-to-br from-emerald-400 via-cyan-400 to-blue-500 p-1 shadow-2xl">
  <img
    :src="user.avatar_url || '/default-avatar.png'"
    class="w-full h-full object-cover blob-shape-inner transform translate-y-1"
    alt="Photo de profil"
  />
</div>
               
                <!-- Indicateur de menu ouvert -->
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-cyan-400 rounded-full border-2 border-gray-900 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                     :class="{ 'opacity-100': isProfileMenuOpen }">
                </div>
              </div>
            </button>

            <!-- Dropdown Menu -->
            <transition
              enter-active-class="transition duration-300 ease-out"
              enter-from-class="transform scale-95 opacity-0 translate-y-2"
              enter-to-class="transform scale-100 opacity-100 translate-y-0"
              leave-active-class="transition duration-200 ease-in"
              leave-from-class="transform scale-100 opacity-100 translate-y-0"
              leave-to-class="transform scale-95 opacity-0 translate-y-2"
            >
              <div
                v-show="isProfileMenuOpen"
                @click.away="isProfileMenuOpen = false"
                class="absolute right-0 mt-4 w-80 bg-gray-900/95 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-2xl z-50 overflow-hidden"
              >
                <!-- Profile Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-gray-800/50 via-gray-700/50 to-gray-800/50 border-b border-white/10">
                  <div class="flex items-center gap-6">
                    <!-- Avatar encore plus grand dans le dropdown -->
                    <div class="relative">
                      <div class="blob-shape-large w-24 h-24 bg-gradient-to-br from-emerald-400 via-cyan-400 to-blue-500 p-1 shadow-2xl">
                        <img
                          v-if="user"
                          :src="user.avatar_url || '/default-avatar.png'"
                          alt="Avatar"
                          class="w-full h-full object-cover blob-shape-large-inner"
                        />
                        <img
                          v-else
                          src="/default-avatar.png"
                          alt="Avatar par défaut"
                          class="w-full h-full object-cover blob-shape-large-inner"
                        />
                      </div>
                      <div class="absolute -inset-3 bg-gradient-to-r from-emerald-400/20 to-cyan-400/20 rounded-full blur-xl animate-pulse"></div>
                    </div>
                    
                    <div class="flex-1">
                      <p class="text-white font-bold text-lg">{{ user.name }}</p>
                      <p class="text-white/70 text-sm">{{ user.email }}</p>
                      <div class="flex items-center gap-2 mt-2">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-white/50 text-xs">En ligne</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Menu Items -->
                <div class="py-3">
                  <Link
                    :href="route('profile.show')"
                    class="flex items-center gap-4 px-8 py-4 text-white/80 hover:text-white hover:bg-white/5 transition-all duration-300 group"
                  >
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                      <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    <div>
                      <p class="font-semibold text-base">Mon Profil</p>
                      <p class="text-xs text-white/50">Gérer mes informations</p>
                    </div>
                  </Link>

                  <div class="border-t border-white/10 my-3 mx-6"></div>

                  <button
                    @click="logout"
                    class="w-full flex items-center gap-4 px-8 py-4 text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all duration-300 group"
                  >
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                      <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                      </svg>
                    </div>
                    <div class="text-left">
                      <p class="font-semibold text-base">Déconnexion</p>
                      <p class="text-xs text-white/50">Se déconnecter du compte</p>
                    </div>
                  </button>
                </div>
              </div>
            </transition>
          </div>

          <!-- Mobile Menu Button -->
          <button 
            @click="toggleMobileMenu"
            class="lg:hidden p-3 rounded-xl hover:bg-white/5 transition-all duration-300 border border-white/10"
          >
            <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h14M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Historique from '@/Components/DashboardClient/Historique.vue'

const page = usePage()
const isProfileMenuOpen = ref(false)
const isMobileMenuOpen = ref(false)

const user = computed(() => page.props.auth.user)

const toggleProfileMenu = () => {
  isProfileMenuOpen.value = !isProfileMenuOpen.value
  isMobileMenuOpen.value = false
}

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  isProfileMenuOpen.value = false
}

const logout = () => {
  router.post('/logout')
}
</script>

<style scoped>
/* Styles pour les formes blob organiques */
.blob-shape {
  border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
  animation: blob-morph 8s ease-in-out infinite;
}

.blob-shape-inner {
  border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
}

.blob-shape-large {
  border-radius: 55% 45% 35% 65% / 55% 35% 65% 45%;
  animation: blob-morph-large 10s ease-in-out infinite;
}

.blob-shape-large-inner {
  border-radius: 55% 45% 35% 65% / 55% 35% 65% 45%;
}

@keyframes blob-morph {
  0%, 100% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
  }
  25% {
    border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
  }
  50% {
    border-radius: 50% 60% 30% 60% / 60% 40% 60% 40%;
  }
  75% {
    border-radius: 60% 40% 60% 40% / 30% 60% 40% 70%;
  }
}

@keyframes blob-morph-large {
  0%, 100% {
    border-radius: 55% 45% 35% 65% / 55% 35% 65% 45%;
  }
  20% {
    border-radius: 35% 65% 55% 45% / 45% 55% 35% 65%;
  }
  40% {
    border-radius: 45% 55% 65% 35% / 65% 45% 55% 35%;
  }
  60% {
    border-radius: 65% 35% 45% 55% / 35% 65% 45% 55%;
  }
  80% {
    border-radius: 55% 45% 35% 65% / 45% 55% 65% 35%;
  }
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
}

@keyframes gradient {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.animate-bounce {
  animation: bounce 2s infinite;
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 3s ease infinite;
}

/* Amélioration des transitions et effets */
.group:hover .blob-shape {
  animation-duration: 3s;
}

.group:hover .blob-shape-large {
  animation-duration: 4s;
}

/* Suppression de tous les styles de focus visibles */
button:focus {
  outline: none !important;
  ring: none !important;
  box-shadow: none !important;
}
</style>