<template>
  <header class="bg-gray-900/95 backdrop-blur-xl border-b border-gray-700/50 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          
          <!-- Welcome Message - Version grande et grasse -->
          <div class="hidden md:block ml-2">
            <div class="text-2xl font-extrabold tracking-tight">
              <span class="text-gray-300">Bienvenue, </span>
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">
                {{ user.name }} 
              </span>
              <span class="text-emerald-400 animate-bounce inline-block">!</span>
            </div>
          </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
                <AdminNotifications />

          <!-- Profile Dropdown -->
          <div class="relative">
            <button 
              @click="toggleProfileMenu" 
              class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-800/50 transition-all duration-200 group"
            >
              <div class="relative">
                <img
                  :src="user.avatar_url || '/default-avatar.png'"
                  class="h-10 w-10 rounded-full object-cover border-2 border-gray-600 group-hover:border-emerald-400 transition-all duration-200"
                  alt="Photo de profil"
                />
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-gray-900"></div>
              </div>
              <div class="hidden sm:block text-left">
                <p class="text-white font-bold text-sm">{{ user.name }}</p>
                <p class="text-gray-400 text-xs">Coach Expert</p>
              </div>
              
              <svg 
                class="w-4 h-4 text-gray-400 group-hover:text-white transition-all duration-200"
                :class="{ 'rotate-180': isProfileMenuOpen }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <div
                v-show="isProfileMenuOpen"
                @click.away="isProfileMenuOpen = false"
                class="absolute right-0 mt-2 w-72 bg-gray-800/95 backdrop-blur-xl border border-gray-700/50 rounded-2xl shadow-2xl z-50 overflow-hidden"
              >
                <!-- Profile Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-gray-800/50 to-gray-700/50 border-b border-gray-700/50">
                  <div class="flex items-center gap-4">
                   <img
                    v-if="user"
                    :src="user.avatar_url || '/default-avatar.png'"
                    alt="Avatar"
                    class="rounded-full w-24 h-24 object-cover"
                  />
                  <img
                    v-else
                    src="/default-avatar.png"
                    alt="Avatar par défaut"
                    class="rounded-full w-24 h-24 object-cover"
                  />
                    <div class="flex-1">
                      <p class="text-white font-semibold">{{ user.name }}</p>
                      <p class="text-gray-400 text-sm">{{ user.email }}</p>
                      <div class="flex items-center gap-2 mt-1">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <span class="text-emerald-400 text-xs font-medium">En ligne</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Menu Items -->
                <div class="py-2">
                  <Link
                    :href="route('profile.show')"
                    class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-700/50 transition-all duration-200"
                  >
                    <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    <div>
                      <p class="font-medium">Mon Profil</p>
                      <p class="text-xs text-gray-500">Gérer mes informations</p>
                    </div>
                  </Link>

                  <div class="border-t border-gray-700/50 my-2"></div>

                  <button
                    @click="logout"
                    class="w-full flex items-center gap-3 px-6 py-3 text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all duration-200"
                  >
                    <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                      </svg>
                    </div>
                    <div class="text-left">
                      <p class="font-medium">Déconnexion</p>
                      <p class="text-xs text-gray-500">Se déconnecter du compte</p>
                    </div>
                  </button>
                </div>
              </div>
            </transition>
          </div>

          <!-- Mobile Menu Button -->
          <button 
            @click="toggleMobileMenu"
            class="lg:hidden p-2 rounded-xl hover:bg-gray-800/50 transition-all duration-200"
          >
            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
import AdminNotifications from '@/Components/dashboardAdmin/AdminNotifications.vue'

const page = usePage()
const isProfileMenuOpen = ref(false)
const isMobileMenuOpen = ref(false)

const user = computed(() => page.props.auth.user)

const isCurrentRoute = (routeName) => {
  return route().current(routeName)
}

const toggleProfileMenu = () => {
  isProfileMenuOpen.value = !isProfileMenuOpen.value
  isMobileMenuOpen.value = false
}

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  isProfileMenuOpen.value = false
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<style>
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-5px);
  }
}
.animate-bounce {
  animation: bounce 1s infinite;
}
</style>