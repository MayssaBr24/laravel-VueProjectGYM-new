<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h3 class="text-2xl font-bold text-white">Liste des Membres</h3>
      <div class="flex items-center space-x-4">
        <Link
          href="/admin/users/create"
          class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 flex items-center space-x-2 shadow-lg"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          <span>Nouveau Membre</span>
        </Link>
        <div class="relative">
          <input
            :value="searchTerm"
            @input="$emit('search', $event.target.value)"
            type="text"
            placeholder="Rechercher un membre..."
            class="pl-12 pr-4 py-3 bg-gray-700/50 border border-gray-600 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-80 placeholder-gray-400 transition-all duration-300 backdrop-blur-sm"
          >
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation -->
    <DeleteConfirmationModal
      :show="showDeleteDialog"
      :message="`Êtes-vous sûr de vouloir supprimer le membre ${memberToDelete?.name} ?`"
      title="Supprimer le membre"
      @confirm="deleteMember"
      @cancel="showDeleteDialog = false"
    />

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500"></div>
      <span class="ml-3 text-gray-400">Chargement des membres...</span>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="bg-red-500/20 border border-red-500/30 rounded-xl p-4 mb-6">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-red-300">{{ errorMessage }}</span>
      </div>
    </div>

    <!-- Members Table -->
    <div v-if="!loading" class="overflow-x-auto rounded-xl border border-gray-700/50 bg-gray-800/50 backdrop-blur-sm">
      <table class="min-w-full divide-y divide-gray-700">
        <thead class="bg-gray-700/50">
          <tr>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Membre</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Email</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Rôle</th>
            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-gray-800/30 divide-y divide-gray-700/50">
          <tr v-for="member in members" :key="member.id" class="hover:bg-gray-700/30 transition-colors duration-200">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
                  <span class="text-white font-bold text-lg">{{ member.name ? member.name.charAt(0).toUpperCase() : 'U' }}</span>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-semibold text-white">{{ member.name }}</div>
                  <div class="text-sm text-gray-400">ID: {{ member.id }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ member.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-3 py-1 text-xs rounded-full font-semibold" :class="getRoleBadgeClass(member.role)">
                {{ getRoleLabel(member.role) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <Link :href="route('admin.members.edit', member.id)" class="text-emerald-400 hover:text-emerald-300 mr-4 transition-colors font-medium">Éditer</Link>
              <button @click="confirmDelete(member)" class="text-red-400 hover:text-red-300 transition-colors font-medium">Supprimer</button>
            </td>
          </tr>
          <tr v-if="members.length === 0 && !loading">
            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
              <div class="flex flex-col items-center space-y-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>{{ searchTerm ? 'Aucun membre trouvé pour cette recherche' : 'Aucun membre trouvé' }}</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import DeleteConfirmationModal from '@/Components/dashboardAdmin/DeleteConfirmationModal.vue';

defineProps({
  members: Array,
  loading: Boolean,
  errorMessage: String,
  searchTerm: String
});

defineEmits(['search']);

const showDeleteDialog = ref(false);
const memberToDelete = ref(null);

const confirmDelete = (member) => {
  memberToDelete.value = member;
  showDeleteDialog.value = true;
};

const deleteMember = () => {
  if (!memberToDelete.value) return;
  router.delete(`/admin/members/${memberToDelete.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteDialog.value = false;
      memberToDelete.value = null;
      fetchMembers();
    },
  });
};

const getRoleBadgeClass = (role) => {
  const classes = {
    'member': 'bg-emerald-500/20 text-emerald-400',
    'client': 'bg-blue-500/20 text-blue-400',
    'user': 'bg-gray-500/20 text-gray-400'
  };
  return classes[role] || classes.user;
};

const getRoleLabel = (role) => {
  const labels = {
    'member': 'Membre',
    'client': 'Client',
    'user': 'Utilisateur'
  };
  return labels[role] || 'Utilisateur';
};
</script>