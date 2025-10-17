<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Header moderne avec gradient -->
    <header class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border-b border-gray-700/50">
      <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-blue-500/10"></div>
      <div class="relative flex flex-col space-y-6 md:flex-row md:justify-between md:items-center px-6 py-8">
        <div class="space-y-3">
          <h1 class="text-5xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
            Gestion des Cours
          </h1>
          <p class="text-emerald-400 text-xl font-medium flex items-center space-x-2">
            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
            <span>Liste de tous les cours disponibles</span>
          </p>
          <div class="flex items-center space-x-3 text-sm text-gray-400">
            <div class="flex items-center space-x-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span>{{ filteredCourses.length }} cours disponibles</span>
            </div>
            <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
            <span class="text-emerald-400">Mis à jour</span>
          </div>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Bouton Nouveau Cours -->
          <Link 
            href="/courses/create"
            class="group relative px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/25 flex items-center space-x-3 overflow-hidden"
          >
            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
            <span class="relative z-10">Nouveau Cours</span>
          </Link>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Statistiques rapides -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="group relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700/50 rounded-2xl p-6 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm font-medium">Total Cours</p>
              <p class="text-3xl font-bold text-white mt-1">{{ courses.length }}</p>
            </div>
            <div class="p-3 bg-emerald-500/20 rounded-xl backdrop-blur-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700/50 rounded-2xl p-6 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm font-medium">À venir</p>
              <p class="text-3xl font-bold text-white mt-1">{{ upcomingCourses }}</p>
            </div>
            <div class="p-3 bg-blue-500/20 rounded-xl backdrop-blur-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700/50 rounded-2xl p-6 hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm font-medium">Terminés</p>
              <p class="text-3xl font-bold text-white mt-1">{{ pastCourses }}</p>
            </div>
            <div class="p-3 bg-purple-500/20 rounded-xl backdrop-blur-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700/50 rounded-2xl p-6 hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm font-medium">Participants</p>
              <p class="text-3xl font-bold text-white mt-1">{{ totalParticipants }}</p>
            </div>
            <div class="p-3 bg-orange-500/20 rounded-xl backdrop-blur-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-gray-700/50 overflow-hidden shadow-2xl backdrop-blur-sm">
        <!-- Filtres et recherche modernes -->
        <div class="px-8 py-6 bg-gray-800/50 border-b border-gray-700/50 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <!-- Barre de recherche moderne -->
            <div class="relative flex-1 lg:w-80">
              <input 
                type="text" 
                v-model="search" 
                placeholder="Rechercher un cours..." 
                class="w-full pl-12 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-white placeholder-gray-400 transition-all duration-300 backdrop-blur-sm"
              >
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>

            <!-- Filtre moderne -->
            <select 
              v-model="filter" 
              class="bg-gray-700/50 border border-gray-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 backdrop-blur-sm transition-all duration-300"
            >
              <option value="all">Tous les cours</option>
              <option value="upcoming">À venir</option>
              <option value="past">Terminés</option>
            </select>
          </div>

          <!-- Boutons d'action -->
          <div class="flex items-center space-x-3">
            <button class="px-4 py-2 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 rounded-xl transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span>Exporter</span>
            </button>
            <button class="px-4 py-2 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 rounded-xl transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
              </svg>
              <span>Filtrer</span>
            </button>
          </div>
        </div>

        <!-- Vue en grille moderne -->
        <div class="p-8">
          <div v-if="filteredCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="course in filteredCourses" 
              :key="course.id" 
              class="group relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700/50 p-6 rounded-2xl hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 transform hover:scale-105"
            >
              <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
              
              <!-- Header de la carte -->
              <div class="relative flex justify-between items-start mb-4">
                <div class="flex-1">
                  <h3 class="font-bold text-xl text-white mb-2 group-hover:text-emerald-400 transition-colors duration-300">
                    {{ course.name }}
                  </h3>
                  <p class="text-sm text-emerald-400 font-medium flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ course.coach_name }}</span>
                  </p>
                </div>
                
                <!-- Menu d'actions -->
                <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <Link 
                    :href="`/courses/${course.id}/edit`"
                    class="p-2 bg-gray-700/50 hover:bg-emerald-500/20 text-gray-400 hover:text-emerald-400 rounded-lg transition-all duration-200"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                  </Link>
                  <button 
                    @click="confirmDelete(course)" 
                    class="p-2 bg-gray-700/50 hover:bg-red-500/20 text-gray-400 hover:text-red-400 rounded-lg transition-all duration-200"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Description -->
              <p class="text-sm text-gray-400 line-clamp-3 mb-6">
                {{ course.description || 'Aucune description disponible' }}
              </p>

              <!-- Informations du cours -->
              <div class="space-y-4">
                <!-- Participants -->
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm">Participants</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="text-white font-semibold">{{ course.current_participants }}</span>
                    <span class="text-gray-400">/</span>
                    <span class="text-emerald-400 font-semibold">{{ course.max_participants }}</span>
                  </div>

                </div>

                <!-- Barre de progression -->
                <div class="w-full bg-gray-700 rounded-full h-2">
                  <div 
                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full transition-all duration-500" 
                    :style="`width: ${(course.current_participants / course.max_participants) * 100}%`"
                  ></div>
                </div>


                <!-- Date et heure -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-700/50">
                  <div class="flex items-center space-x-2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm">{{ formatDate(course.start_time) }}</span>
                  </div>
                  <div class="flex items-center space-x-2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">{{ formatTime(course.start_time) }} - {{ formatTime(course.end_time) }}</span>
                  </div>
                </div>

                
  <!-- QR Code + bouton impression -->
