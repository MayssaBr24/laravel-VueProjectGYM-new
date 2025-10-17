<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'client', // Valeur par défaut
});

const showPassword = ref(false);

const submit = () => {
  form.post(route('admin.users.store'), {
    onSuccess: () => {
      form.reset();
      // Redirection vers le dashboard admin après création
      window.location.href = route('admin.dashboard');
    }
  });
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-900 p-6 relative overflow-hidden">
    <!-- Effets visuels -->
    <div class="absolute w-80 h-80 bg-green-500 opacity-10 rounded-full blur-3xl -top-20 -left-20"></div>
    <div class="absolute w-80 h-80 bg-green-500 opacity-10 rounded-full blur-3xl bottom-10 right-10"></div>

    <div class="w-full max-w-md bg-gray-800 rounded-2xl shadow-2xl p-8 space-y-6 z-10 border border-gray-700">
      <div class="text-center">
        <h1 class="text-3xl font-bold text-green-400 mb-2">Créer un nouveau compte</h1>
        <p class="text-gray-400">Formulaire réservé à l'administration</p>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Champ Nom -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nom complet</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
            required
            placeholder="Nom du membre"
          >
          <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
        </div>

        <!-- Champ Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
            required
            placeholder="email@exemple.com"
          >
          <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
        </div>

        <!-- Champ Mot de passe -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Mot de passe</label>
          <div class="relative">
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent pr-10"
              required
              placeholder="••••••••"
            >
            <button
              type="button"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-400"
              @click="showPassword = !showPassword"
            >
              <EyeIcon v-if="showPassword" class="h-5 w-5" />
              <EyeOffIcon v-else class="h-5 w-5" />
            </button>
          </div>
          <p v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</p>
        </div>

        <!-- Confirmation Mot de passe -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirmation</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
            required
            placeholder="••••••••"
          >
        </div>

        <!-- Sélection du Rôle -->
        <div>
          <label for="role" class="block text-sm font-medium text-gray-300 mb-1">Type de compte</label>
          <select
            id="role"
            v-model="form.role"
            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
          >
            <option value="client">Client</option>
            <option value="coach">Coach</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>

        <!-- Bouton de soumission -->
        <button
          type="submit"
          class="w-full py-3 px-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold rounded-lg shadow-md transition-all duration-300 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-50 flex items-center justify-center gap-2"
          :disabled="form.processing"
          :class="{ 'opacity-70 cursor-not-allowed': form.processing }"
        >
          <span v-if="!form.processing">Créer le compte</span>
          <span v-else>Création en cours...</span>
          <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
        </button>
      </form>

      <!-- Lien vers le dashboard -->
      <div class="text-center text-gray-400 text-sm">
        <Link :href="route('admin.dashboard')" class="text-green-400 hover:underline">Retour au dashboard</Link>
      </div>
    </div>
  </div>
</template>