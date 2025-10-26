<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
    <!-- Header -->
    <div class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/80 backdrop-blur-xl">
      <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white">Gestion des Cours</h1>
            <p class="text-slate-400 text-sm mt-1">Administrez vos types de cours et d'abonnements</p>
          </div>
          <div class="flex items-center gap-3">
            <div class="px-3 py-1 bg-cyan-500/10 border border-cyan-500/30 rounded-full">
              <span class="text-cyan-400 text-sm font-medium">{{ courseTypes.length }} cours</span>
            </div>
            <div class="px-3 py-1 bg-violet-500/10 border border-violet-500/30 rounded-full">
              <span class="text-violet-400 text-sm font-medium">{{ subscriptionTypes.length }} abonnements</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
      <!-- Success Notification -->
      <Transition name="slide-fade">
        <div v-if="showNotification" class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg flex items-center gap-3">
          <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
          <span class="text-emerald-400 text-sm">{{ notificationMessage }}</span>
        </div>
      </Transition>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Types de Cours Section -->
        <div class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <div class="w-1 h-6 bg-gradient-to-b from-cyan-500 to-blue-500 rounded-full"></div>
                Types de Cours
              </h2>
              <p class="text-slate-400 text-sm mt-1">{{ courseTypes.length }} cours disponible(s)</p>
            </div>
          </div>

          <!-- Form Ajouter Cours -->
          <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl p-6 backdrop-blur-sm hover:border-slate-600/50 transition-all">
            <h3 class="text-lg font-semibold text-white mb-4">Ajouter un nouveau cours</h3>
            <form @submit.prevent="submitCourse" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Nom du cours</label>
                <input
                  v-model="courseForm.name"
                  type="text"
                  placeholder="Ex: Python Avancé"
                  class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500/30 transition-all"
                />
                <span v-if="courseErrors.name" class="text-red-400 text-xs mt-1 block">{{ courseErrors.name }}</span>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Prix (TND)</label>
                <input
                  v-model.number="courseForm.price"
                  type="number"
                  placeholder="99.99"
                  step="0.01"
                  class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500/30 transition-all"
                />
                <span v-if="courseErrors.price" class="text-red-400 text-xs mt-1 block">{{ courseErrors.price }}</span>
              </div>
              <button
                type="submit"
                :disabled="loadingCourse"
                class="w-full py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
              >
                <span v-if="!loadingCourse">+ Ajouter un cours</span>
                <span v-else class="flex items-center gap-2">
                  <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                  Ajout en cours...
                </span>
              </button>
            </form>
          </div>

          <!-- Liste des Cours -->
          <div class="space-y-3">
            <div v-if="courseTypes.length === 0" class="text-center py-8 text-slate-400">
              <p>Aucun cours trouvé</p>
            </div>
            <div
              v-for="course in courseTypes"
              :key="course.id"
              class="bg-slate-800/40 border border-slate-700/50 rounded-lg p-4 hover:border-cyan-500/30 transition-all group"
            >
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <h4 class="text-white font-semibold group-hover:text-cyan-400 transition-colors">{{ course.name }}</h4>
                  <p class="text-slate-400 text-sm mt-1">{{ course.price }}TND</p>
                </div>
                <button
                  @click="deleteCourse(course.id)"
                  :disabled="deletingCourseId === course.id"
                  class="p-2 hover:bg-red-500/10 rounded-lg text-slate-400 hover:text-red-400 transition-all disabled:opacity-50"
                >
                  <svg v-if="deletingCourseId !== course.id" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Types d'Abonnements Section -->
        <div class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <div class="w-1 h-6 bg-gradient-to-b from-violet-500 to-purple-500 rounded-full"></div>
                Types d'Abonnements
              </h2>
              <p class="text-slate-400 text-sm mt-1">{{ subscriptionTypes.length }} abonnement(s) disponible(s)</p>
            </div>
          </div>

          <!-- Form Ajouter Abonnement -->
          <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl p-6 backdrop-blur-sm hover:border-slate-600/50 transition-all">
            <h3 class="text-lg font-semibold text-white mb-4">Ajouter un nouvel abonnement</h3>
            <form @submit.prevent="submitSubscription" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Nom de l'abonnement</label>
                <input
                  v-model="subscriptionForm.name"
                  type="text"
                  placeholder="Ex: Premium Annuel"
                  class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all"
                />
                <span v-if="subscriptionErrors.name" class="text-red-400 text-xs mt-1 block">{{ subscriptionErrors.name }}</span>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Prix (TND)</label>
                  <input
                    v-model.number="subscriptionForm.price"
                    type="number"
                    placeholder="199.99"
                    step="0.01"
                    class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all"
                  />
                  <span v-if="subscriptionErrors.price" class="text-red-400 text-xs mt-1 block">{{ subscriptionErrors.price }}</span>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Durée (mois)</label>
                  <input
                    v-model.number="subscriptionForm.duration_days"
                    type="number"
                    placeholder="12"
                    class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500/30 transition-all"
                  />
                  <span v-if="subscriptionErrors.duration_days" class="text-red-400 text-xs mt-1 block">{{ subscriptionErrors.duration_days }}</span>
                </div>
              </div>
              <button
                type="submit"
                :disabled="loadingSubscription"
                class="w-full py-2 bg-gradient-to-r from-violet-500 to-purple-500 hover:from-violet-600 hover:to-purple-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
              >
                <span v-if="!loadingSubscription">+ Ajouter un abonnement</span>
                <span v-else class="flex items-center gap-2">
                  <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                  Ajout en cours...
                </span>
              </button>
            </form>
          </div>

          <!-- Liste des Abonnements -->
          <div class="space-y-3">
            <div v-if="subscriptionTypes.length === 0" class="text-center py-8 text-slate-400">
              <p>Aucun abonnement trouvé</p>
            </div>
            <div
              v-for="subscription in subscriptionTypes"
              :key="subscription.id"
              class="bg-slate-800/40 border border-slate-700/50 rounded-lg p-4 hover:border-violet-500/30 transition-all group"
            >
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <h4 class="text-white font-semibold group-hover:text-violet-400 transition-colors">{{ subscription.name }}</h4>
                  <p class="text-slate-400 text-sm mt-1">{{ subscription.price }}TND / {{ subscription.duration_days }} mois</p>
                </div>
                <button
                  @click="deleteSubscription(subscription.id)"
                  :disabled="deletingSubscriptionId === subscription.id"
                  class="p-2 hover:bg-red-500/10 rounded-lg text-slate-400 hover:text-red-400 transition-all disabled:opacity-50"
                >
                  <svg v-if="deletingSubscriptionId !== subscription.id" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// Props from Inertia
