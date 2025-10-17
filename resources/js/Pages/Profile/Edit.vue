<template>
  <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Modifier mon profil</h1>

    <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
      <!-- Photo -->
      <div class="flex items-center gap-4">
        <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 border border-gray-300">
          <img
            v-if="photoPreview"
            :src="photoPreview"
            alt="Photo de profil"
            class="object-cover w-full h-full"
          />
          <img
            v-else-if="user.photo"
            :src="`/storage/${user.photo}`"
            alt="Photo de profil"
            class="object-cover w-full h-full"
          />
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            class="h-12 w-12 text-gray-400 m-auto"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z" />
          </svg>
        </div>
        <div class="flex flex-col gap-2">
          <label
            for="photo"
            class="cursor-pointer px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-center"
          >
            Changer la photo
            <input
              id="photo"
              type="file"
              accept="image/*"
              @change="onPhotoSelected"
              class="hidden"
            />
          </label>
          <button
            v-if="user.photo || photoPreview"
            type="button"
            @click="removePhoto"
            class="text-sm text-red-600 hover:text-red-800"
          >
            Supprimer
          </button>
        </div>
      </div>
      <div v-if="errors.photo" class="text-red-600 text-sm">{{ errors.photo }}</div>

      <!-- Nom -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
        <input
          id="name"
          type="text"
          v-model="form.name"
          required
          class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-300 focus:ring-opacity-50"
        />
        <div v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</div>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          id="email"
          type="email"
          v-model="form.email"
          required
          class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-300 focus:ring-opacity-50"
        />
        <div v-if="errors.email" class="text-red-600 text-sm">{{ errors.email }}</div>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">
          Nouveau mot de passe <span class="text-gray-400">(laisser vide pour ne pas changer)</span>
        </label>
        <div class="relative">
          <input
            id="password"
            :type="showPassword ? 'text' : 'password'"
            v-model="form.password"
            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-300 focus:ring-opacity-50"
            autocomplete="new-password"
          />
          <button
            type="button"
            @click="showPassword = !showPassword"
            class="absolute right-3 top-2 text-gray-500 hover:text-gray-700"
          >
            <span v-if="showPassword">👁️</span>
            <span v-else>👁️‍🗨️</span>
          </button>
        </div>
        <div v-if="errors.password" class="text-red-600 text-sm">{{ errors.password }}</div>
      </div>

      <!-- Password Confirmation -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
          Confirmer le mot de passe
        </label>
        <input
          id="password_confirmation"
          :type="showPassword ? 'text' : 'password'"
          v-model="form.password_confirmation"
          class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-300 focus:ring-opacity-50"
          autocomplete="new-password"
        />
      </div>

      <!-- Boutons -->
      <div class="flex items-center gap-4 pt-4">
        <button
          type="submit"
          :disabled="form.processing"
          class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded disabled:opacity-50 transition"
        >
          <span v-if="form.processing">Enregistrement...</span>
          <span v-else>Sauvegarder</span>
        </button>
        <button
          type="button"
          @click="resetForm"
          class="text-gray-600 hover:text-gray-900"
        >
          Annuler
        </button>
      </div>

      <!-- Messages -->
      <div v-if="flash.success" class="mt-4 p-3 bg-green-100 text-green-700 rounded">
        {{ flash.success }}
      </div>
      <div v-if="flash.error" class="mt-4 p-3 bg-red-100 text-red-700 rounded">
        {{ flash.error }}
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  errors: Object
})

const { flash } = usePage().props

const form = useForm({
  name: '',
  email: '',
  photo: null,
  password: '',
  password_confirmation: ''
})

const photoPreview = ref(null)
const showPassword = ref(false)

// Initialisation après le montage
onMounted(() => {
  form.name = props.user.name
  form.email = props.user.email
})

const onPhotoSelected = (e) => {
  const file = e.target.files[0]
  if (!file) return
  
  form.photo = file
  photoPreview.value = URL.createObjectURL(file)
}

const removePhoto = () => {
  form.photo = 'REMOVE' // Spécial value pour indiquer la suppression
  photoPreview.value = null
}

const resetForm = () => {
  form.reset()
  form.name = props.user.name
  form.email = props.user.email
  photoPreview.value = null
}

const submit = () => {
  form.transform(data => ({
    ...data,
    // Force la méthode PUT pour Laravel
    _method: 'PUT'
  })).post(route('coach.profile.update'), {
    preserveScroll: true,
    onSuccess: () => photoPreview.value = null
  })
}
</script>