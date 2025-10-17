<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-12 px-4">
    <div class="max-w-4xl mx-auto space-y-8">
      <!-- Notifications de succès -->
      <div v-if="showSuccessNotification" class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-emerald-600 text-white px-6 py-4 rounded-xl shadow-2xl border border-emerald-400/30 flex items-center space-x-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="font-semibold">{{ successMessage }}</span>
          <button @click="showSuccessNotification = false" class="text-emerald-200 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
      <!-- Remplacez le bouton de retour par ceci : -->
<div class="mb-6">
  <Link 
    :href="route('admin.dashboard')" 
    class="inline-flex items-center space-x-2 text-emerald-400 hover:text-emerald-300 transition-colors duration-300 group"
  >
    <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span class="font-semibold">Retour au Dashboard</span>
  </Link>
</div>

      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-2">Gestion des Cours</h1>
        <p class="text-gray-300">Configurez vos types de cours et abonnements</p>
      </div>

      <!-- Formulaire pour ajouter un type de cours -->
      <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-8 py-6">
          <h2 class="text-2xl font-bold text-white flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <span>Ajouter un type de cours</span>
          </h2>
        </div>
        
        <div class="p-8">
          <form @submit.prevent="submitCourseType" class="space-y-6">
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-200">Nom du type</label>
              <input 
                v-model="courseForm.name" 
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-600 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-900/20 transition-all duration-300 outline-none text-white bg-gray-700 placeholder-gray-400"
                placeholder="Ex: Cours de yoga, Pilates..."
              />
              <div v-if="courseForm.errors.name" class="text-red-400 text-sm font-medium flex items-center space-x-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ courseForm.errors.name }}</span>
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-200">Prix (TND)</label>
              <input 
                v-model="courseForm.price" 
                type="number" 
                step="0.01" 
                class="w-full px-4 py-3 border-2 border-gray-600 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-900/20 transition-all duration-300 outline-none text-white bg-gray-700 placeholder-gray-400"
                placeholder="0.00"
              />
              <div v-if="courseForm.errors.price" class="text-red-400 text-sm font-medium flex items-center space-x-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ courseForm.errors.price }}</span>
              </div>
            </div>
            
            <button 
              type="submit" 
              :disabled="courseForm.processing"
              class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2 shadow-lg border border-emerald-500/30 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
              <svg v-if="courseForm.processing" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828m12.728 0l-2.828-2.828M7.464 6.464L4.636 3.636" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <span>{{ courseForm.processing ? 'Création en cours...' : 'Ajouter type de cours' }}</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Formulaire pour ajouter un type d'abonnement -->
      <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-8 py-6">
          <h2 class="text-2xl font-bold text-white flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            <span>Ajouter un type d'abonnement</span>
          </h2>
        </div>
        
        <div class="p-8">
          <form @submit.prevent="submitSubscription" class="space-y-6">
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-200">Nom d'abonnement</label>
              <input 
                v-model="subForm.name" 
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-600 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-900/20 transition-all duration-300 outline-none text-white bg-gray-700 placeholder-gray-400"
                placeholder="Ex: Abonnement mensuel, Forfait annuel..."
              />
              <div v-if="subForm.errors.name" class="text-red-400 text-sm font-medium flex items-center space-x-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ subForm.errors.name }}</span>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-200">Prix (TND)</label>
                <input 
                  v-model="subForm.price" 
                  type="number" 
                  step="0.01" 
                  class="w-full px-4 py-3 border-2 border-gray-600 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-900/20 transition-all duration-300 outline-none text-white bg-gray-700 placeholder-gray-400"
                  placeholder="0.00"
                />
                <div v-if="subForm.errors.price" class="text-red-400 text-sm font-medium flex items-center space-x-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  <span>{{ subForm.errors.price }}</span>
                </div>
              </div>
              
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-200">Durée (jours)</label>
                <input 
                  v-model="subForm.duration_days" 
                  type="number" 
                  class="w-full px-4 py-3 border-2 border-gray-600 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-900/20 transition-all duration-300 outline-none text-white bg-gray-700 placeholder-gray-400"
                  placeholder="30"
                />
                <div v-if="subForm.errors.duration_days" class="text-red-400 text-sm font-medium flex items-center space-x-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  <span>{{ subForm.errors.duration_days }}</span>
                </div>
              </div>
            </div>
            
            <button 
              type="submit" 
              :disabled="subForm.processing"
              class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2 shadow-lg border border-emerald-500/30 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
              <svg v-if="subForm.processing" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828m12.728 0l-2.828-2.828M7.464 6.464L4.636 3.636" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <span>{{ subForm.processing ? 'Création en cours...' : 'Ajouter abonnement' }}</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
const showSuccessNotification = ref(false);
const successMessage = ref('');

const courseForm = useForm({
  name: '',
  price: 0
});

const subForm = useForm({
  name: '',
  price: 0,
  duration_days: 0
});

const showSuccess = (message) => {
  successMessage.value = message;
  showSuccessNotification.value = true;
  
  // Masquer automatiquement après 5 secondes
  setTimeout(() => {
    showSuccessNotification.value = false;
  }, 5000);
};

const submitCourseType = () => {
  courseForm.post(route('admin.course-types.store'), {
    onSuccess: () => {
      showSuccess('Type de cours créé avec succès !'); // ← CETTE LIGNE ÉTAIT MANQUANTE
      courseForm.reset();
    },
    onError: (errors) => {
      console.log('Erreurs du formulaire cours:', errors);
    },
    preserveScroll: true
  });
};

const submitSubscription = () => {
  subForm.post(route('admin.subscription-types.store'), {
    onSuccess: () => {
      showSuccess('Type d\'abonnement créé avec succès !');
      subForm.reset();
    },
    onError: (errors) => {
      console.log('Erreurs du formulaire abonnement:', errors);
    },
    preserveScroll: true
  });
};
</script>

<style scoped>
/* Styles supplémentaires pour améliorer le thème sombre */
.shadow-3xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Animation pour la notification */
.animate-fade-in {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Animation de spin pour le chargement */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Amélioration du focus pour le thème sombre */
input:focus {
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
</style>