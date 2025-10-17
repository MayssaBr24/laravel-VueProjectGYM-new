<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <!-- Main Content -->
    <div class=" p-8">
      <div class="max-w-4xl mx-auto">
      
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-white mb-2">
            Modifier mon <span class="bg-gradient-to-r from-emerald-400 to-emerald-600 bg-clip-text text-transparent">Profil</span>
          </h1>
           <p class="text-gray-400">Personnalisez vos informations et préférences</p>
        </div>
        

        <!-- Profile Form -->
        <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-xl rounded-2xl p-8 border border-gray-700/50">
          <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-8">
            <!-- avatar Section -->
            <div class="flex flex-col sm:flex-row items-start gap-6">
              <div class="relative group">
                <div class="w-32 h-32 rounded-2xl overflow-hidden bg-gradient-to-br from-gray-700 to-gray-800 border-2 border-gray-600 group-hover:border-emerald-500 transition-all duration-300">
                  <img
                    v-if="avatarPreview"
                    :src="avatarPreview"
                    alt="avatar de profil"
                    class="object-cover w-full h-full"
                  />
                  <img
                    v-else-if="props.user && props.user.avatar_url"
                    :src="props.user.avatar_url"
                    alt="avatar de profil"
                    class="object-cover w-full h-full"
                  />


                  <div v-else class="flex items-center justify-center h-full">
                    <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                </div>
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
              </div>
              
              <div class="flex-1">
                <label
                  for="avatar"
                  class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 rounded-xl font-medium transition-all cursor-pointer border border-emerald-500/30 hover:border-emerald-500/50"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Changer la avatar
                  <input
                    id="avatar"
                    type="file"
                    accept="image/*"
                    @change="onavatarSelected"
                    class="hidden"
                  />
                </label>
                <p class="text-gray-400 text-sm mt-2">JPG, PNG ou GIF. Max 2MB.</p>
                <div v-if="errors.avatar" class="text-red-400 text-sm mt-2 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  </svg>
                  {{ errors.avatar }}
                </div>
              </div>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Name -->
              <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-300">
                  Nom complet
                </label>
                <input
                  id="name"
                  type="text"
                  v-model="form.name"
                  class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                  placeholder="Votre nom complet"
                />
                <div v-if="errors.name" class="text-red-400 text-sm flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  </svg>
                  {{ errors.name }}
                </div>
              </div>

              <!-- Email -->
              <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-300">
                  Adresse email
                </label>
                <input
                  id="email"
                  type="email"
                  v-model="form.email"
                  class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                  placeholder="votre@email.com"
                />
                <div v-if="errors.email" class="text-red-400 text-sm flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  </svg>
                  {{ errors.email }}
                </div>
              </div>

              <!-- Password -->
              <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-300">
                  Nouveau mot de passe
                </label>
                <input
                  id="password"
                  type="password"
                  v-model="form.password"
                  class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                  placeholder="Laisser vide pour ne pas changer"
                  autocomplete="new-password"
                />
                <p class="text-gray-400 text-xs">Laisser vide pour conserver le mot de passe actuel</p>
                <div v-if="errors.password" class="text-red-400 text-sm flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  </svg>
                  {{ errors.password }}
                </div>
              </div>

              <!-- Password Confirmation -->
              <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300">
                  Confirmer le mot de passe
                </label>
                <input
                  id="password_confirmation"
                  type="password"
                  v-model="form.password_confirmation"
                  class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                  placeholder="Confirmer le nouveau mot de passe"
                  autocomplete="new-password"
                />
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-700/50">
              <button
                type="submit"
                :disabled="processing"
                class="flex-1 sm:flex-none px-8 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 disabled:from-gray-600 disabled:to-gray-700 text-white font-semibold rounded-xl transition-all duration-300 hover:transform hover:scale-105 disabled:transform-none disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                <svg v-if="processing" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ processing ? 'Sauvegarde...' : 'Sauvegarder' }}</span>
              </button>
              
              <button
                type="button"
                @click="resetForm"
                class="px-6 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-white rounded-xl font-medium transition-all border border-gray-600/50 hover:border-gray-500/50 flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Annuler</span>
              </button>
            </div>

            <!-- Success Message -->
            <div v-if="flash.success" class="bg-emerald-500/20 border border-emerald-500/30 rounded-xl p-4 flex items-center gap-3">
              <div class="w-8 h-8 bg-emerald-500/30 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <p class="text-emerald-300 font-medium">{{ flash.success }}</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { usePage, useForm, Link } from '@inertiajs/vue3'


const props = defineProps({
  user: Object,
  Role: String,
  errors: Object
})

const flash = computed(() => usePage().props.flash || {})

const form = useForm({
  name: props.user?.name ?? '',
  email: props.user?.email ?? '',
  avatar: props.user?.avatar ?? '',

  password: '',
  password_confirmation: ''
})

const processing = ref(false)
const avatarPreview = ref(null)

function onavatarSelected(event) {
  const file = event.target.files[0]
  if (!file) return
  
  
  
  if (file.size > 2048 * 1024) {
    form.errors.avatar = 'L\'image ne doit pas dépasser 2MB'
    return
  }
  
  form.avatar = file
  avatarPreview.value = URL.createObjectURL(file)
}

function resetForm() {
  form.reset()
  avatarPreview.value = null
}

async function submit() {
  processing.value = true
  
  try {
    // Créer manuellement le FormData pour mieux contrôler
    const formData = new FormData()
    
    // Toujours inclure name et email
    formData.append('name', form.name)
    formData.append('email', form.email)
    
    // Inclure l'avatar seulement si sélectionné
    if (form.avatar instanceof File) {
      formData.append('avatar', form.avatar)
    }
    
    // Inclure le mot de passe seulement si rempli
    if (form.password) {
      formData.append('password', form.password)
      formData.append('password_confirmation', form.password_confirmation)
    }
    
    // Spoof PUT method
    formData.append('_method', 'put')

    // Utiliser axios directement
    await axios.post(route('profile.update'), formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    // Recharger pour voir les changements
    window.location.reload()
    
  } catch (error) {
    if (error.response?.data?.errors) {
      form.errors = error.response.data.errors
    }
  } finally {
    processing.value = false
  }
}
</script>