defineProps({
  courseTypes: {
    type: Array,
    default: () => []
  },
  subscriptionTypes: {
    type: Array,
    default: () => []
  }
})

// State
const showNotification = ref(false)
const notificationMessage = ref('')
const loadingCourse = ref(false)
const loadingSubscription = ref(false)
const deletingCourseId = ref(null)
const deletingSubscriptionId = ref(null)

// Course Form
const courseForm = ref({ name: '', price: '' })
const courseErrors = ref({})

// Subscription Form
const subscriptionForm = ref({ name: '', price: '', duration_days: '' })
const subscriptionErrors = ref({})

// Methods
const validateCourseForm = () => {
  courseErrors.value = {}
  if (!courseForm.value.name.trim()) {
    courseErrors.value.name = 'Le nom du cours est requis'
  }
  if (!courseForm.value.price || courseForm.value.price <= 0) {
    courseErrors.value.price = 'Le prix doit être supérieur à 0'
  }
  return Object.keys(courseErrors.value).length === 0
}

const validateSubscriptionForm = () => {
  subscriptionErrors.value = {}
  if (!subscriptionForm.value.name.trim()) {
    subscriptionErrors.value.name = "Le nom de l'abonnement est requis"
  }
  if (!subscriptionForm.value.price || subscriptionForm.value.price <= 0) {
    subscriptionErrors.value.price = 'Le prix doit être supérieur à 0'
  }
  if (!subscriptionForm.value.duration_days || subscriptionForm.value.duration_days <= 0) {
    subscriptionErrors.value.duration_days = 'La durée doit être supérieure à 0'
  }
  return Object.keys(subscriptionErrors.value).length === 0
}

const submitCourse = () => {
  if (!validateCourseForm()) return

  loadingCourse.value = true
  router.post(route('admin.course-types.store'), courseForm.value, {
    onSuccess: () => {
      notificationMessage.value = `Cours "${courseForm.value.name}" ajouté avec succès!`
      showNotification.value = true
      courseForm.value = { name: '', price: '' }
      loadingCourse.value = false
      setTimeout(() => {
        showNotification.value = false
      }, 5000)
    },
    onError: () => {
      loadingCourse.value = false
    }
  })
}

const submitSubscription = () => {
  if (!validateSubscriptionForm()) return

  loadingSubscription.value = true
  router.post(route('admin.subscription-types.store'), subscriptionForm.value, {
    onSuccess: () => {
      notificationMessage.value = `Abonnement "${subscriptionForm.value.name}" ajouté avec succès!`
      showNotification.value = true
      subscriptionForm.value = { name: '', price: '', duration: '' }
      loadingSubscription.value = false
      setTimeout(() => {
        showNotification.value = false
      }, 5000)
    },
    onError: () => {
      loadingSubscription.value = false
    }
  })
}

const deleteCourse = (id) => {
  deletingCourseId.value = id
  router.delete(route('admin.course-types.destroy', id), {
    onSuccess: () => {
      notificationMessage.value = 'Cours supprimé avec succès!'
      showNotification.value = true
      deletingCourseId.value = null
      setTimeout(() => {
        showNotification.value = false
      }, 3000)
    },
    onError: () => {
      deletingCourseId.value = null
    }
  })
}

const deleteSubscription = (id) => {
  deletingSubscriptionId.value = id
  router.delete(route('admin.subscription-types.destroy', id), {
    onSuccess: () => {
      notificationMessage.value = 'Abonnement supprimé avec succès!'
      showNotification.value = true
      deletingSubscriptionId.value = null
      setTimeout(() => {
        showNotification.value = false
      }, 3000)
    },
    onError: () => {
      deletingSubscriptionId.value = null
    }
  })
}
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from {
  transform: translateX(10px);
  opacity: 0;
}

.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>