<div class="relative flex flex-col top-0 right-0 mt-4 space-y-3">
  <img 
    :src="`/storage/qrcodes/course_${course.id}.png`" 
    alt="QR Code" 
    class="w-16 h-168 rounded-lg shadow-md border border-gray-700"
  />

  <!-- Icône d'impression -->
  <button
    @click="printQr(course)"
    class="absolute top-0 right-0 bg-white/10 hover:bg-emerald-500/20 p-2 rounded-full transition-all duration-300"
    title="Imprimer le QR Code"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400 hover:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-4 0H10v4h4v-4z" />
    </svg>
  </button>
</div>

    
   




                <!-- Statut -->
                <div class="flex justify-center pt-2">
                  <span :class="getStatusBadgeClass(course)" class="px-3 py-1 text-xs rounded-full font-semibold">
                    {{ getStatusText(course) }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- État vide moderne -->
          <div v-else class="text-center py-16">
            <div class="flex flex-col items-center space-y-4">
              <div class="p-6 bg-gray-800/50 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-400">Aucun cours trouvé</h3>
              <p class="text-gray-500 max-w-md">
                {{ search ? 'Aucun cours ne correspond à votre recherche.' : 'Commencez par créer votre premier cours.' }}
              </p>
              <Link 
                href="/courses/create"
                class="mt-4 px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105"
              >
                Créer un cours
              </Link>
            </div>
          </div>
        </div>

        <!-- Footer avec pagination -->
        <div class="px-8 py-6 bg-gray-800/50 border-t border-gray-700/50 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="text-sm text-gray-400">
            Affichage de <span class="font-medium text-white">{{ filteredCourses.length }}</span> cours
            {{ search ? `sur ${courses.length} au total` : '' }}
          </div>
          <div class="flex space-x-2">
            <button class="px-4 py-2 rounded-xl bg-gray-700/50 text-gray-400 hover:bg-gray-600/50 hover:text-gray-300 disabled:opacity-50 transition-all duration-300 backdrop-blur-sm" disabled>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <span class="px-4 py-2 bg-emerald-500/20 text-emerald-400 rounded-xl font-medium">1</span>
            <button class="px-4 py-2 rounded-xl bg-gray-700/50 text-gray-400 hover:bg-gray-600/50 hover:text-gray-300 disabled:opacity-50 transition-all duration-300 backdrop-blur-sm" disabled>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation moderne -->
    <div v-if="showDeleteDialog" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 max-w-md w-full mx-4 border border-gray-700/50 shadow-2xl">
        <div class="flex items-center space-x-4 mb-6">
          <div class="p-3 bg-red-500/20 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-xl font-semibold text-white">Supprimer le cours</h3>
            <p class="text-gray-400 text-sm">Cette action est irréversible</p>
          </div>
        </div>
        <p class="text-gray-300 mb-6">
          Êtes-vous sûr de vouloir supprimer le cours <span class="font-semibold text-white">"{{ courseToDelete?.name }}"</span> ?
        </p>
        <div class="flex space-x-3">
          <button 
            @click="showDeleteDialog = false"
            class="flex-1 px-6 py-3 bg-gray-700/50 text-gray-300 rounded-xl hover:bg-gray-600/50 transition-colors font-medium"
          >
            Annuler
          </button>
          <button 
            @click="deleteCourse"
            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-colors font-medium"
          >
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import jsPDF from "jspdf";

const props = defineProps({
  courses: {
    type: Array,
    required: true
  }
});

function printQr(course) {
  const qrUrl = `/storage/qrcodes/course_${course.id}.png`;

  const pdf = new jsPDF({
    orientation: "portrait",
    unit: "mm",
    format: "a4",
  });

  // Titre du cours
  pdf.setFontSize(20);
  pdf.text(course.name, 105, 30, { align: "center" });

  // Ajout du QR code
  const img = new Image();
  img.src = qrUrl;

 img.onload = function () {
  // 🎨 Fond et titre principal
  pdf.setFillColor(245, 245, 245); // fond gris clair
  pdf.rect(0, 0, 210, 297, "F"); // fond sur toute la page

  // 🏷️ Titre du cours
  pdf.setTextColor(33, 37, 41);
  pdf.setFontSize(22);
  pdf.text(course.name, 105, 35, { align: "center" });

  // Ligne décorative sous le titre
  pdf.setDrawColor(0, 150, 136);
  pdf.setLineWidth(0.8);
  pdf.line(40, 40, 170, 40);

  // 🧾 QR code centré
  pdf.addImage(img, "PNG", 60, 55, 90, 90);
  pdf.setDrawColor(200);
  pdf.rect(58, 53, 94, 94); // petite bordure autour du QR

  // 🔹 Informations du cours
  pdf.setFontSize(13);
  pdf.setTextColor(44, 62, 80);
  pdf.text("Informations du cours :", 20, 160);

  // Ligne séparatrice
  pdf.setDrawColor(180);
  pdf.line(20, 162, 190, 162);

  // 📋 Détails alignés proprement
  pdf.setFontSize(11);
  pdf.setTextColor(60, 60, 60);

  const yBase = 172;
  const lineHeight = 8;

  pdf.text(`Coach : ${course.coach_name}`, 20, yBase);
  pdf.text(`CoachID: ${course.coach_id}   |   CoursID: ${course.id}`, 20, yBase + lineHeight);
  pdf.text(`Date : ${new Date(course.start_time).toLocaleDateString()}`, 20, yBase + 2 * lineHeight);
  pdf.text(
    `Heure : ${new Date(course.start_time).toLocaleTimeString()} - ${new Date(course.end_time).toLocaleTimeString()}`,
    20,
    yBase + 3 * lineHeight
  );

  // 🔻 Pied de page stylisé
  pdf.setFontSize(10);
  pdf.setTextColor(100);
  pdf.text("Généré automatiquement par votre application de gestion des cours", 105, 285, {
    align: "center",
  });

  // 💾 Téléchargement
  pdf.save(`QR_${course.name}.pdf`);
};
}

  


const search = ref('');
const filter = ref('all');
const showDeleteDialog = ref(false);
const courseToDelete = ref(null);

// Computed properties
const filteredCourses = computed(() => {
  let result = props.courses;
  
  // Filtre par recherche
  if (search.value) {
    const searchTerm = search.value.toLowerCase();
    result = result.filter(course => 
      course.name?.toLowerCase().includes(searchTerm) || 
      course.description?.toLowerCase().includes(searchTerm) ||
      course.coach_name?.toLowerCase().includes(searchTerm)
    );
  }
  
  // Filtre par statut
  if (filter.value === 'upcoming') {
    result = result.filter(course => new Date(course.start_time) > new Date());
  } else if (filter.value === 'past') {
    result = result.filter(course => new Date(course.start_time) < new Date());
  }
  
  return result;
});

const upcomingCourses = computed(() => {
  return props.courses.filter(course => new Date(course.start_time) > new Date()).length;
});

const pastCourses = computed(() => {
  return props.courses.filter(course => new Date(course.start_time) < new Date()).length;
});

const totalParticipants = computed(() => {
  return props.courses.reduce((total, course) => total + (course.max_participants || 0), 0);
});

// Méthodes
const formatDate = (dateString) => {
  if (!dateString) return 'Date non disponible';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
};

const formatTime = (dateString) => {
  if (!dateString) return 'Heure non disponible';
  const options = { hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleTimeString('fr-FR', options);
};

const getStatusText = (course) => {
  const now = new Date();
  const startTime = new Date(course.start_time);
  const endTime = new Date(course.end_time);
  
  if (now < startTime) return 'À venir';
  if (now > endTime) return 'Terminé';
  return 'En cours';
};

const getStatusBadgeClass = (course) => {
  const now = new Date();
  const startTime = new Date(course.start_time);
  const endTime = new Date(course.end_time);
  
  if (now < startTime) return 'bg-blue-500/20 text-blue-400 border border-blue-500/30';
  if (now > endTime) return 'bg-gray-500/20 text-gray-400 border border-gray-500/30';
  return 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
};

const confirmDelete = (course) => {
  courseToDelete.value = course;
  showDeleteDialog.value = true;
};

const deleteCourse = () => {
  if (courseToDelete.value) {
    router.delete(`/courses/${courseToDelete.value.id}`, {
      onSuccess: () => {
        showDeleteDialog.value = false;
        courseToDelete.value = null;
      },
      onError: (errors) => {
        console.error('Erreur lors de la suppression:', errors);
      }
    });
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Effet de ligne pour les textes tronqués */
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Animations et transitions fluides */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
:style="`width: ${
  course.max_participants > 0 
    ? (course.current_participants / course.max_participants) * 100 
    : 0
}%`"


/* Scrollbar personnalisée pour le thème sombre */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(55, 65, 81, 0.3);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: rgba(16, 185, 129, 0.5);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(16, 185, 129, 0.7);
}

/* Effet de focus moderne */
input:focus, textarea:focus, select:focus {
  transform: translateY(-1px);
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1), 0 4px 12px -2px rgba(16, 185, 129, 0.25);
}

/* Animation de pulsation */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Effet de brillance sur les cartes */
.group:hover .group-hover\:opacity-100 {
  opacity: 1;
}

/* Effet de backdrop blur */
.backdrop-blur-sm {
  backdrop-filter: blur(4px);
}

/* Gradient text */
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}

/* Effet de survol pour les cartes */
.hover\:shadow-2xl:hover {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Animation de rotation */
.group-hover\:rotate-90:hover {
  transform: rotate(90deg);
}
</style> 
